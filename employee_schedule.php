<?php
session_name('admin_session');
session_start();
include 'includes/breadcrumb.php';
include 'db_config.php';
include 'helper.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
        <title>Holy Spirit Human Resource</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="background.css">
        <style>
            .thin-column {
    width: 10%; /* Adjust as needed */
}
</style>
    </head>
    <body>
<div class="main-container">
    <?php include 'sidebar.php'; ?>
</div>
<div class="content-container">
    <?php include 'nav_header.php'; ?>
</div>

<main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
<div class="d-flex justify-content-start align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-calendar-event"></i> Employee Schedule
    </h2>
</div>


    <!-- Employee Table -->
    <div class="card shadow-lg border-1 rounded-3">
            <div class="card-header bg-gradient-primary text-black d-flex justify-content-between align-items-center">
                <!-- Search Bar -->
                <div class="d-flex align-items-center">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 150px;">
                </div>


                <!-- Archives Button -->
                <button type="button" class="btn btn-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#ArchivesModal">
                    <i class="fa fa-folder"></i> <span>Archives</span>
                </button>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <?php
                $sql = "SELECT id, lastname, firstname, gender, email1, employment_type, status 
                FROM employees 
                WHERE status = 'Active'";
        
                    $result = $conn->query($sql);
                    ?>
        <div style="max-height: 530px; overflow-y: auto;">
                        <table class="table table-borderless table-hover align-middle">   
                        <thead class="table-light">
                        <tr>
                                <th class="d-none">ID</th>
                                <th style="min-width: 150px; width: 150px;">Last Name</th>
                                <th style="min-width: 150px; width: 150px;">First Name</th>
                                <th style="min-width: 150px; width: 150px;">Status</th>
                                <th style="min-width: 150px; width: 150px;">Employment Type</th>
                                <th style="min-width: 150px; width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTable">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="d-none"><?= htmlspecialchars($row["id"]) ?></td> <!-- Added missing ID column -->
                                    <td style="width: 150px; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($row["lastname"]) ?></td>
                                    <td style="width: 150px;"><?= htmlspecialchars($row["firstname"]) ?></td>
                                    <td style="width: 150px;"><span class="badge bg-success">Active</span></td>
                                    <td style="width: 150px;">
                                        <span class="badge <?= $row["employment_type"] == 'full_time' ? 'bg-primary' : 'bg-warning' ?>">
                                            <?= $row["employment_type"] == 'full_time' ? 'Full Time' : 'Part Time' ?>
                                        </span>
                                    </td>
                                    <td style="width: 150px;">
                                        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                            data-empid="<?= $row['id'] ?>" data-empname="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>">
                                            <i class="bi bi-calendar-plus"></i> Create Schedule
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </main>
    <!-- Fixed Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Create Schedule</h5>
                    <span class="badge bg-info ms-3" id="scheduleMonthIndicator">
                        <?php
                            // Show the current month and year as an indicator
                            echo date('F Y');
                        ?>
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleForm">
                        <input type="hidden" name="employee_id" id="modalEmployeeId">
                        <h5 id="modalEmployeeName"></h5>
                        <div style="max-height: 680px; overflow-y: auto;"></div>
                            <table class="table table-borderless table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="thin-column">Start Time</th>
                                        <th class="thin-column">End Time</th>
                                        <th>Monday <br><input type="checkbox" class="select-all" data-day="1"></th>
                                        <th>Tuesday <br><input type="checkbox" class="select-all" data-day="2"></th>
                                        <th>Wednesday <br><input type="checkbox" class="select-all" data-day="3"></th>
                                        <th>Thursday <br><input type="checkbox" class="select-all" data-day="4"></th>
                                        <th>Friday <br><input type="checkbox" class="select-all" data-day="5"></th>
                                    </tr>
                                </thead>
                            <tbody id="scheduleTableBody"></tbody>
                        </table>
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save Schedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  <!-- Archives Modal -->
  <div class="modal fade" id="ArchivesModal" tabindex="-1" aria-labelledby="ArchivesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ArchivesModalLabel">Archives</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="background.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
scheduleForm.addEventListener('submit', function (e) {
  e.preventDefault();

  const employeeId = document.getElementById('modalEmployeeId').value;
  const hours = {};

  document.querySelectorAll('#scheduleTableBody tr').forEach(row => {
    const sh = parseInt(row.querySelector('.start-hour').value, 10);
    const sm = parseInt(row.querySelector('.start-minute').value, 10);
    const eh = parseInt(row.querySelector('.end-hour').value, 10);
    const em = parseInt(row.querySelector('.end-minute').value, 10);
    const duration = (eh + em/60) - (sh + sm/60);

    row.querySelectorAll('input[type="checkbox"]').forEach((cb, idx) => {
      if (cb.checked) {
        hours[idx+1] = (hours[idx+1] || 0) + duration;
      }
    });
  });

  const payload = { employee_id: employeeId, hours };

  fetch('save_schedule_logic.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(res => {
    console.log('[Fetch] HTTP status:', res.status, res.statusText);
    return res.text();            // grab the raw text first
  })
  .then(text => {
    console.log('[Fetch] Raw response text:', text);
    let data;
    try {
      data = JSON.parse(text);     // then parse JSON
      console.log('[Fetch] Parsed JSON:', data);
    } catch (err) {
      console.error('[Fetch] Failed to parse JSON:', err);
      toastr.error('Server returned invalid JSON. See console.');
      throw err;                   // jump to catch below
    }

    if (data.success) {
      toastr.success(data.message || "Schedule saved successfully.");
      const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleModal'));
      modal.hide();
    } else {
      console.error('[Server] Error payload:', data);
      toastr.error(data.message || "Failed to save schedule.");
    }
  })
  .catch(error => {
    console.error('[Fetch] Unexpected error:', error);
    toastr.error("An unexpected error occurred. See console for details.");
  });
});
</script>

<script>
$(document).ready(function() {
    $('#scheduleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var empId = button.data('empid');
        var empName = button.data('empname');
        $('#modalEmployeeId').val(empId);
        
    });
});
</script>

<script>

document.addEventListener("DOMContentLoaded", function () {
    const scheduleTableBody = document.getElementById("scheduleTableBody");
    let rows = "";
    const disabledRanges = []; // Stores ranges [{start, end}]

    // Generate table rows for hours 7 AM - 4 PM
    for (let hour = 7; hour <= 16; hour++) {
        rows += `<tr data-hour="${hour}">
            <td>
                <div class="d-flex align-items-center" style="width: 150px;">
                    <select name="start_hour[${hour}]" class="form-select w-auto me-1 start-hour" data-hour="${hour}">
                        ${generateHourOptions(hour)}
                    </select> :
                    <select name="start_minutes[${hour}]" class="form-select w-auto start-minute" data-hour="${hour}">
                        ${generateMinuteOptions()}
                    </select>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center" style="width: 150px;">
                    <select name="end_hour[${hour}]" class="form-select w-auto me-1 end-hour" data-hour="${hour}">
                        ${generateHourOptions(hour + 1)}
                    </select> :
                    <select name="end_minutes[${hour}]" class="form-select w-auto end-minute" data-hour="${hour}">
                        ${generateMinuteOptions()}
                    </select>
                </div>
            </td>`;
        for (let day = 1; day <= 5; day++) {
            rows += `<td><input type="checkbox" class="form-check-input" name="schedule[${hour}][${day}]" data-day="${day}"></td>`;
        }
        rows += "</tr>";
    }

    scheduleTableBody.innerHTML = rows;

    /** ========================== EVENT LISTENERS ========================== **/

    document.querySelectorAll(".end-hour, .end-minute").forEach(select => {
    select.addEventListener("change", function () {
        let rowHour = parseInt(this.dataset.hour);

        // Grab all relevant selects in this row
        let startHourSelect  = document.querySelector(`.start-hour[data-hour='${rowHour}']`);
        let startMinuteSelect= document.querySelector(`.start-minute[data-hour='${rowHour}']`);
        let endHourSelect    = document.querySelector(`.end-hour[data-hour='${rowHour}']`);
        let endMinuteSelect  = document.querySelector(`.end-minute[data-hour='${rowHour}']`);

        if (!startHourSelect || !startMinuteSelect || !endHourSelect || !endMinuteSelect) return;

        // Convert the selected times to numbers
        let startHour   = parseInt(startHourSelect.value);
        let startMinute = parseInt(startMinuteSelect.value);
        let endHour     = parseInt(endHourSelect.value);
        let endMinute   = parseInt(endMinuteSelect.value);

        // Helper to get total minutes from midnight for quick comparison
        function toTotalMinutes(h, m) {
            return h * 60 + m;
        }

        let startTotal = toTotalMinutes(startHour, startMinute);
        let endTotal   = toTotalMinutes(endHour, endMinute);

        // === (A) If startMinute = 45, disable the same hour in the End-Hour dropdown ===
        //     For example, if user picked 9:45, disable hour 9 in the end-hour select.
        //     Re-enable all first (so we don't keep old disables forever).
        endHourSelect.querySelectorAll("option").forEach(opt => {
            opt.disabled = false;
        });

        if (startMinute === 45) {
            const sameHourOption = endHourSelect.querySelector(`option[value='${startHour}']`);
            if (sameHourOption) {
                sameHourOption.disabled = true;
            }
            // If the user already selected that same hour, bump it to the next hour
            if (endHour === startHour) {
                endHourSelect.value = (startHour + 1).toString();
                endHour   = parseInt(endHourSelect.value);
                endTotal  = toTotalMinutes(endHour, endMinute);
            }
        }

        // === (B) Basic validation: end time must be strictly after start time ===
        if (endTotal <= startTotal) {
            showToast("Invalid time selection: End time must be strictly after the start time.");
            revertSelection(rowHour); 
            return;
        }

        // === (C) Disable rows and update the next row’s start minutes (existing logic) ===
        disableRowsBetween(rowHour, endHour);
        disableStartMinutes(rowHour, endMinute);

        // === (D) If startHour === endHour, ensure endMinute > startMinute (existing logic) ===
        if (startHour === endHour) {
            Array.from(endMinuteSelect.options).forEach(option => {
                let optMinute = parseInt(option.value);
                option.disabled = (optMinute <= startMinute);
            });
            // If the current selection is invalid, pick the first valid
            if (parseInt(endMinuteSelect.value) <= startMinute) {
                let firstValid = Array.from(endMinuteSelect.options).find(opt => !opt.disabled);
                if (firstValid) {
                    endMinuteSelect.value = firstValid.value;
                }
            }
        }
    });
});


    document.querySelectorAll(".start-hour").forEach(select => {
        select.addEventListener("change", function () {
            let rowHour = parseInt(this.dataset.hour);
            let startHour = parseInt(this.value);
            let endHourSelect = document.querySelector(`.end-hour[data-hour='${rowHour}']`);

            if (endHourSelect) {
                endHourSelect.querySelectorAll("option").forEach(option => {
                    let hourValue = parseInt(option.value);
                    option.disabled = hourValue < startHour;
                });

                if (parseInt(endHourSelect.value) < startHour) {
                    let firstAvailable = Array.from(endHourSelect.options).find(opt => !opt.disabled);
                    if (firstAvailable) {
                        endHourSelect.value = firstAvailable.value;
                    }
                }
            }
        });
    });

    document.querySelectorAll(".start-minute").forEach(select => {
        select.addEventListener("change", function () {
            let rowHour = parseInt(this.dataset.hour);
            let startHour = parseInt(document.querySelector(`.start-hour[data-hour='${rowHour}']`).value);
            let startMinute = parseInt(this.value);
            let endMinuteSelect = document.querySelector(`.end-minute[data-hour='${rowHour}']`);
            let endHourSelect = document.querySelector(`.end-hour[data-hour='${rowHour}']`);

            if (endMinuteSelect) {
                endMinuteSelect.querySelectorAll("option").forEach(option => {
                    let minuteValue = parseInt(option.value);
                    option.disabled = (parseInt(endHourSelect.value) === startHour) && (minuteValue < startMinute);
                });

                if (parseInt(endMinuteSelect.value) < startMinute && parseInt(endHourSelect.value) === startHour) {
                    let firstAvailable = Array.from(endMinuteSelect.options).find(opt => !opt.disabled);
                    if (firstAvailable) {
                        endMinuteSelect.value = firstAvailable.value;
                    }
                }
            }
        });
    });

    /** ========================== FUNCTION DEFINITIONS ========================== **/
    function disableRowsBetween(startRow, endHour, reset = false) {
    console.log("disableRowsBetween called with:", { startRow, endHour, reset });

    // Remove any existing range for this row
    for (let i = disabledRanges.length - 1; i >= 0; i--) {
        if (disabledRanges[i].start === startRow) {
            disabledRanges.splice(i, 1);
        }
    }

    if (!reset) {
        // Add new range
        disabledRanges.push({ start: startRow, end: endHour });
    }

    updateDisabledRows();
}

function revertSelection(revertedHour) {
    console.log(`🔄 Reverting selection for hour ${revertedHour}`);
    
    // Remove all ranges for the given row by modifying the array in place
    for (let i = disabledRanges.length - 1; i >= 0; i--) {
        if (disabledRanges[i].start === revertedHour) {
            disabledRanges.splice(i, 1);
        }
    }
    
    updateDisabledRows();
}

function updateDisabledRows() {
    console.log("🔄 updateDisabledRows called");

    // First, disable rows that are within a selected range
    document.querySelectorAll("tr").forEach(row => {
        let rowHour = parseInt(row.dataset.hour);
        if (isNaN(rowHour)) return;

        // Disable rows that fall strictly between any disabled range (not including the boundary row)
        let isDisabled = disabledRanges.some(range => rowHour > range.start && rowHour < range.end);

        if (isDisabled) {
            row.querySelectorAll("select, input").forEach(el => el.disabled = true);
        } else {
            console.log(`✅ Re-enabling row ${rowHour}`);
            row.querySelectorAll("select, input").forEach(el => el.removeAttribute("disabled"));
        }
    });

    // Then, for each disabled range, adjust the boundary row (row with data-hour equal to range.end)
    disabledRanges.forEach(range => {
        let boundaryRow = document.querySelector(`tr[data-hour='${range.end}']`);
        if (boundaryRow) {
            // Adjust the start-hour dropdown
            let startHourSelect = boundaryRow.querySelector(".start-hour");
            if (startHourSelect) {
                Array.from(startHourSelect.options).forEach(option => {
                    let optionHour = parseInt(option.value);
                    if (optionHour < range.end) {
                        option.disabled = true;
                    }
                });
                // Reset the selection if it falls in the disabled part
                if (parseInt(startHourSelect.value) < range.end) {
                    let firstAvailable = Array.from(startHourSelect.options).find(opt => !opt.disabled);
                    if (firstAvailable) {
                        startHourSelect.value = firstAvailable.value;
                    }
                }
            }
            // Adjust the end-hour dropdown in the same way
            let endHourSelect = boundaryRow.querySelector(".end-hour");
            if (endHourSelect) {
                Array.from(endHourSelect.options).forEach(option => {
                    let optionHour = parseInt(option.value);
                    if (optionHour < range.end) {
                        option.disabled = true;
                    }
                });
                // Reset the selection if it is less than the allowed value
                if (parseInt(endHourSelect.value) < range.end) {
                    let firstAvailable = Array.from(endHourSelect.options).find(opt => !opt.disabled);
                    if (firstAvailable) {
                        endHourSelect.value = firstAvailable.value;
                    }
                }
            }
        }
    });

    console.log("✅ Finished updating all rows", disabledRanges);
}


    function disableStartMinutes(rowHour, endMinute) {
        let nextRow = document.querySelector(`tr[data-hour='${rowHour + 1}']`);

        while (nextRow && nextRow.querySelector("select").disabled) {
            rowHour++;
            nextRow = document.querySelector(`tr[data-hour='${rowHour + 1}']`);
        }

        if (nextRow) {
            let startHourSelect = nextRow.querySelector(".start-hour");
            let startMinuteSelect = nextRow.querySelector(".start-minute");

            if (startHourSelect) {
                startHourSelect.value = document.querySelector(`.end-hour[data-hour='${rowHour}']`).value;
            }

            if (startMinuteSelect) {
                startMinuteSelect.querySelectorAll("option").forEach(option => {
                    let minuteValue = parseInt(option.value);
                    option.disabled = minuteValue < endMinute;
                });

                let firstAvailable = Array.from(startMinuteSelect.options).find(opt => !opt.disabled);
                if (firstAvailable) {
                    startMinuteSelect.value = firstAvailable.value;
                }
            }
        }
    }

    function generateHourOptions(startHour) {
        let options = "";
        for (let i = 7; i <= 17; i++) {
            options += `<option value="${i}" ${i === startHour ? "selected" : ""}>${formatHour(i)}</option>`;
        }
        return options;
    }

    function generateMinuteOptions() {
        return `<option value="00">00</option><option value="15">15</option><option value="30">30</option><option value="45">45</option>`;
    }

    function formatHour(hour) {
        let period = hour >= 12 ? "PM" : "AM";
        return `${hour > 12 ? hour - 12 : hour} ${period}`;
    }

    function showToast(message) {
        toastr.error(message); // or toastr.warning, toastr.info, etc.
    }

});

</script>
<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#scheduleTable tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>
