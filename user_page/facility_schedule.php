<?php
include('../dbConnect.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>
        if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
            window.location.href = "../index.php"; // Redirect to your login page
        } else {
            // Redirect or take any other action if the user clicks Cancel
        }
    </script>';
    exit;
}

if (isset($_GET['facilityID'])) {
    $facilityID = $_GET['facilityID'];

    $sql = "SELECT * FROM request WHERE status = 'approved'";
    $result = $conn->query($sql);

    $scheduledDays = array(); // Initialize an array to store scheduled dates

    while ($row = $result->fetch_assoc()) {
        $facility_ids = $row['facility_id'];

        // Explode the comma-separated string into an array
        $idArray = explode(',', $facility_ids);

        // Check if the provided facilityID exists in the array
        if (in_array($facilityID, $idArray)) {
            $scheduledDays[] = $row['date']; // Add scheduled date to the array
        }
    }

    // Output scheduled days for debugging
    
} else {
    echo 'Facility ID not provided.';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    #calendar-container {
        border: solid;
        width: 100%;
        height: 100%;
        padding: 20px;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .calendar-header button {
        background: none;
        border: none;
        cursor: pointer;
    }

    .calendar-header h2 {
        font-family: 'Josefin Sans', sans-serif;
        font-size: 24px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        text-align: center;
        width: 14.28%;
        padding: 10px;
        border: 1px solid #ccc;
    }

    .current-day {
        background-color: lightblue;
    }

    #activity-container {
        border: 1px solid;
        padding: 20px;
        margin-top: 20px;
        height: 100%;
    }

    td {
        text-align: center;
        width: 14.28%;
        padding: 10px;
        border-radius: 3px;
    }

    td:hover {
        background-color: lightgray;
        cursor: pointer;
        border-radius: 50%;
    }

    .scheduled-day {
        background-color: red;
        color: white;
    }
</style>

</head>
<body>
    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prev-month" onclick="previousMonth()">&lt;</button>
            <h2 id="month-year"></h2>
            <button id="next-month" onclick="nextMonth()">&gt;</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Sun</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                </tr>
            </thead>
            <tbody id="calendar-body"></tbody>
        </table>
    </div>

    <script>

        
        const calendarBody = document.getElementById("calendar-body");
        const monthYear = document.getElementById("month-year");
        const iframeContent = document.getElementById("iframe_content");

        let currentYear, currentMonth, today;
        let scheduledDays = <?php echo json_encode($scheduledDays); ?>;

        function initializeCalendar() {
            today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();

            generateCalendar(currentYear, currentMonth);
            showActivities(currentYear, currentMonth, today.getDate());
        }

        function generateCalendar(year, month) {
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0).getDate();
            const firstDayIndex = firstDay.getDay();

            calendarBody.innerHTML = "";
            monthYear.textContent = `${getMonthName(month)} ${year}`;

            for (let i = 0; i < 6; i++) {
                const row = document.createElement("tr");

                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement("td");
                    const date = i * 7 + j - firstDayIndex + 1;

                    if (i === 0 && j < firstDayIndex) {
                        cell.textContent = "";
                    } else if (date <= lastDay && date >= 1) {
                        cell.textContent = date;
                        cell.addEventListener("click", function () {
                            showActivities(year, month, date);
                        });

                        const formattedDate = `${year}-${padZero(month + 1)}-${padZero(date)}`;
                        
                        if (scheduledDays.includes(formattedDate)) {
                            cell.classList.add("scheduled-day");
                        }

                        if (date === today.getDate() && month === currentMonth && year === currentYear) {
                            cell.classList.add("current-day");
                        }
                    } else {
                        cell.textContent = "";
                    }
                    row.appendChild(cell);
                }

                calendarBody.appendChild(row);
            }
        }

        function getMonthName(month) {
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            return months[month];
        }

        function showActivities(year, month, date) {
            const selectedDate = `${year}-${padZero(month + 1)}-${padZero(date)}`;
            iframeContent.src = `get_activities.php?selected_date=${selectedDate}`;
            
            if (scheduledDays.includes(selectedDate)) {
                iframeContent.classList.add("scheduled-day");
            } else {
                iframeContent.classList.remove("scheduled-day");
            }
        }

        function padZero(num) {
            return num.toString().padStart(2, '0');
        }

        function previousMonth() {
            if (currentMonth === 0) {
                currentYear--;
                currentMonth = 11;
            } else {
                currentMonth--;
            }
            generateCalendar(currentYear, currentMonth);
        }

        function nextMonth() {
            if (currentMonth === 11) {
                currentYear++;
                currentMonth = 0;
            } else {
                currentMonth++;
            }
            generateCalendar(currentYear, currentMonth);
        }

        initializeCalendar();
    </script>
</body>
</html>
