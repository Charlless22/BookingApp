<?php
session_start();

// Connexion BDD
$host = "localhost";
$username = "root";
$password = "";
$database = "bookit";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Gestion réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);

    // Vérifie et met à jour le nombre de tickets
    $checkSql = "SELECT tickets FROM events WHERE id = $eventId";
    $checkResult = $conn->query($checkSql);

    if ($checkResult && $checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        if ($row['tickets'] > 0) {
            $newTickets = $row['tickets'] - 1;
            $updateSql = "UPDATE events SET tickets = $newTickets WHERE id = $eventId";
            $conn->query($updateSql);
        }
    }
}

// Récupérer les événements (avec images)
$sql = "SELECT id, name, bio, tickets, price, notes, created_at, images FROM events ORDER BY created_at DESC";
$result = $conn->query($sql);

// Format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d-m-Y H:i');
}
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>BookIt</title>
        <link rel="stylesheet" href="../css/template.css">
        <link rel="stylesheet" href="../css/dashboard.css">
        <link rel="icon" type="image/png" href="../assets/logo.png">
    </head>
    <body>
    <div class="container">
        <div class="top-page">
            <div class="navbar">
                <div class="logo">
                    <img src="../assets/logo.png" width="75" height="75">
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
            <div class="events-grid">
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $images = json_decode($row['images'], true);
                        $firstImage = isset($images[0]) ? $images[0] : 'default.jpg';

                        $isFull = $row['tickets'] <= 0;
                        ?>
                        <div class="event-card">
                            <img src="../uploads/<?php echo htmlspecialchars($firstImage); ?>" class="event-image" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="event-details">
                                <div class="event-title"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="event-description"><?php echo htmlspecialchars(substr($row['bio'], 0, 120)) . (strlen($row['bio']) > 120 ? '...' : ''); ?></div>
                                <div class="event-info">
                                    <span class="event-date">📅 Date: <?php echo formatDate($row['created_at']); ?></span>
                                    <span class="event-price">💶 Price: €<?php echo number_format($row['price'], 2); ?></span>
                                    <span>🎟️ Tickets: <?php echo $row['tickets']; ?></span>
                                </div>
                                <?php if ($isFull): ?>
                                    <button class="event-button" style="background-color: #FF7B7B;" disabled>Full</button>
                                <?php else: ?>
                                    <form method="post">
                                        <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="event-button" style="background-color: #9DFF9D;">Book</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div style='text-align: center; width: 100%; padding: 50px;'>No events found. <a href='createEventView.php'>Create an event</a> to get started.</div>";
                }
                ?>
            </div>
        </div>
        <div class="bottom-page"></div>
    </div>
    </body>
    <footer>
    </footer>
    </html>

<?php
$conn->close();
?>