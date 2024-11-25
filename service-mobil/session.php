<?php
session_start();
include 'connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$email = strip_tags(mysqli_real_escape_string($connect, trim($email)));
$password = strip_tags(mysqli_real_escape_string($connect, trim($password)));

$sql = "SELECT * FROM user WHERE email = '$email'";
$query = mysqli_query($connect, $sql);

if (mysqli_num_rows($query)) {
    $row = mysqli_fetch_array($query);
    $password_hash = $row['password'];
    if (password_verify($password, $password_hash)) {
        $_SESSION['email'] = $email;
        $_SESSION['status'] = "login";
        header("location: home.php");
    }
} else
    header("location: login.php?message=failed");
