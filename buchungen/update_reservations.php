<?php
require_once 'dbaccess.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-confirm past bookings
$today = date('Y-m-d');
$sql = "UPDATE Booking SET confirmed = 1 WHERE checkin <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
if (!$stmt->execute()) {
    $_SESSION['update_message'] = "Error confirming bookings: " . $stmt->error;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to update reservations.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $booking_id = $_POST['booking_id'];

    // Check which action is being performed
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'confirm') {
            $sql = "UPDATE Booking SET confirmed = 1 WHERE booking_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);
            if ($stmt->execute()) {
                $_SESSION['update_message'] = "Reservation confirmed successfully!";
            } else {
                $_SESSION['update_message'] = "Error confirming reservation: " . $stmt->error;
            }
            $stmt->close();
        }
        else if ($action === 'cancel') {
            $sql = "DELETE FROM Booking WHERE booking_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);
            
            if ($stmt->execute()) {
                $_SESSION['update_message'] = "Reservation #$booking_id canceled successfully.";
            } else {
                $_SESSION['update_message'] = "Error canceling reservation: " . $stmt->error;
            }
            $stmt->close();
        }
    } 
    // If no action is set, assume it's an update operation
    else {
        // Get all the POST variables first
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $erwachsene = $_POST['erwachsene'];
        $kinder = $_POST['kinder'];
        $frühstück = $_POST['frühstück'];
        $parkplatz = $_POST['parkplatz'];
        $haustiere = $_POST['haustiere'];

        // Check if checkin is today or in past
        if ($checkin <= $today) {
            $sql = "UPDATE Booking SET confirmed = 1 WHERE booking_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $stmt->close();
        }

        // Update the reservation
        $sql = "UPDATE Booking SET checkin = ?, checkout = ?, Erwachsene = ?, Kinder = ?, Frühstück = ?, Parkplatz = ?, Haustiere = ? WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiiiii", 
            $checkin, 
            $checkout, 
            $erwachsene, 
            $kinder, 
            $frühstück, 
            $parkplatz, 
            $haustiere, 
            $booking_id
        );

        if ($stmt->execute()) {
            $_SESSION['update_message'] = "Reservation updated successfully!";
        } else {
            $_SESSION['update_message'] = "Error updating reservation: " . $stmt->error;
        }
        $stmt->close();
    }

    // Redirect based on admin status
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
        header("Location: http://localhost/hotelseite_php/index.php?page=buchungverwaltung");
    } else {
        header("Location: http://localhost/hotelseite_php/index.php?page=buchungen");
    }
    exit;
}
?>