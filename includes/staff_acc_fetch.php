<?php
require 'db_config.php';

$query = "SELECT * FROM staff_accounts";
$result = $conn->query($query);

$output = "";
while ($row = $result->fetch_assoc()) {
    $profilePicture = !empty($row['profile_picture']) ? 
        "<img src='".htmlspecialchars($row['profile_picture'])."' alt='Profile' class='img-thumbnail' width='100' height='100' style='object-fit: cover;'>" : 
        "<span>No Image</span>";

    $statusBadge = $row['status'] == 'Active' ? 
        '<span class="badge bg-success">Active</span>' : 
        '<span class="badge bg-danger">Inactive</span>';
    
    $toggleBtn = $row['status'] == 'Active' ? 
        '<button class="btn btn-danger btn-sm toggle-status-btn" data-id="'.$row['id'].'" data-status="Deactivate"><i class="fas fa-times"></i> Deactivate</button>' : 
        '<button class="btn btn-success btn-sm toggle-status-btn" data-id="'.$row['id'].'" data-status="Reactivate"><i class="fas fa-check"></i> Reactivate</button>';

    $output .= "<tr>
        <td style='display:none;'>{$row['id']}</td>
        <td>$profilePicture</td>
        <td>{$row['employee_id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['role']}</td>
        <td>$statusBadge</td>
        <td>
            <button class='btn btn-warning btn-sm edit-btn' 
                data-id='{$row['id']}' 
                data-employee_id='{$row['employee_id']}' 
                data-username='{$row['username']}' 
                data-role='{$row['role']}'
                data-status='{$row['status']}'>
                <i class='fas fa-edit'></i> Edit
            </button>
            $toggleBtn
        </td>
    </tr>";
}

echo $output;
?>
