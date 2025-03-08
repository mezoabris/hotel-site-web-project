<?php
require_once 'dbaccess.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
    $delete_id = intval($_POST['delete_id']);

    // Prepare the DELETE query
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    // Execute the deletion
    if ($stmt->execute()) {
        echo "<p class='text-success text-center'>Artikel erfolgreich gelöscht.</p>";
    } else {
        echo "<p class='text-danger text-center'>Fehler beim Löschen des Artikels.</p>";
    }

    $stmt->close();
}
?>
<div class="bg-light text-center p-5 mt-5">
    <h1>Willkommen zu den Hotel News</h1>
    <p class="lead">Bleiben Sie auf dem Laufenden mit unseren neuesten Nachrichten und Updates!</p>
</div>
<?php
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
    include "news/upload-form.php";
}
?>
<!-- News Section -->
<section id="news" class="container my-5">
    <h2 class="text-center mb-4">Aktuelle Neuigkeiten</h2>
    <div class="row row-cols-1 g-4">
    <?php
    // Prepare the SQL query
    $sql = "SELECT id, title, content, thumbnail_path, created_at FROM news ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);

    // Execute the prepared statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($id, $title, $content, $thumbnail_path, $created_at);

    // Fetch and display the results
    if ($stmt->fetch()) { // Check if there is at least one row
        do {
            echo "<div class='col'>";
            echo "    <div class='card'>";
            echo "        <img src='{$thumbnail_path}' class='card-img-top' alt='News Image'>";
            echo "        <div class='card-body'>";
            echo "            <h5 class='card-title'>{$title}</h5>";
            echo "            <p class='card-text'>{$content}</p>";
            echo "            <p class='card-text text-muted'>Erstellt am: {$created_at}</p>";
            
            // Show delete button if the user is an admin
            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
                echo "            <form method='post' class='mt-2'>";
                echo "                <input type='hidden' name='delete_id' value='{$id}'>";
                echo "                <button type='submit' class='btn btn-danger'>Löschen</button>";
                echo "            </form>";
            }

            echo "        </div>";
            echo "    </div>";
            echo "</div>";
        } while ($stmt->fetch());
    } else {
        echo "<p class='text-center'>Es gibt noch keine Neuigkeiten.</p>";
    }

// Close the statement and connection
$stmt->close();
$conn->close();
?>

        <!-- Static News -->
        <!-- News Item 1 -->
        <div class="col">
            <div class="card">
                <img src="img/sauna.jpg" class="card-img-top" alt="News Image">
                <div class="card-body">
                    <h5 class="card-title">Neue Spa-Angebote</h5>
                    <p class="card-text">Entdecken Sie unsere neuen Spa-Behandlungen für maximale Entspannung.</p>
                </div>
            </div>
        </div>
        <!-- News Item 2 -->
        <div class="col">
            <div class="card">
                <img src="img/sommerangebot.jpg" class="card-img-top" alt="News Image">
                <div class="card-body">
                    <h5 class="card-title">Sommerangebote 2024</h5>
                    <p class="card-text">Buchen Sie jetzt und profitieren Sie von unseren exklusiven Sommerangeboten.</p>
                </div>
            </div>
        </div>
        <!-- News Item 3 -->
        <div class="col">
            <div class="card">
                <img src="img/restaurant.jpg" class="card-img-top" alt="News Image">
                <div class="card-body">
                    <h5 class="card-title">Restaurant Neueröffnung</h5>
                    <p class="card-text">Unser neues Restaurant bietet Ihnen kulinarische Highlights aus der Region.</p>
                </div>
            </div>
        </div>
    </div>
</section>
