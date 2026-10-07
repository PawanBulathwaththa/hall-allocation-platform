<?php
include 'includes/connection.php'; 

// Fetch batches from the database
$query = "SELECT name, student_count FROM batches ORDER BY student_count DESC";
$result = mysqli_query($conn, $query);

$queryHalls = "SELECT name, capacity FROM halls ORDER BY capacity DESC";
$resultHalls = mysqli_query($conn, $queryHalls);

$halls = [];
while ($hallRow = mysqli_fetch_assoc($resultHalls)) {
    $halls[] = $hallRow;
}

?>