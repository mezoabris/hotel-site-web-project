<?php

require_once 'dbaccess.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: http://localhost/hotelseite_php/index.php'); 
    exit();
}
$user_id = $_SESSION['user_id'];
$profile_updated = false;
$sql = "SELECT username, vorname, nachname, email FROM User WHERE user_id =?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($username, $vorname, $nachname, $email);
$stmt->fetch();
$stmt->close();





if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $new_username = $_POST['username'];
    $new_vorname = $_POST['vorname'];
    $new_nachname = $_POST['nachname'];
    $new_email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $sql = "UPDATE User SET username= ?, vorname = ?, nachname = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $new_username, $new_vorname, $new_nachname, $new_email, $user_id);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        $profile_updated = true;
    }
    $stmt->close();

    if (!empty($old_password) && !empty($new_password)) {
        // Retrieve the hashed password from the database
        $sql = "SELECT password FROM User WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();
    }
    if(!empty($old_password) && !empty($new_password)){
        if(password_verify($old_password, $hashed_password)){
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE User SET password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_hashed_password, $user_id);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $profile_updated = true;
            }
            $stmt->close();
        }else {
            echo "<div class='alert alert-danger mt-5'>Das aktuelle Passwort ist falsch!</div>";           
        }
    }


}
    
if ($profile_updated) {
    echo "<div class='alert alert-success mt-5'>Profile updated successfully!</div>";
}

?>

<body>
    <div class="container">
        <h2 class="my-5">Profile of <?php echo htmlspecialchars($username); ?></h2>

        <form action="index.php?page=profile" method="post">
        <div class="form-group">
                <label for="name">Username:</label>
                <input type="text" class="form-control" name="username" id="username" value="<?php 
                if(isset($new_username)&& $profile_updated == true){
                    echo $new_username;
                    $_SESSION['username'] = $new_username;
                }else{
                    echo $username;} ?>">
            </div>

            <div class="form-group">
                <label for="name">Vorname:</label>
                <input type="text" class="form-control" name="vorname" id="vorname" value="<?php
                if(isset($new_vorname)&& $profile_updated == true){
                    echo $new_vorname;
                }else{
                    echo $vorname;} ?>">
            </div>

            <div class="form-group">
                <label for="name">Nachname:</label>
                <input type="text" class="form-control" name="nachname" id="nachname" value="<?php
                 if(isset($new_nachname)&& $profile_updated == true){
                    echo $new_nachname;
                }else{
                    echo $nachname;}?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php
                if(isset($new_email)&& $profile_updated == true){
                    echo $new_email;
                }else{
                    echo $email;}?>">
            </div>

            <div class="form-group">
                <label for="old_password">Old Password:</label>
                <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Enter your  password">
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control mb-3" name="new_password" id="new_password" placeholder="Enter new password">
            </div>

            <button type="submit" class="btn btn-success">Update Profile</button>
        </form>

        <br>
        <a href="includes/logout.php" class="btn btn-danger mb-5">Logout</a>
    </div>


