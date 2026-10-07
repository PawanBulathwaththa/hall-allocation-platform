<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: ./includes/home-page-includes/admin-login.php");
    exit();
}
include('includes/connection.php');
include('includes/admin-dashboard-includes/head.php');
include('includes/admin-dashboard-includes/loader.php');
include('includes/admin-dashboard-includes/nav-bar.php');
include('includes/admin-dashboard-includes/side-bar/side-bar1.php');

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'dashboard':
            include('includes/admin-dashboard-includes/side-bar/side-bar2-1.php');
            break;
        case 'manage-halls':
            include('includes/admin-dashboard-includes/side-bar/side-bar2-2.php');
            break;
        case 'manage-batches':
            include('includes/admin-dashboard-includes/side-bar/side-bar2-3.php');
            break;
        case 'lab-assign':
            include('includes/admin-dashboard-includes/side-bar/side-bar2-4.php');
            break;
        default:
            include('includes/admin-dashboard-includes/side-bar/side-bar-default.php'); // Default page
    }
} else {
    include('includes/admin-dashboard-includes/side-bar/side-bar-default.php'); // Default page
}
include('includes/admin-dashboard-includes/side-bar/side-bar3.php');

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'dashboard':
            include('includes/admin-dashboard-includes/dashboard.php');
            break;
        case 'manage-halls':
            include('includes/admin-dashboard-includes/manage-halls.php');
            break;
        case 'manage-batches':
            include('includes/admin-dashboard-includes/manage-batches.php');
            break;
        case 'time-table':
            include('includes/admin-dashboard-includes/time-table.php');
            break;
        case 'lecturer':
            include('includes/admin-dashboard-includes/lecturer.php');
            break;
        case 'student':
            include('includes/admin-dashboard-includes/student.php');
            break;
        case 'admin-profile':
            include('includes/admin-dashboard-includes/admin-profile.php');
            break;
        case 'edit-admin':
            include('includes/admin-dashboard-includes/edit-admin.php');
            break;
        case 'time-table-soc':
            include('includes/admin-dashboard-includes/time-table-soc.php');
            break;
        case 'mail-box':
            include('includes/admin-dashboard-includes/mail-box.php');
            break;
        case 'lab-assign':
            include('includes/admin-dashboard-includes/lab-assign.php');
            break;

        default:
            include('includes/admin-dashboard-includes/dashboard.php'); // Default page
    }
} else {
    include('includes/admin-dashboard-includes/dashboard.php'); // Default page
}

include('includes/admin-dashboard-includes/scripts.php');
include('./footer.php');
