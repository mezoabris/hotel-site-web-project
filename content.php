<?php

$page;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'mainpage';
}
switch ($page) {
    case 'gallery':
        include "gallery/gallery.php";
        break;
    case 'hilfeseite':
        include "hilfeseite/hilfeseite.php";
        break;
    case 'register' :
        include "register/register.php";
        break;
    case 'login':
        include "login/login.php";
        break;
    case 'profile':
        include "profile/profile.php";
        break;
    case 'news':
        include "news/news2.php";
        break;
    case 'buchungen':
        include "buchungen/buchungen.php";
        break;
    case 'benutzerverwaltung':
        include "admin/benutzerverwaltung.php";
        break;
    case 'edituser':
        include "admin/edituser.php";
        break;
    case 'buchungverwaltung':
        include 'admin/buchungverwaltung.php';
        break;
    case 'deletuser':
        include 'admin/deleteuser.php';
        break;
    default :
        include "mainpage/mainpage.php";
        break;
}
?>