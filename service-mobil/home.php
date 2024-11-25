<?php
session_start();
if (empty($_SESSION['email'])) {
    header("location: login.php?message=belum_login");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/home.css">
    <title>Website Kriptografi</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <span class="logo">WEBSITE SERVICE MOBIL</span>
    </nav>

    <!-- Button Section -->
    <div class="button-section">
        <div class="button-grid">
            <a href="text.php" class="cta-btn">Booking</a>
            <a href="gambar.php" class="cta-btn">Bukti Bayar</a>
            <a href="file.php" class="cta-btn">Buku Servis</a>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <!-- Logout Button -->
            <a href="logout.php" class="cta-btn logout-btn">Logout</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>123220100 Rayan Luqman Hakim</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>
