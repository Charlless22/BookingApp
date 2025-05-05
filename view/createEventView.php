<?php
// Start session
session_start();

// Database connection
$host = "localhost"; // Change according to your database host
$username = "root"; // Change according to your database username
$password = ""; // Change according to your database password
$database = "bookit"; // Change according to your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $tickets = intval($_POST['tickets']);
    $price = floatval($_POST['price']);
    $notes = 0;

    $images = [];

    if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['eventImage']['name'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowed)) {
            $uploadDir = '../uploads/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate unique filename
            $newFilename = uniqid() . '.' . $fileExt;
            $destination = $uploadDir . $newFilename;

            if (move_uploaded_file($_FILES['eventImage']['tmp_name'], $destination)) {
                $images[] = $destination;
            }
        }
    }

    // Convert images array to JSON
    $imagesJson = json_encode($images);

    // Insert data into the database
    $sql = "INSERT INTO events (name, bio, images, notes, tickets, price) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssidi", $title, $description, $imagesJson, $notes, $tickets, $price);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Event created successfully!";
        header("Location: dashboardView.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Error creating event: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>BookIt</title>
    <link rel="stylesheet" href="../css/create.css">
    <link rel="stylesheet" href="../css/template.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">
</head>
<body>
    <div class="container">
        <div class="top-page">
            <div class="navbar">
                <div class="logo">
                    <a href="dashboardView.php">
                        <img src="../assets/logo.png" width="75" height="75">
                    </a>
                </div>
                <div class="navbar-research">
                    <input class="research" type="text" placeholder="Research ...">
                </div>
                <div class="button-container">
                    <a href="profileView.php" class="nav-button">Profile</a>
                    <a href="createEventView.php" class="nav-button">Create event</a>
                </div>
            </div>
        </div>
        <div class="middle-page">
            <form class="event-container" action="createEventView.php" method="POST" enctype="multipart/form-data">
                <div class="top-container">
                    <div class="top-container-picture">
                        <!-- Clickable image to upload a file -->
                        <label for="eventImage">
                            <img class="event-picture" id="previewImage" src="../assets/no-photo.png" width="300" style="cursor:pointer;">
                        </label>
                        <input type="file" id="eventImage" name="eventImage" accept="image/*" style="display: none;" onchange="previewFile()">
                    </div>
                    <div class="top-container-info">
                        <div class="create-button-wrapper">
                            <button type="submit" class="create-button">Create Event</button>
                        </div>
                        <div class="top-container-title">
                            <input class="fields" type="text" name="title" placeholder="Enter event title" required>
                        </div>
                        <div class="top-container-descr">
                            <textarea  class="fields" name="description" placeholder="Enter event description" rows="4" style="width: 95%" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="bottom-container">
                    <div class="bottom-container-notes">
                        <div class="bottom-container-notation">
                            <h4>Notes</h4>
                        </div>
                        <div class="bottom-container-evaluation">
                            <progress class="gauge" value="0" max="100" id="noteGauge"></progress>
                        </div>
                    </div>
                    <div class="bottom-container-tickets">
                        <div class="bottom-container-prices">
                            <h4>Tickets</h4>
                            <input class="fields" type="number" name="tickets" placeholder="Number of tickets" min="0" style="margin-bottom: 1em;">
                            <input class="fields" type="number" name="price" placeholder="Price per ticket (€)" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <div class="bottom-page">
        </div>
    </div>
    <script>
        function previewFile() {
            const preview = document.getElementById('previewImage');
            const file = document.getElementById('eventImage').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
<footer>
</footer>
</html>