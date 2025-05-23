<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookIt";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: loginView.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer le nom de l'utilisateur
$userName = "";
$sqlUser = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userName);
$stmt->fetch();
$stmt->close();

// Récupérer les événements créés par cet utilisateur
$events = [];
$sqlEvents = "SELECT name, bio, images, tickets, price FROM events WHERE user_id = ?";
$stmt = $conn->prepare($sqlEvents);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>BookIt</title>
    <link rel="stylesheet" href="../css/profile.css">
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
        <div class="middle-container">
            <div class="profile-name">
                <h2>Welcome, <?php echo htmlspecialchars($userName); ?> !</h2>
            </div>
            <div class="event-container">
                <?php foreach ($events as $event):
                    $images = json_decode($event['images'], true);
                    $firstImage = isset($images[0]) ? $images[0] : 'default.jpg';?>

                    <div class="event-card">
                        <img src="../uploads/<?php echo htmlspecialchars($firstImage); ?>" class="event-image" alt="Event Image">
                        <div class="event-details">
                            <div class="event-title"><strong><?php echo htmlspecialchars($event['name']); ?></strong></div>
                            <div class="event-description"><?php echo nl2br(htmlspecialchars($event['bio'])); ?></div>
                            <div class="event-info">
                                <span class="event-price">💶 Price: €<?php echo number_format($event['price'], 2); ?></span>
                                <span>🎟️ Tickets: <?php echo intval($event['tickets']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="bottom-page"></div>
</div>
</body>
<footer></footer>
</html>
