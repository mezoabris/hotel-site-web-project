<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

?>
<div class="mainpage">
    <h1><img src="img/Grey_and_Beige_Flat___Minimalist_Architecture_Logo__1_-removebg-preview.png" class="mx-auto d-block pt-" alt=""></h1>
    
    <?php
    if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) {
        $username = $_SESSION['username'];
        echo "<p style='color: white; font-weight:700; font-size:20px; text-align: center;'>Wilkommen, $username!</p>";
        
       include "mainpage/reservieren.php";

    } else    echo '<p class="text-center text-white font-weight-bold fs-4 mb-5">Um buchen zu können, müssen Sie <a href="index.php?page=login" class="btn btn-warning">einloggen</a></p>';
    ?>

        
        <div class="teaser-background">
        <div class="teaser">
            <div class="teaser-text">
                <h2>Wilkommen</h2> <br>
                <p>im FH HOTEL, wo Luxus und Eleganz aufeinandertreffen. Im Herzen von Wien bieten wir Ihnen erstklassigen Komfort, exklusive Kulinarik und persönlichen Service – für unvergessliche Momente in einem einzigartigen Ambiente.</p>
            </div>
            <img src="img\mainpage_room.jpg" alt="room">
        </div>
    </div>
