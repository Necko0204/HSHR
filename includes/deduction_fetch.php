<?php
require_once 'db_config.php'; // Adjust based on your structure

// Fetch deductions sorted by created_at descending
$sql = "SELECT id, name, description, deduction_type, amount, percentage, max_cap, is_mandatory, created_at FROM deductions ORDER BY created_at DESC";
$result = $conn->query($sql);

$counter = 1; // Start counting for display

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()):
        // Generate formatted ID (HSHI-DEDYYYYNNNNNN)
        $year = date("Y", strtotime($row["created_at"]));
        $formatted_id = "HSHI-DED{$year}" . str_pad($counter, 6, "0", STR_PAD_LEFT);
        ?>
        <tr>
            <td class="hidden-id" style="display: none;"><?= htmlspecialchars($formatted_id) ?></td> <!-- Hidden ID -->
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["description"]) ?></td>
            <td><?= htmlspecialchars(ucfirst($row["deduction_type"])) ?></td>
            <td><?= $row["deduction_type"] == "fixed" ? number_format($row["amount"], 2) : '-' ?></td>
            <td><?= $row["deduction_type"] == "percentage" ? $row["percentage"] . "%" : '-' ?></td>
            <td><?= $row["max_cap"] ? number_format($row["max_cap"], 2) : 'N/A' ?></td>
            <td><?= $row["is_mandatory"] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' ?></td>
        </tr>
        <?php 
        $counter++; // Increment the counter for the next ID
    endwhile;
} else {
    echo '<tr><td colspan="8" class="text-center">No deductions found.</td></tr>';
}

$conn->close();
?>
