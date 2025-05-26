<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$apiKey = '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'; // Replace with your OpenAI API key
$url = 'https://api.openai.com/v1/chat/completions';

// Read the incoming JSON request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['message'])) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

$message = $data['message'];

$postData = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [['role' => 'user', 'content' => $message]],
    'temperature' => 0.7
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
curl_close($ch);

$decodedResponse = json_decode($response, true);
$botReply = $decodedResponse['choices'][0]['message']['content'] ?? 'Sorry, I encountered an error.';

echo json_encode(['response' => $botReply]);
?>
