<?php
include 'connection.php';
session_start();
$userEmail = '';
$id = '';

if (isset($_SESSION['user']) && isset($_SESSION['user_id'])) {
    $userEmail = $_SESSION['user'];
    $id = $_SESSION['user_id'];
}

$courseCode = $_GET['course_code'];
$scNumber = $_GET['sc_number'];
$semester = 'Summer 2023';

$selectSql = "SELECT * FROM advised_course2 WHERE st_id = '$id' AND Semester = '$semester'";

$result = mysqli_query($conn, $selectSql);

if ($result) {
    $numRows = mysqli_num_rows($result);  // Get the number of rows
    if ($row = mysqli_fetch_assoc($result)) {
        $creditPerSemester = $row['credit_per_semester'];
    } else {
        $creditPerSemester = 3;
    }
} else {
    $numRows = 0;
    $creditPerSemester = 3;
}

if($numRows<4){
    // echo $numRows;//check if pre req done and course already take 
    $sql1 = "SELECT c.prerequisite FROM courses c WHERE c.course_code='$courseCode';";
    $result1 = mysqli_query($conn, $selectSql);
    $row1 = mysqli_fetch_array($result1);
    $preq = $row1["prerequisite"];
    echo "<script>alert('You have reached the maximum number of courses for this semester');</script>";
    //SELECT c.prerequisite FROM courses c WHERE c.course_code="courseCode";
    $newNumberOfCourse = $numRows+1;
    $sql = "INSERT INTO `advised_course2` (`Semester`, `st_id`, `section`, `C_code`, `credit_per_semester`, `number_of_course`) VALUES ('$semester', '$id', '$scNumber', '$courseCode', '3', '$newNumberOfCourse')";

    if (mysqli_query($conn, $sql)) {
        echo "Record inserted successfully.";
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
    
    header('Location:advising.php');
}
else{
    echo "<script>alert('You have reached the maximum number of courses for this semester');</script>";
    echo "<script>window.location.href = 'home.php';</script>";
}

?>
