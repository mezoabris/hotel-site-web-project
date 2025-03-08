<?php
require_once 'dbaccess.php'; // Include your database connection

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

$nachname = $vorname = $email = $anrede = $username = $password = $password2 = "";
$error = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $nachname = test_input($_POST["nachname"]);
    $vorname = test_input($_POST["vorname"]);
    $email = test_input($_POST["email"]);
    $anrede = test_input($_POST["anrede"]);
    $username = test_input($_POST["username"]);
    $password = $_POST["password"]; 
    $password2 = $_POST["password2"];

    // Validation
    if ($password !== $password2) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO User (anrede, vorname, nachname, email, password, username) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $anrede, $vorname, $nachname, $email, $hashed_password, $username);
            
            // Execute the statement
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                // Set session variables
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['nachname'] = $nachname;
                $_SESSION['vorname'] = $vorname;
                $_SESSION['email'] = $email;
                $_SESSION['anrede'] = $anrede;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;

                // Redirect to main page
                header("Location: http://localhost/hotelseite_php/index.php");
                exit();
            } else {
                $error = "Error: Could not register user. " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Close database connection
$conn->close();
?>

<main id="register_background">
    <h1 id="register">Erstellen Sie einen Account</h1>
    
    
    <div class="register mb-5">
        <form method="POST" action="">
            <div class="div1">
                <label for="anrede">Anrede</label> <br>
                <select name="anrede" id="anrede" required>
                    <option value="">Bitte wählen</option>
                    <option value="frau">Frau</option>
                    <option value="herr">Herr</option>
                </select> <br>
                <label for="vorname">Vorname</label> <br>
                <input type="text" name="vorname" id="vorname" required placeholder="Max"> <br>
                
                <label for="nachname">Nachname</label> <br>
                <input type="text" name="nachname" id="nachname" required placeholder="Mustermann"> <br>
                <label for="email">E-mail Adresse</label> <br>
                <input type="email" name="email" id="email" required placeholder="muster@xyz.com"> <br>
            </div>
            <div class="div2">
                <label for="username">Username</label> <br>
                <input type="text" name="username" placeholder="maxmustermann" required minlength="5"> <br>
                
                <label for="password">Passwort</label> <br>
                <input type="password" name="password" id="password" required minlength="8"> <br>
                
                <label for="password2">Passwort bestätigen</label> <br>
                <input type="password" name="password2" id="password2" required minlength="8"> <br>
                
                <?php if ($error): ?>
                    <p style='color: red; text-align: center;'><?php echo $error; ?></p>
                <?php endif; ?>
                
                <button type="submit">Registrieren</button> <br>
            </div>
            <div class="div3">
                <p>Ich habe schon einen Account <a href="index.php?page=login">Login</a></p>
            </div>
        </form>
    </div>
</main>
