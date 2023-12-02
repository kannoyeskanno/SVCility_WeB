<?php
include '../dbConnect.php';

$sql = "SELECT scheduled_date as date, facilityName as title FROM facility_schedules";
$result = $conn->query($sql);

$events = array();

while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>
