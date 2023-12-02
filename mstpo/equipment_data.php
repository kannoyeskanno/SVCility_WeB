<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment List</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa; /* Bootstrap background color */
            border: solid;
            width: 990px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        .edit-icon {
            cursor: pointer;
            font-size: 18px;
            color: #3498db;
        }

        .edit-icon:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Equipment List</h2>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Edit</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="equipmentTableBody">
                    <!-- Equipment items will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js (required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to fetch equipment data from the server
        async function fetchEquipmentData() {
            try {
                const response = await fetch('getEquipment.php');
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching equipment data:', error);
                return [];
            }
        }

        // Function to render equipment data
        async function renderEquipmentTable() {
            const tableBody = document.getElementById('equipmentTableBody');

            // Fetch equipment data
            const equipmentData = await fetchEquipmentData();

            // Clear existing rows
            tableBody.innerHTML = '';

            // Loop through the equipment data and create rows
            equipmentData.forEach(equipment => {
                const row = document.createElement('tr');

                // Adjust the column names based on your actual table structure
                const imageCell = document.createElement('td');
                const image = document.createElement('img');
                const equipmentImagePath = '../resources/equipment_imgs/' + equipment.image_name + '.png';

                image.src = equipmentImagePath;
                image.alt = 'Equipment Image';
                image.width = 50; // Set the desired width
                image.height = 50; // Set the desired height
                imageCell.appendChild(image);
                row.appendChild(imageCell);

                const nameCell = document.createElement('td');
                nameCell.textContent = equipment.equipment_name;
                row.appendChild(nameCell);

                const qtyCell = document.createElement('td');
                qtyCell.textContent = equipment.qty;
                row.appendChild(qtyCell);

                const editCell = document.createElement('td');
                const editIcon = document.createElement('span');
                editIcon.className = 'edit-icon';
                editIcon.textContent = '✎'; // Edit icon (you can use an actual icon here)
                editIcon.addEventListener('click', () => handleEditClick(equipment));
                editCell.appendChild(editIcon);
                row.appendChild(editCell);

                const detailsCell = document.createElement('td');
                const detailsLink = document.createElement('a');
                detailsLink.href = 'logs.php?equipmentId=' + equipment.id;
                detailsLink.textContent = 'Details';
                detailsCell.appendChild(detailsLink);
                row.appendChild(detailsCell);

                tableBody.appendChild(row);
            });
        }

        // Function to handle edit icon click (replace with actual edit functionality)
        function handleEditClick(equipment) {
            // Add your edit logic here (e.g., opening a modal for editing)
            console.log('Edit clicked for equipment:', equipment);
        }

        // Initial rendering of the table
        renderEquipmentTable();
    </script>
</body>
</html>
