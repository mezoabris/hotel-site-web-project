<?php
require_once 'dbaccess.php'; 
$today = date('Y-m-d');
$success = false;
$available_time= true;
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
        // Get user_id from the database
    $username = $_SESSION['username'];
    $userQuery = "SELECT user_id FROM User WHERE username = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $room_id = $_POST['room_id'];
    $erwachsene = $_POST['erwachsene'];
    if(isset($_POST['kinder'])){
        $kinder= $_POST['kinder'];
    }else{$kinder= 0;}
    if(isset($_POST['frühstück'])){
        $frühstück = $_POST['frühstück'];
    }
    else{$frühstück = 0;}

    if(isset($_POST['parking'])){
        $parkplatz = $_POST['parking'];
    }else{$parkplatz = 0;}

    if(isset($_POST['haustiere'])){
        $haustiere = $_POST['haustiere'];
    }
    else{$haustiere = 0;}

    // Check if the room is available
    $availabilityQuery = "
        SELECT * FROM Booking
        WHERE room_id = ?
        AND (
            (checkin <= ? AND checkout > ?) OR 
            (checkin < ? AND checkout >= ?) OR
            (checkin >= ? AND checkout <= ?)
        )
    ";
    $stmt = $conn->prepare($availabilityQuery);
    $stmt->bind_param("issssss", $room_id, $checkin, $checkin, $checkout, $checkout, $checkin, $checkout);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $available_time = false;
        $success = false; // Room not available
    } else {
        // Insert booking into database
        $sql = "INSERT INTO Booking (user_id, room_id, checkin, checkout, Erwachsene, Kinder, Frühstück, Parkplatz, Haustiere) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissiiiii", $user_id, $room_id, $checkin, $checkout, $erwachsene, $kinder, $frühstück, $parkplatz, $haustiere);

        if ($stmt->execute()) {
            $success = true; // Booking successful
        } else {
            echo "Fehler bei der Buchung: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>



<div class="container my-2">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <form class="border p-4 bg-white rounded shadow" method="POST">
                <h2 class="text-center mb-4">Zimmer Reservieren</h2>
                <?php 
                if ($success) {
                    echo '<p class="alert alert-success" role="alert">Buchung erfolgreich!</p>';
                } elseif (!$available_time) {
                    echo '<p class="alert alert-danger" role="alert">Das gewählte Zimmer ist im angegebenen Zeitraum nicht verfügbar.</p>';
                }
                ?>
                <!-- Check In and Check Out -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="checkin" class="form-label">Check In</label>
                        <input type="date" class="form-control" id="checkin" name="checkin" min=<?php echo $today?> required>
                    </div>
                    <div class="col-md-6">
                        <label for="checkout" class="form-label">Check Out</label>
                        <input type="date" class="form-control" id="checkout" name="checkout" min=<?php echo $today?> required>
                    </div>
                </div>

                <!-- Number of Guests -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="erwachsene" class="form-label">Erwachsene</label>
                        <select class="form-select" id="adults" name="erwachsene" required>
                            <option value="" selected disabled>Anzahl wählen</option>
                            <option value="1">1 Erwachsener</option>
                            <option value="2">2 Erwachsene</option>
                            <option value="3">3 Erwachsene</option>
                            <option value="4">4 Erwachsene</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kinder" class="form-label">Kinder</label>
                        <select class="form-select" id="children" name="kinder">
                            <option value="" selected disabled>Anzahl wählen</option>
                            <option value="0">Keine Kinder</option>
                            <option value="1">1 Kind</option>
                            <option value="2">2 Kinder</option>
                            <option value="3">3 Kinder</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-12">
                    <label for="room_id" class="form-label">Zimmertyp</label>
                    <select class="form-select" id="room_id" name="room_id" required>
                      <option value="" selected disabled>Wähle ein Zimmer</option>
                      <option value="1">Zimmer 1</option>
                      <option value="2">Zimmer 2</option>
                      <option value="3">Zimmer 3</option>
                    </select>
                  </div>
                </div>

                

                <!-- Optional Services -->
                <div class="mb-3">
                    <label class="form-label">Optionale Leistungen</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="frühstück" name="frühstück" value="1">
                        <label class="form-check-label" for="frühstück">Frühstück (+10€)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="parking" name="parking" value="1">
                        <label class="form-check-label" for="parking">Parkplatz (+5€)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="haustiere" name="haustiere" value="1">
                        <label class="form-check-label" for="haustiere">Haustiere (+15€)</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-warning w-100">Buchen</button>
                </div>
                
            </form>
        </div>
    </div>
</div>


        