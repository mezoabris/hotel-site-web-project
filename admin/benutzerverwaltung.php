<?php
require_once 'dbaccess.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch user data
$result = $conn->query("SELECT user_id, vorname, nachname, username, email, status FROM User");
?>

<html>
<head>
    <title>User Data</title>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4 mt-5">Benutzern</h1>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    $statusClass = $row['status'] === 'active' ? 'text-success fw-bold' : 'text-danger fw-bold';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['vorname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nachname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td class='$statusClass'>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td class='text-center'>
                    <a href='index.php?page=edituser&id=" . urlencode($row['user_id']) . "' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='index.php?page=deletuser&id=" . urlencode($row['user_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                    </td>";
                    echo "</tr>";
                    
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



    </div>
</body>
</html>
