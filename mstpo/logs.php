<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment History</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add DataTables CSS link -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <!-- Add DataTables JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        .container {
            border: solid;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .facility-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            margin-bottom: 20px;
        }

        .log-entry {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .log-entry table {
            width: 100%;
        }

        .log-entry th,
        .log-entry td {
            border: 1px solid #ddd;
        }

        .log-entry th {
            background-color: #f2f2f2;
            text-align: center;
            cursor: pointer; /* Add cursor pointer for sorting */
        }

        .log-entry td:first-child {
            font-weight: bold;
        }

        .log-entry td:last-child {}

        /* Additional styling for DataTables */
        #logTable_wrapper {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">

        <?php
        session_start();
        include('../dbConnect.php');

        if (isset($_GET['equipmentId'])) {
            $equipmentId = $_GET['equipmentId'];

            $sql = "SELECT * FROM equipment_logs WHERE equipment_id = $equipmentId";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Error: " . $conn->error;
            } else {
                echo '<div class="log-entry">';
                echo '<table class="table" id="logTable">';
                echo '<thead class="thead-light">';
                echo '<tr>';
                echo '<th class="sortable">Office/Org</th>';
                echo '<th class="sortable">Name</th>';
                echo '<th class="sortable">Handed</th>';
                echo '<th class="sortable">Returned</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $equipmentId = $row["id"];
                        $handed = $row["handed"];
                        $returned = $row["returned"];
                        $reservedBy = $row["user_id"];

                        $sqlClient = "SELECT * FROM client WHERE id = ?";
                        $stmtClient = $conn->prepare($sqlClient);

                        if ($stmtClient) {
                            $stmtClient->bind_param("i", $reservedBy);
                            $stmtClient->execute();
                            $resultClient = $stmtClient->get_result();

                            if ($resultClient->num_rows > 0) {
                                while ($rowClient = $resultClient->fetch_assoc()) {
                                    $userid = $rowClient['id'];
                                    $fname = $rowClient['fname'];
                                    $lname = $rowClient['lname'];
                                    $office_org = $rowClient['office_org'];
                                }
                            }
                        }

                        echo '<tr>';
                        echo '<td>' . $office_org . '</td>';
                        echo '<td>' . $fname . ' ' . $lname . '</td>';
                        echo '<td>' . $handed . '</td>';
                        echo '<td>' . ($returned ? $returned : 'Pending') . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found in the equipment_logs table.</td></tr>";
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
        }

        $conn->close();
        ?>

        <script>
            $(document).ready(function () {
                $('#logTable').DataTable();
            });
        </script>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
