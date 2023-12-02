<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home |</title>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../resources/logo/svcility_icon.png" type="image/x-icon">
    <style>
      body {
        border: solid;
        background-color: red;
      }
    </style>
</head>
<body>
    <?php
    session_start();
    include '../header.php';
    ?>

    <div class="container-sm my-5">
        <div class="box">
            <div id="form-container" class="container-sm p-5 border">
                <form id="form" action="test2.php" method="post" enctype="multipart/form-data">
                    <h2 class="mb-4">Page 1: Select Date</h2>
                    <div class="mb-3">
                        <label for="datepicker" class="form-label">Select Date:</label>
                        <input type="date" class="form-control" id="datepicker" name="selectedDate" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject:</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose:</label>
                        <select class="form-control" id="purpose" name="purpose">
                            <option value="Community Events">Community Events</option>
                            <option value="Private Functions">Private Functions</option>
                            <option value="Sports Practices">Sports Practices</option>
                            <option value="Health and Wellness Programs">Health and Wellness Programs</option>
                            <option value="Religious Services">Religious Services</option>
                            <option value="Social Gatherings">Social Gatherings</option>
                            <option value="Business Meetings">Business Meetings</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-outline-primary">Next</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal" id="myModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ADVISORY</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>

        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p> 
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p> 
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>
        <p>Modal body text goes here.</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show the modal on page load
        $(document).ready(function () {
            $('#myModal').modal('show');
        });
        
    </script>
</body>
</html>
