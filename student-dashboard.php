<?php
session_start(); // Start the session

// Protect the dashboard by checking if the student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if not logged in
    header("Location: ./includes/home-page-includes/student-login.php");
    exit;
}

// Variables from session to use in the dashboard
$studentId = $_SESSION['student_id'];
$studentName = $_SESSION['student_name'];

include('includes/student-dashboard-includes/head.php');
include('includes/student-dashboard-includes/nav-bar.php');
include('includes/student-dashboard-includes/side-bar/side-bar.php');

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'student-view':
            include('includes/student-dashboard-includes/side-bar/side-bar2-1.php');
            break;
        case 'time-table':
            include('includes/student-dashboard-includes/side-bar/side-bar2-2.php');
            break;

        default:
            include('includes/student-dashboard-includes/side-bar/side-bar2-1.php'); // Default page
    }
} else {
    include('includes/student-dashboard-includes/side-bar/side-bar2-1.php'); // Default page
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'student-view':
            include('includes/student-dashboard-includes/student-view.php');
            break;
        case 'time-table':
            include('includes/student-dashboard-includes/time-table.php');
            break;
        case 'student-account':
            include('includes/student-dashboard-includes/student-account.php');
            break;
        default:
            include('includes/student-dashboard-includes/student-view.php'); // Default page
    }
} else {
    include('includes/student-dashboard-includes/student-view.php'); // Default page
}

include('includes/student-dashboard-includes/footer03.php');
include('includes/student-dashboard-includes/scripts.php');
