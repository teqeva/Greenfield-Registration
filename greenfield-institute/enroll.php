<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$course_id = $_POST['course_id'];

/* CHECK DUPLICATE ENROLLMENT */
$check = "SELECT * FROM registrations 
          WHERE student_id='$student_id' 
          AND course_id='$course_id'";

$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) > 0){
    echo "Already enrolled in this course!";
    exit();
}

/* INSERT REGISTRATION */
$sql = "INSERT INTO registrations (student_id, course_id)
        VALUES ('$student_id', '$course_id')";

if(mysqli_query($conn, $sql)){
    header("Location: courses.php");
    exit();
} else {
    echo "Enrollment failed";
}
?>