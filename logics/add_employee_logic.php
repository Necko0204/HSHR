<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_api.php';
require_once __DIR__ . '/db_config.php';

function addEmployeeResponse(int $status, bool $success, string $message, array $data = []): never
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') addEmployeeResponse(405, false, 'Method not allowed.');
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 35 * 1024 * 1024) addEmployeeResponse(413, false, 'Employee form is too large.');

function employeePost(string $key, int $maximum, bool $required = false): ?string
{
    $value = trim((string) ($_POST[$key] ?? ''));
    if ($required && $value === '') addEmployeeResponse(422, false, "{$key} is required.");
    if (strlen($value) > $maximum) addEmployeeResponse(422, false, "{$key} is too long.");
    return $value === '' ? null : $value;
}

function employeePostArray(string $key, int $maximum): array
{
    $values = $_POST[$key] ?? [];
    if (!is_array($values) || count($values) > 20) addEmployeeResponse(422, false, "{$key} contains too many entries.");
    return array_map(static function ($value) use ($key, $maximum): ?string {
        $clean = trim((string) $value);
        if (strlen($clean) > $maximum) addEmployeeResponse(422, false, "{$key} contains an entry that is too long.");
        return $clean === '' ? null : $clean;
    }, array_values($values));
}

function employeeDate(string $key, bool $required = false): ?string
{
    $raw = trim((string) ($_POST[$key] ?? ''));
    if ($raw === '' && !$required) return null;
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $raw);
    $errors = DateTimeImmutable::getLastErrors();
    if (!$date || ($errors !== false && ($errors['warning_count'] || $errors['error_count']))) {
        addEmployeeResponse(422, false, "{$key} must be a valid date.");
    }
    return $date->format('Y-m-d');
}

function storeEmployeeDocument(?array $file, string $label): ?array
{
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || (int) ($file['size'] ?? 0) > 8 * 1024 * 1024) {
        addEmployeeResponse(422, false, "{$label} must be smaller than 8 MB.");
    }
    if (!is_uploaded_file((string) $file['tmp_name'])) addEmployeeResponse(422, false, "{$label} upload is invalid.");

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file((string) $file['tmp_name']);
    $extensions = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];
    if (!isset($extensions[$mime])) addEmployeeResponse(422, false, "{$label} must be a PDF, JPEG, or PNG file.");

    $directory = dirname(__DIR__) . '/uploads/employee_documents';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        addEmployeeResponse(500, false, 'Employee document storage is unavailable.');
    }
    $filename = strtolower(str_replace(' ', '_', $label)) . '_' . bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
    $diskPath = $directory . '/' . $filename;
    if (!move_uploaded_file((string) $file['tmp_name'], $diskPath)) addEmployeeResponse(500, false, "{$label} could not be saved.");
    return ['relative' => 'uploads/employee_documents/' . $filename, 'disk' => $diskPath];
}

$lastname = employeePost('lastname', 100, true);
$firstname = employeePost('firstname', 100, true);
$middlename = employeePost('middlename', 100);
$suffix = employeePost('suffix', 10);
$gender = employeePost('gender', 10, true);
if (!in_array($gender, ['Male', 'Female', 'Other'], true)) addEmployeeResponse(422, false, 'Select a valid gender.');
$maritalStatus = employeePost('maritalstatus', 20) ?? 'Single';
if (!in_array($maritalStatus, ['Single', 'Married', 'Divorced', 'Widowed'], true)) addEmployeeResponse(422, false, 'Select a valid marital status.');
$birthDate = employeeDate('dateofbirth', true);
$birth = new DateTimeImmutable($birthDate);
if ($birth > new DateTimeImmutable('today')) addEmployeeResponse(422, false, 'Date of birth cannot be in the future.');
$age = $birth->diff(new DateTimeImmutable('today'))->y;

$children = filter_var($_POST['no_of_children'] ?? 0, FILTER_VALIDATE_INT);
$height = filter_var($_POST['height'] ?? 0, FILTER_VALIDATE_INT);
$weight = filter_var($_POST['weight'] ?? 0, FILTER_VALIDATE_INT);
if ($children === false || $children < 0 || $children > 30 || $height === false || $height < 0 || $height > 300 || $weight === false || $weight < 0 || $weight > 500) {
    addEmployeeResponse(422, false, 'Enter valid household, height, and weight values.');
}

$email1 = employeePost('email1', 100);
$email2 = employeePost('email2', 100);
foreach ([$email1, $email2] as $email) {
    if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) addEmployeeResponse(422, false, 'Enter valid email addresses.');
}

$employeeValues = [
    $lastname, $firstname, $middlename, $suffix, $gender,
    employeePost('othernamesused', 2000), employeePost('homeaddress', 2000), $maritalStatus,
    employeePost('nameofspouse', 100), $children, $height, $weight,
    employeePost('eyecolor', 50), employeePost('haircolor', 50), employeePost('distinguishingfeatures', 2000),
    employeePost('sss_gsis', 50), $birthDate, $age, employeePost('placeofbirth', 100),
    employeePost('citizenship', 50), employeePost('provinceoforigin', 100), employeePost('religion', 50),
    employeePost('bloodtype', 3), employeePost('homephone', 20), employeePost('businessphone', 20),
    employeePost('mobilephone', 20), $email1, $email2, employeePost('faxno', 20),
    employeePost('workoccupation', 100), employeePost('passportno', 50), employeeDate('expirydate'),
    employeePost('typeofvisa', 50), employeePost('tinno', 50), employeeDate('datejoiningindsclc'),
    employeePost('fbvibername', 100), employeePost('instagram', 100), employeePost('kidsnames', 2000),
];

$schools = employeePostArray('schoolname', 255);
$degrees = employeePostArray('degreeobtained', 255);
$educationDates = employeePostArray('inclusivedates', 50);
$educationYears = employeePostArray('yearobtained', 4);
$companies = employeePostArray('company', 255);
$businessTypes = employeePostArray('natureofbusiness', 255);
$designations = employeePostArray('designation', 255);
$workDates = employeePostArray('work_inclusive_dates', 100);
$licenses = employeePostArray('professional_licenses', 255);
$trainings = employeePostArray('special_trainings', 255);
$skills = employeePostArray('special_interests_skills', 255);
$organizations = employeePostArray('organization', 255);
$places = employeePostArray('place', 255);
$membershipDates = employeePostArray('date_of_membership', 255);
$positionsHeld = employeePostArray('position_held', 255);
$rowCount = max(1, count($schools), count($companies), count($organizations), count($licenses));
$positionPeriodAssumed = employeePost('position_period_assumed', 255);
$natureOfOffice = employeePost('nature_of_office', 255);
$emergencyContact = [
    employeePost('last_name', 255), employeePost('first_name', 255), employeePost('middle_initial', 10),
    employeePost('emergency_suffix', 50), employeePost('relationship', 255), employeePost('address', 2000),
    employeePost('tel_home', 50), employeePost('tel_business', 50), employeePost('mobile_phone', 50),
];
foreach ($educationYears as $yearValue) {
    if ($yearValue !== null && filter_var($yearValue, FILTER_VALIDATE_INT) === false) {
        addEmployeeResponse(422, false, 'Education year must be numeric.');
    }
}

$documents = [
    storeEmployeeDocument($_FILES['nbi_clearance'] ?? null, 'NBI clearance'),
    storeEmployeeDocument($_FILES['police_clearance'] ?? null, 'Police clearance'),
    storeEmployeeDocument($_FILES['barangay_clearance'] ?? null, 'Barangay clearance'),
    storeEmployeeDocument($_FILES['orientation_certificate'] ?? null, 'Orientation certificate'),
];
$documentPaths = array_map(static fn($document) => $document['relative'] ?? null, $documents);

$conn->begin_transaction();
try {
    $lock = $conn->query("SELECT GET_LOCK('hshr_employee_sequence', 5) AS acquired");
    if ((int) ($lock->fetch_assoc()['acquired'] ?? 0) !== 1) throw new RuntimeException('Employee sequence is busy.');
    $year = date('Y');
    $prefix = 'HS-EID' . $year;
    $last = $conn->prepare('SELECT id FROM employees WHERE id LIKE CONCAT(?, \'%\') ORDER BY id DESC LIMIT 1 FOR UPDATE');
    $last->bind_param('s', $prefix);
    $last->execute();
    $lastId = $last->get_result()->fetch_assoc()['id'] ?? null;
    $last->close();
    $sequence = $lastId ? ((int) substr((string) $lastId, -7) + 1) : 1;
    $employeeId = $prefix . str_pad((string) $sequence, 7, '0', STR_PAD_LEFT);

    $employeeStatement = $conn->prepare(
        'INSERT INTO employees
         (id, lastname, firstname, middlename, suffix, gender, othernamesused, homeaddress, maritalstatus,
          nameofspouse, no_of_children, `height(cm)`, `weight(kg)`, colorofeyes, colorofhair,
          scars_marks_distinguishingfeatures, sss_gsisno, dateofbirth, age, placeofbirth, citizenship,
          provinceoforigin, religion, bloodtype, tel_no_home, businessphonenumber, mobilephone, email1,
          email2, faxno, work_occupation, passportno, expirydate, typeofvisaissued, tinno, datejoiningindsclc,
          fb_messenger_vibername, instagramname, kids_children_names, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'Active\')'
    );
    $employeeStatement->execute(array_merge([$employeeId], $employeeValues));
    $employeeStatement->close();

    $secondHalf = $conn->prepare(
        'INSERT INTO ed_2ndhalf
         (employee_id, schoolname, degreeobtained, inclusivedates, yearobtained, company, nature_of_business,
          designation, inclusive_dates, position_period_assumed, nature_of_office, professional_licenses,
          special_trainings, special_interests_skills, last_name, first_name, mi, suffix, relationship, address,
          tel_no_home, tel_no_business, mobile_phone_no, organization, place, date_of_membership, position_held,
          nbi_clearance, police_clearance, barangay_clearance, orientation_membership_seminar_certificate)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    for ($index = 0; $index < $rowCount; $index++) {
        $yearObtained = isset($educationYears[$index]) && $educationYears[$index] !== null
            ? filter_var($educationYears[$index], FILTER_VALIDATE_INT)
            : null;
        $secondHalf->execute([
            $employeeId, $schools[$index] ?? null, $degrees[$index] ?? null, $educationDates[$index] ?? null,
            $yearObtained, $companies[$index] ?? null, $businessTypes[$index] ?? null, $designations[$index] ?? null,
            $workDates[$index] ?? null, $index === 0 ? $positionPeriodAssumed : null,
            $index === 0 ? $natureOfOffice : null, $licenses[$index] ?? null,
            $trainings[$index] ?? null, $skills[$index] ?? null, $index === 0 ? $emergencyContact[0] : null,
            $index === 0 ? $emergencyContact[1] : null, $index === 0 ? $emergencyContact[2] : null,
            $index === 0 ? $emergencyContact[3] : null, $index === 0 ? $emergencyContact[4] : null,
            $index === 0 ? $emergencyContact[5] : null, $index === 0 ? $emergencyContact[6] : null,
            $index === 0 ? $emergencyContact[7] : null, $index === 0 ? $emergencyContact[8] : null,
            $organizations[$index] ?? null, $places[$index] ?? null, $membershipDates[$index] ?? null,
            $positionsHeld[$index] ?? null, $index === 0 ? $documentPaths[0] : null,
            $index === 0 ? $documentPaths[1] : null, $index === 0 ? $documentPaths[2] : null,
            $index === 0 ? $documentPaths[3] : null,
        ]);
    }
    $secondHalf->close();
    $conn->commit();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_employee_sequence')"); } catch (Throwable $ignored) {}
    addEmployeeResponse(201, true, 'Employee created successfully.', ['employee_id' => $employeeId]);
} catch (Throwable $error) {
    $conn->rollback();
    try { $conn->query("SELECT RELEASE_LOCK('hshr_employee_sequence')"); } catch (Throwable $ignored) {}
    foreach ($documents as $document) {
        if (is_array($document) && is_file($document['disk'])) @unlink($document['disk']);
    }
    error_log('Employee creation failed: ' . $error->getMessage());
    addEmployeeResponse(500, false, 'Employee could not be created. Check the submitted values and try again.');
}
