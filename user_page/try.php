<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Form</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Your custom styles -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>

<!-- Your existing div structure -->
<div id="myForm">
    <input type="hidden" name="subject" value="YourSubjectValue">
    <input type="hidden" name="purpose" value="YourPurposeValue">
    <input type="hidden" name="datepicker" value="YourDatepickerValue">
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Your custom script -->
<script>
    $(document).ready(function () {
        // Get the values of hidden inputs
        var subject = document.querySelector('#myForm [name="subject"]').value;
        var purpose = document.querySelector('#myForm [name="purpose"]').value;
        var datepicker = document.querySelector('#myForm [name="datepicker"]').value;

        // Display the values (you can use these values as needed)
        console.log('Subject:', subject);
        console.log('Purpose:', purpose);
        console.log('Datepicker:', datepicker);

        // Call sendFormData when the document is ready
        sendFormData(subject, purpose, datepicker);
    });

    function sendFormData(subject, purpose, datepicker) {
        // Create a FormData object
        var formData = new FormData();

        // Append the values to the FormData object
        formData.append("subject", subject);
        formData.append("purpose", purpose);
        formData.append("datepicker", datepicker);

        // Create XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Configure it to send a POST request to "test.php"
        xhr.open("POST", "test.php", true);

        // Set up the event listener to handle the response
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Response:", xhr.responseText);
                // Handle the response as needed
            }
        };

        // Send the FormData
        xhr.send(formData);
    }
</script>

</body>
</html>
