<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://source.unsplash.com/1600x900/?office,workspace') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Glassmorphic Card */
        .glass-card {
            background: rgba(128, 0, 0, 0.3);
            border-radius: 15px;
            padding: 30px;
            width: 450px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        h2 {
            text-align: center;
            color: white;
        }

        /* Form Inputs */
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid maroon;
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
            box-shadow: 0 0 5px maroon;
        }

        /* Button Styling */
        .btn-primary {
            width: 100%;
            background: maroon;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: darkred;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .glass-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <!-- Glassmorphic Card -->
    <div class="glass-card">
        <h2>Leave Request Form</h2>
        <form action="submit_leave.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" class="form-control" name="employee_name" required placeholder="Enter your name">
            </div>
            <div class="mb-3">
                <label class="form-label">Leave Type</label>
                <select class="form-control" name="leave_type_id" required>
                    <option value="">Select Leave Type</option>
                    <?php
                    // Database connection
                    $conn = new mysqli("localhost", "root", "", "humanresource");
                    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                    // Fetch leave types
                    $result = $conn->query("SELECT * FROM leave_types");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['leave_type_id']}'>{$row['leave_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Leave Dates</label>
                <input type="text" class="form-control" id="leave_dates" name="leave_dates" placeholder="Select leave range" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Total Days</label>
                <input type="number" class="form-control" name="total_days" id="total_days" readonly placeholder="Automatically Calculated">
            </div>
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Flatpickr Date Range Picker
        flatpickr("#leave_dates", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates) {
                if (selectedDates.length === 2) {
                    let start = selectedDates[0];
                    let end = selectedDates[1];
                    let daysDiff = (end - start) / (1000 * 60 * 60 * 24) + 1;
                    document.getElementById("total_days").value = daysDiff;
                } else {
                    document.getElementById("total_days").value = "";
                }
            }
        });
    </script>

</body>
</html>
