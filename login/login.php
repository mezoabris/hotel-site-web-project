<?php
require_once 'dbaccess.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$inactiveErrorMessage= 'Profile ist inaktiv';
$generalErrorMessage = 'Benutzername oder Passwort ist falsch.';
$successMessage = 'Login erfolgreich! Weiterleitung...';
$_SESSION['user_logged_in'] = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['loginButton'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Check if username and password are provided
        if (!empty($username) && !empty($password)) {
            // Database query to fetch hashed password and user role
            $sql = "SELECT user_id, password, is_admin, status FROM User WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($user_id, $hashed_password, $is_admin, $status);
            $stmt->fetch();
            $stmt->close();

            
            
            
            // Verify the user
            if ($user_id && password_verify($password, $hashed_password) && $status === 'active') {
                // Login successful
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_logged_in'] = true;

                
                if ((int)$is_admin === 1) {
                    $_SESSION['is_admin'] = true;
                } else {
                    $_SESSION['is_admin'] = false;
                }   

                $message = $successMessage;
                $alertType = "success";
                header("Refresh: 0.5; url=http://localhost/hotelseite_php/index.php");
                exit();
            } else {
                if($status != 'active'){
                    $message = $inactiveErrorMessage;
                    $alertType = "danger";
                }
                else{
                    $message = $generalErrorMessage;
                    $alertType = "danger";
                }

                }
                // Invalid username or password
        } else {
            $message = "Bitte geben Sie Benutzername und Passwort ein.";
            $alertType = "warning";
        }
    }
}

?>


<main class="login-page">
    <h1 class="mt-5">Log into your account</h1>
    <div class="login-form">
    <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $alertType; ?> mt-3" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">    
            <label for="username">Username</label> <br>
            <input type="text" placeholder="maxmustermann" name="username" required> <br>
            <label for="password">Passwort</label> <br>
            <input type="password" name="password" id="password" required> <br>
            <button type="submit" name="loginButton">Login</button> <br>
            <p>Ich habe noch keinen Account</p>
            <a href="index.php?page=register">Registrieren</a>
        </form>

        
    </div>
</main>



