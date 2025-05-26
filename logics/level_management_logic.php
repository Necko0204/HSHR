<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $levelName = $_POST['levelName'];
    $levelStatus = $_POST['levelStatus'];

    // Get the current year
    $currentYear = date('Y');

    // Generate the new level_id
    $sql = "SELECT level_id FROM levels WHERE level_id LIKE 'HSHI-LVL{$currentYear}%' ORDER BY level_id DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['level_id'];
        $number = (int)substr($lastId, -7) + 1;
        $newId = 'HSHI-LVL' . $currentYear . str_pad($number, 7, '0', STR_PAD_LEFT);
    } else {
        $newId = 'HSHI-LVL' . $currentYear . '0000001';
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO levels (level_id, level_name, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $newId, $levelName, $levelStatus);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Level added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding level"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>