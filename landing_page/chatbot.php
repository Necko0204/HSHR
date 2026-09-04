<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
hshr_start_session('public_session', '/');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');

function chatbotResponse(int $status, array $payload): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') chatbotResponse(405, ['error' => 'Method not allowed.']);
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 16 * 1024) chatbotResponse(413, ['error' => 'Request is too large.']);
if (!hshr_rate_limit_consume('public-chatbot', 'chat', 20, 60)) {
    header('Retry-After: 60');
    chatbotResponse(429, ['error' => 'Too many messages. Please wait a minute and try again.']);
}

try {
    $data = json_decode((string) file_get_contents('php://input'), true, 16, JSON_THROW_ON_ERROR);
} catch (JsonException $error) {
    chatbotResponse(400, ['error' => 'Request body must be valid JSON.']);
}

$message = trim((string) ($data['message'] ?? ''));
if ($message === '' || strlen($message) > 4000) {
    chatbotResponse(422, ['error' => 'Message must contain between 1 and 1,000 characters.']);
}

$apiKey = trim((string) getenv('OPENAI_API_KEY'));
if ($apiKey === '') chatbotResponse(503, ['error' => 'The assistant is not configured.']);
if (!function_exists('curl_init')) chatbotResponse(503, ['error' => 'The assistant service is unavailable.']);

$requestBody = json_encode([
    'model' => trim((string) (getenv('OPENAI_MODEL') ?: 'gpt-5.6')),
    'instructions' => 'You are the concise, friendly public information assistant for Holy Spirit School of Imus. Answer only general school, enrollment, and employment-application questions. Do not request or reveal sensitive personal, employee, credential, payroll, or internal system data. If unsure, direct the visitor to the school office.',
    'input' => $message,
    'max_output_tokens' => 300,
], JSON_THROW_ON_ERROR);

$curl = curl_init('https://api.openai.com/v1/responses');
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS => $requestBody,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 25,
    CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
]);
$rawResponse = curl_exec($curl);
$status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
$curlError = curl_error($curl);
curl_close($curl);

if (!is_string($rawResponse) || $status < 200 || $status >= 300) {
    error_log('OpenAI chatbot request failed with HTTP ' . $status . ($curlError !== '' ? ': ' . $curlError : ''));
    chatbotResponse(502, ['error' => 'The assistant could not answer right now.']);
}

try {
    $decoded = json_decode($rawResponse, true, 64, JSON_THROW_ON_ERROR);
} catch (JsonException $error) {
    error_log('OpenAI chatbot returned invalid JSON.');
    chatbotResponse(502, ['error' => 'The assistant returned an invalid response.']);
}

$parts = [];
foreach (($decoded['output'] ?? []) as $item) {
    if (($item['type'] ?? '') !== 'message') continue;
    foreach (($item['content'] ?? []) as $content) {
        if (($content['type'] ?? '') === 'output_text' && is_string($content['text'] ?? null)) {
            $parts[] = $content['text'];
        }
    }
}
$reply = trim(implode("\n", $parts));
if ($reply === '') chatbotResponse(502, ['error' => 'The assistant did not return a text answer.']);

chatbotResponse(200, ['response' => $reply]);
