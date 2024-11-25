<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'service-mobil';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi enkripsi super (Caesar + AES)
function super_encrypt($text, $key) {
    // Enkripsi Caesar sederhana
    $shift = 3;
    $caesar = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $char = ord($text[$i]);
        $char = $char + $shift;
        $caesar .= chr($char);
    }
    
    // Enkripsi AES
    $aes_key = substr(hash('sha256', $key, true), 0, 32);
    $iv = openssl_random_pseudo_bytes(16);
    $aes = openssl_encrypt($caesar, 'aes-256-cbc', $aes_key, 0, $iv);
    
    // Gabungkan IV dan hasil enkripsi AES
    return base64_encode($iv . $aes);
}

// Fungsi dekripsi super (AES + Caesar)
function super_decrypt($encrypted_text, $key) {
    // Pisahkan IV dan ciphertext
    $data = base64_decode($encrypted_text);
    $iv = substr($data, 0, 16);
    $aes_encrypted = substr($data, 16);
    
    // Dekripsi AES
    $aes_key = substr(hash('sha256', $key, true), 0, 32);
    $caesar = openssl_decrypt($aes_encrypted, 'aes-256-cbc', $aes_key, 0, $iv);
    
    // Dekripsi Caesar sederhana
    $shift = 3;
    $text = '';
    for ($i = 0; $i < strlen($caesar); $i++) {
        $char = ord($caesar[$i]);
        $char = $char - $shift;
        $text .= chr($char);
    }
    
    return $text;
}

$message = '';
$decrypted_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Ambil data dari form
    $merek = $_POST['merek'];
    $nama = $_POST['nama'];
    $tahun = $_POST['tahun'];
    $plat_mobil = $_POST['plat_mobil'];
    
    // Enkripsi plat mobil menggunakan super enkripsi
    $key = "kunci_rahasia";
    $encrypted_plat = super_encrypt($plat_mobil, $key);
    
    // Masukkan data ke database
    $sql = "INSERT INTO mobil (merek, nama, tahun, kode_booking) VALUES ('$merek', '$nama', '$tahun', '$encrypted_plat')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil disimpan. Kode booking Anda: " . $encrypted_plat;
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['decrypt'])) {
    // Ambil kode booking untuk didekripsi
    $booking_code = $_POST['kode_booking'];
    
    // Dekripsi kode booking
    $key = "kunci_rahasia";
    $decrypted_plat = super_decrypt($booking_code, $key);
    
    $decrypted_message = "Plat mobil asli: " . $decrypted_plat;
}

$conn->close();
?>

<!-- HTML Form dan Tampilan -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/text.css">
    <title>Input Data Mobil</title>
</head>
<body>
    <div class="container">
        <h2>Input Data Mobil</h2>
        <form method="post" action="text.php">
            Merek Mobil: <input type="text" name="merek" required><br><br>
            Nama Mobil: <input type="text" name="nama" required><br><br>
            Tahun: <input type="number" name="tahun" required><br><br>
            Plat Mobil: <input type="text" name="plat_mobil" required><br><br>
            <input type="submit" name="submit" value="Simpan">
        </form>
        
        <br><br>

        <!-- Tampilkan hasil enkripsi -->
        <?php if (!empty($message)) { echo "<div class='result'><h3>$message</h3></div>"; } ?>
        
        <!-- Form Dekripsi -->
        <h2>Decrypt Kode Booking</h2>
        <form method="post" action="text.php">
            Masukkan Kode Booking: <input type="text" name="kode_booking" required><br><br>
            <input type="submit" name="decrypt" value="Decrypt">
        </form>

        <!-- Tampilkan hasil dekripsi -->
        <?php if (!empty($decrypted_message)) { echo "<div class='result'><h3>$decrypted_message</h3></div>"; } ?>
        
        <br><br>

        <!-- Tombol Kembali ke Home -->
        <div class="center">
            <a href="home.php"><button>Kembali ke Home</button></a>
        </div>
    </div>
</body>
</html>
