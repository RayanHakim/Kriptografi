<?php
// Fungsi untuk enkripsi Vigenere Cipher dengan base64
function vigenereEncrypt($data, $key) {
    $output = '';
    $keyLength = strlen($key);
    for ($i = 0, $j = 0; $i < strlen($data); $i++) {
        $char = $data[$i];
        $output .= chr((ord($char) + ord($key[$j % $keyLength])) % 256);  // Penanganan biner
        $j++;
    }
    return base64_encode($output); // Encode hasil enkripsi ke base64
}

// Fungsi untuk dekripsi Vigenere Cipher dengan base64
function vigenereDecrypt($data, $key) {
    $output = '';
    $data = base64_decode($data); // Decode base64 sebelum dekripsi
    $keyLength = strlen($key);
    for ($i = 0, $j = 0; $i < strlen($data); $i++) {
        $char = $data[$i];
        $output .= chr((ord($char) - ord($key[$j % $keyLength]) + 256) % 256);  // Penanganan biner
        $j++;
    }
    return $output;
}

// Fungsi untuk mengganti nama file
function renameFile($originalName, $key) {
    return 'encrypted_' . vigenereEncrypt($originalName, $key);  // Nama file dienkripsi
}

// Fungsi untuk mengembalikan nama file asli
function restoreFileName($encryptedName, $key) {
    // Hilangkan prefix 'encrypted_'
    $encryptedName = str_replace('encrypted_', '', $encryptedName);
    return vigenereDecrypt($encryptedName, $key);  // Dekripsi nama file
}

// Proses enkripsi file
if (isset($_POST['upload'])) {
    $file = $_FILES['file']['tmp_name'];
    $originalName = $_FILES['file']['name'];
    $key = $_POST['key'];

    if (file_exists($file)) {
        $content = file_get_contents($file);
        $encryptedData = vigenereEncrypt($content, $key);

        // Ganti nama file dengan nama terenkripsi
        $encryptedFileName = renameFile($originalName, $key);
        
        // Simpan file dengan nama baru dan konten terenkripsi
        file_put_contents($encryptedFileName, $encryptedData);

        echo "File berhasil terenkripsi dengan nama: " . $encryptedFileName . "<br>";
        echo "<a href='file.php?file=" . urlencode($encryptedFileName) . "'>Download file terenkripsi</a><br>";
    }
}

// Proses dekripsi file
if (isset($_POST['decrypt'])) {
    $file = $_FILES['file']['tmp_name'];
    $encryptedFileName = $_FILES['file']['name'];
    $key = $_POST['key'];

    if (file_exists($file)) {
        $content = file_get_contents($file);
        $decryptedData = vigenereDecrypt($content, $key);

        // Kembalikan nama file asli berdasarkan kunci
        $originalFileName = restoreFileName($encryptedFileName, $key);
        
        // Simpan file terdekripsi dengan nama asli
        file_put_contents($originalFileName, $decryptedData);

        echo "File berhasil didekripsi dengan nama: " . $originalFileName . "<br>";
        echo "<a href='file.php?file=" . urlencode($originalFileName) . "'>Download file terdekripsi</a><br>";
    }
}

// Proses download file
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']); // Dapatkan nama file

    if (file_exists($file)) {
        // Set headers untuk download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush(); // Bersihkan buffer sistem
        readfile($file); // Baca file dan kirim ke output
        exit;
    } else {
        echo "File tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/file.css">
    <title>Buku Servis</title>
</head>
<body>
    <div class="container">
        <h1>Upload File Buku Service</h1>
        <form action="file.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required><br><br>
            <label for="key">Masukkan Kunci:</label>
            <input type="text" name="key" required><br><br>
            <input type="submit" name="upload" value="Encrypt">
        </form>
        <br>
        <h1>Decrypt Buku Service</h1>
        <h3>Kunci Mohon Disamakan dengan yang encrypt</h3>
        <form action="file.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required><br><br>
            <label for="key">Masukkan Kunci:</label>
            <input type="text" name="key" required><br><br>
            <input type="submit" name="decrypt" value="Decrypt">
        </form>
        <br>
        <div class="center">
            <a href="home.php"><button>Kembali ke Home</button></a>
        </div>
    </div>
</body>
</html>
