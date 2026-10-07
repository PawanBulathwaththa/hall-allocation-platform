<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['lecturer_id'])) {
    header("Location: ./includes/home-page-includes/lecturer-login.php"); // Redirect to login if not logged in
    exit();
}

// Retrieve lecturer information
$lecturer_name = $_SESSION['lecturer_name'];
$lecturer_email = $_SESSION['lecturer_email'];


include('includes/lecturer-dashboard-includes/head.php');
include('includes/lecturer-dashboard-includes/nav-bar.php');
include('includes/lecturer-dashboard-includes/side-bar/side-bar.php');

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'view':
            include('includes/lecturer-dashboard-includes/side-bar/side-bar2-1.php');
            break;
        case 'reqLab':
            include('includes/lecturer-dashboard-includes/side-bar/side-bar2-2.php');
            break;
        case 'time-table':
            include('includes/lecturer-dashboard-includes/side-bar/side-bar2-3.php');
            break;
        default:
            include('includes/lecturer-dashboard-includes/side-bar/side-bar2-1.php'); // Default page
    }
} else {
    include('includes/lecturer-dashboard-includes/side-bar/side-bar2-1.php'); // Default page
}


if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'view':
            include('includes/lecturer-dashboard-includes/view.php');
            break;
        case 'reqLab':
            include('includes/lecturer-dashboard-includes/reqLab.php');
            break;
        case 'time-table':
            include('includes/lecturer-dashboard-includes/time-table.php');
            break;
        case 'lecturer-account':
            include('includes/lecturer-dashboard-includes/lecturer-account.php');
            break;
        case 'lecturer-account-edit':
            include('includes/lecturer-dashboard-includes/lecturer-account-edit.php');
            break;

        default:
            include('includes/lecturer-dashboard-includes/view.php'); // Default page
    }
} else {
    include('includes/lecturer-dashboard-includes/view.php'); // Default page
}
include('includes/lecturer-dashboard-includes/footer02.php');
include('includes/lecturer-dashboard-includes/scripts.php');
