<?php

require_once 'dbaccess.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $vorname = $_POST['vorname'];
        $nachname = $_POST['nachname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $password = $_POST['password'];

        // Fetch the current password from the database
        $stmt = $conn->prepare("SELECT password FROM User WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentUser = $result->fetch_assoc();

        // Hash the password only if it has been changed
        if (!empty($password)) {
            // Hash the new password
            $password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // Keep the existing password
            $password = $currentUser['password'];
        }

        $stmt = $conn->prepare("UPDATE User SET vorname = ?, nachname = ?, username = ?, email = ?, status = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("ssssssi", $vorname, $nachname, $username, $email, $status, $password, $id);
        $stmt->execute();

        header("Location: http://localhost/hotelseite_php/index.php?page=benutzerverwaltung");
        exit();
    }

    $stmt = $conn->prepare("SELECT vorname, nachname, username, email, status, password FROM User WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    header("Location: http://localhost/hotelseite_php/index.php?page=benutzerverwaltung");
    exit();
}
?>


<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="post" class="p-4 border rounded bg-light">
    <div class="mb-3">
        <label for="vorname" class="form-label">Vorname:</label>
        <input type="text" class="form-control" id="vorname" name="vorname" value="<?php echo htmlspecialchars($user['vorname']); ?>">
    </div>
    <div class="mb-3">
        <label for="nachname" class="form-label">Nachname:</label>
        <input type="text" class="form-control" id="nachname" name="nachname" value="<?php echo htmlspecialchars($user['nachname']); ?>">
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status:</label>
        <select class="form-select" id="status" name="status">
            <option value="active" <?php if ($user['status'] === 'active') echo 'selected'; ?>>active</option>
            <option value="inaktiv" <?php if ($user['status'] === 'inaktiv') echo 'selected'; ?>>inaktiv</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password">
        <small class="form-text text-muted">Leave this field blank if you do not want to change the password.</small>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    </form>
</body>
</html>

