<?php
session_start();
include 'connect.php';

$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];
$password = $_POST['password'];

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
$query = mysqli_query($connect, $sql);

$cek = mysqli_num_rows($query);
if ($cek > 0) {
    header("location: daftar.php?message=sudah_ada");
} else {
    $sql = "INSERT INTO user (nama, no_hp, email, password) VALUES ('$nama','$no_hp','$email', '$password_hash')";

    $query = mysqli_query($connect, $sql) or die(mysqli_error($connect));

    if ($query) {
        $_SESSION['email'] = $email;
        header("location: login.php?message=daftar");
    }
}

