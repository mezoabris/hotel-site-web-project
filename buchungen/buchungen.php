<?php
require_once 'dbaccess.php';
$today = date('Y-m-d');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'];
$sql ='SELECT booking_id, room_id, checkin, checkout, Erwachsene, Kinder, Frühstück, Parkplatz, Haustiere, confirmed FROM Booking WHERE user_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id );
$stmt->execute();
$stmt->bind_result($booking_id,$room_id, $checkin, $checkout, $erwachsene, $kinder, $frühstück, $parkplatz, $haustiere, $confirmed);

echo '<p class="mt-5">'.$erwachsene.'</p>';

$reservations = [];
while ($stmt->fetch()) {
    $reservations[] = [
        'booking_id' => $booking_id,
        'room_id' => $room_id,
        'checkin' => $checkin,
        'checkout' => $checkout,
        'erwachsene' => $erwachsene,
        'kinder' => $kinder,
        'frühstück' => $frühstück,
        'parkplatz' => $parkplatz,
        'haustiere' => $haustiere,
        'confirmed' => $confirmed
    ];
}
$stmt->close();

include 'buchungen/update_reservations.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Your Reservations</h2>
    
    <?php if (isset($_SESSION['update_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['update_message']; ?></div>
        <?php unset($_SESSION['update_message']); ?>
    <?php endif; ?>

    <!-- Form to display all reservations -->
    <?php foreach ($reservations as $index => $reservation): ?>
        <form action="" method="POST">
    <div class="card mb-3">
        <!-- Card Header with Collapse Trigger -->
        <div class="card-header" id="heading_<?php echo $index; ?>">
            <h5 class="mb-0">
                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $index; ?>">
                    Reservation #<?php echo $index + 1; ?>
                </button>
            </h5>
        </div>

        <!-- Card Body (Collapsible Section) -->
        <div id="collapse_<?php echo $index; ?>" class="collapse" aria-labelledby="heading_<?php echo $index; ?>" data-bs-parent="#reservations">
            <div class="card-body">
                <!-- Room ID (hidden) -->
                <input type="hidden" name="booking_id" value="<?php echo $reservation['booking_id']; ?>">
                <div class="form-group mb-3">
                    <label class="form-label">Room</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($reservation['room_id']); ?>" disabled>
                </div>
                <!-- Check-in -->
                <div class="form-group mb-3">
                    <label for="checkin_<?php echo $index; ?>" class="form-label">Check-in</label>
                    <input type="date" id="checkin_<?php echo $index; ?>" name="checkin" class="form-control" value="<?php echo $reservation['checkin']; ?>" min="<?php echo $today?>" required 
                    <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                </div>

                <!-- Check-out -->
                <div class="form-group mb-3">
                    <label for="checkout_<?php echo $index; ?>" class="form-label">Check-out</label>
                    <input type="date" id="checkout_<?php echo $index; ?>" name="checkout" class="form-control" value="<?php echo $reservation['checkout']; ?>" min="<?php echo $today?>" required 
                    <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                </div>

                <!-- Adults -->
                <div class="form-group mb-3">
                    <label for="erwachsene_<?php echo $index; ?>" class="form-label">Adults</label>
                    <input type="number" id="erwachsene_<?php echo $index; ?>" name="erwachsene" class="form-control" value="<?php echo $reservation['erwachsene']; ?>" required 
                    <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                </div>

                <!-- Children -->
                <div class="form-group mb-3">
                    <label for="kinder_<?php echo $index; ?>" class="form-label">Children</label>
                    <input type="number" id="kinder_<?php echo $index; ?>" name="kinder" class="form-control" value="<?php echo $reservation['kinder']; ?>" required 
                    <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                </div>

                <!-- Breakfast -->
                <div class="form-group mb-3">
                    <label for="frühstück_<?php echo $index; ?>" class="form-label">Breakfast</label>
                    <select id="frühstück_<?php echo $index; ?>" name="frühstück" class="form-control" required <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                        <option value="1" <?php echo ($reservation['frühstück'] == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($reservation['frühstück'] == 0) ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <!-- Parking -->
                <div class="form-group mb-3">
                    <label for="parkplatz_<?php echo $index; ?>" class="form-label">Parking</label>
                    <select id="parkplatz_<?php echo $index; ?>" name="parkplatz" class="form-control" required <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                        <option value="1" <?php echo ($reservation['parkplatz'] == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($reservation['parkplatz'] == 0) ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <!-- Pets -->
                <div class="form-group mb-3">
                    <label for="haustiere_<?php echo $index; ?>" class="form-label">Pets</label>
                    <select id="haustiere_<?php echo $index; ?>" name="haustiere" class="form-control" required <?php if($reservation['checkin'] <= $today || $reservation['confirmed'] === 1){echo 'disabled';}?>>
                        <option value="1" <?php echo ($reservation['haustiere'] == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($reservation['haustiere'] == 0) ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <?php if($reservation['checkin'] >= $today && $reservation['confirmed'] != 1){
                    echo '<button type="submit" name="action" value="confirm" class="btn btn-success w-100 m-2">Confirm</button>';
                    echo '<button type="submit" class="btn btn-primary w-100 m-2">Update</button>';
                    echo '<button type="submit" name="action" value="cancel" class="btn btn-danger w-100 m-2">Cancel </button>';
                }
                

                ?>
            </div>
        </div>
    </div>
</form>

    <?php endforeach; ?>
</div>