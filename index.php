<?php 
include('includes/home-page-includes/head.php');
include('includes/home-page-includes/nav-bar.php');
include('includes/home-page-includes/side-bar.php');

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'main-content':
            include ('includes/home-page-includes/main-content.php');
            break;
        case 'lecturer-login':
            include ('includes/home-page-includes/lecturer-login.php');
            break;
        case 'lecturer-signup':
            include ('includes/home-page-includes/lecturer-signup.php');
            break;
        case 'admin-login':
            include ('includes/home-page-includes/admin-login.php');
            break;
        case 'admin-change-password':
            include ('includes/home-page-includes/admin-change-password.php');
            break;
        case 'student-login':
            include ('includes/home-page-includes/student-login.php');
            break;
        case 'student-signup':
            include ('includes/home-page-includes/student-signup.php');
            break;
        default:
            include ('includes/home-page-includes/main-content.php'); // Default page
    }
} else {
    include ('includes/home-page-includes/main-content.php'); // Default page
}

include('includes/home-page-includes/scripts.php');
?>