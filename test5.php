
<?php
session_start();
include('../dbConnect.php');

if (
    isset($_SESSION['date']) &&
    isset($_SESSION['subject']) &&
    isset($_SESSION['purpose']) &&
    isset($_GET['selectedFacilities']) &&
    isset($_GET['selectedEquipment'])
) {    
    $date = $_SESSION['date'];
    $subject = $_SESSION['subject'];
    $purpose = $_SESSION['purpose'];

    echo $subject;
    echo $purpose;
    $selectedDate = $_SESSION['date'];
    $selectedFacilityIDs = explode(',', $_GET['selectedFacilities']); 

    if (isset($_GET['selectedEquipment']) && !is_null($_GET['selectedEquipment'])) {
        $selectedEquipmentData = json_decode($_GET['selectedEquipment']);        
        if (is_array($selectedEquipmentData)) {
            $equipmentIDQuantityPairs = [];

            foreach ($selectedEquipmentData as $equipment) {
                $equipmentID = intval($equipment->id); 
                $quantity = intval($equipment->quantity); 

                $equipmentIDQuantityPairs[] = "$equipmentID:$quantity";
            }

            $selectedEquipmentString = implode(',', $equipmentIDQuantityPairs);

            $sqlSelectedFacilities = "SELECT * FROM facility WHERE id IN (" . implode(',', $selectedFacilityIDs) . ")";
            $resultSelectedFacilities = $conn->query($sqlSelectedFacilities);

            $selectedFacilityIDsString = implode(',', $selectedFacilityIDs);

            echo $selectedFacilityIDsString;

            echo 'equipment', $selectedEquipmentString;
        }
    }
} 

// else {
//     header("Location: test1.php");
//     exit();
// }

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Document</title>
</head>
<body>

<form id="form2" action="show_result.php" method="post" enctype="multipart/form-data">

    <input type="hidden" name="facilityId" value="<?php echo $selectedFacilityIDsString; ?>">
    <input type="hidden" name="equipmentIds" value="<?php echo $selectedEquipmentString; ?>">
    <input type="hidden" name="subject" value="<?php echo $subject; ?>">
    <input type="hidden" name="purpose" value="<?php echo $purpose; ?>">
    <input type="hidden" name="date" value="<?php echo $date; ?>">

    <label for="pdf_file" class="file-label">Select a PDF file:</label>
    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="file-input" required>

    <button id="prevButton2" class="btn">Previous</button>
    <button type="submit" class="btn" name="submitForm">Submit</button>
</form>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
