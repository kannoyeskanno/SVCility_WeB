<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form - Page 1</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <form action="test2.php" method="post" enctype="multipart/form-data">
            <h2>Page 1: Select Date</h2>
            <div class="form-group">
                <label for="datepicker">Select Date:</label>
                <input type="date" class="form-control" id="datepicker" name="selectedDate" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <select class="form-control" id="purpose" name="purpose">
                    <option value="Purpose A">Purpose A</option>
                    <option value="Purpose B">Purpose B</option>
                    <option value="Purpose C">Purpose C</option>
                    <option value="Purpose D">Purpose D</option>
                    <option value="Purpose E">Purpose E</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>
</body>
</html>
