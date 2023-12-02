

<?php
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Calendar</title>

    <style>
        body {
            margin: 0;
            overflow: hidden;
        }
        .calendar-container {
            border-radius: 10px;
            
        }

        .iframe-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .calendar-header {
            text-align: center;
            margin-bottom: 20px;
        }
        #calendarParent {
            width: 100%;
        }
        #calendar {
            padding: 90px;

        }
        /* Add any additional styles as needed */
    </style>


</head>

<body>
<?php
        include('../header.php');
        ?>



<div class="container-fluid" style="padding: 0px;">
        <div class="row">
            <div class="col-lg-6 calendar-container" id="calendar">
            <div class="calendar-header">
                    <button id="prev-month" onclick="previousMonth()" class="btn btn-light">&lt;</button>
                    <h2 id="month-year" class="d-inline-block mx-3"></h2>
                    <button id="next-month" onclick="nextMonth()" class="btn btn-light">&gt;</button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Sun</th>
                            <th scope="col">Mon</th>
                            <th scope="col">Tue</th>
                            <th scope="col">Wed</th>
                            <th scope="col">Thu</th>
                            <th scope="col">Fri</th>
                            <th scope="col">Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body"></tbody>
                </table>            
            </div>

            <div class="col-lg-6 iframe-container">
                <iframe id="iframe_content" src="get_activities.php" frameborder="0" class="w-100" height="400"></iframe>
            </div>
        </div>
    </div>

    

        

            
            <script>
                    const calendarBody = document.getElementById("calendar-body");
                    const monthYear = document.getElementById("month-year");
                    const prevMonthButton = document.getElementById("prev-month");
                    const nextMonthButton = document.getElementById("next-month");
                    const iframeContent = document.getElementById("iframe_content");

                    let currentYear, currentMonth, today;

                    function initializeCalendar() {
                        today = new Date();
                        currentMonth = today.getMonth();
                        currentYear = today.getFullYear();

                        generateCalendar(currentYear, currentMonth);
                        showActivities();
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
                                    cell.addEventListener("click", function() {
                                        showActivities(year, month, date);
                                    });
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

                    function showActivities(year = currentYear, month = currentMonth, date = today.getDate()) {
                        const selectedDate = `${year}-${month + 1}-${date}`; // Adjust month since JavaScript months are zero-based
                        iframeContent.src = `get_activities.php?selected_date=${selectedDate}`;
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
                
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <script src="js/search.js"></script>
            
        <!-- </main> -->

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="js/search.js"></script>
    </div>
    </div>
</body>

</html>






