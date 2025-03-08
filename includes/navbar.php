<?php session_start(); ?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark " >
            <div class="container">
                <a class="navbar-brand" href="index.php">FH HOTEL</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item .active">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=gallery">Bilder</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=hilfeseite">Hilfe & Impressum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=news">News</a>
                        </li>
                        <?php
                        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true){
                        echo 
                        '
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=buchungverwaltung">Buchungverwaltung</a>
                        </li>';
                         echo 
                        '
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=benutzerverwaltung">Benutzerverwaltung</a>
                        </li>';
                         
                    }
                        ?>
                <?php if (isset($_SESSION['username'])) {
                        echo 
                        '
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=buchungen">Meine Buchungen</a>
                        </li>';
                        echo 
                        '
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=profile">Profile</a>
                        </li>';
                        }else {
                            echo'
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=register">Registrieren</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link .active" href="index.php?page=login">Login</a>
                        </li>';
                        }  ?> 
                        
                    </ul>
                </div>
            </div>
        </nav>
