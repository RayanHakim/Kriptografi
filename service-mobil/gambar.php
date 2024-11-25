<?php
$decrypted_message = '';
$decrypted_image_path = '';

if (isset($_POST['upload'])) {
    // Konfigurasi file yang diunggah
    $image = $_FILES['image']['tmp_name'];
    $message = $_POST['message'];

    // Mengecek apakah file yang diunggah adalah gambar PNG
    $image_info = getimagesize($image);
    $image_type = $image_info[2];

    if ($image_type != IMAGETYPE_PNG) {
        echo "<p>File yang diunggah bukan gambar PNG. Silakan unggah gambar berformat PNG.</p>";
    } else {
        if ($image && $message) {
            // Proses penyisipan pesan ke dalam gambar
            $img = imagecreatefrompng($image);
            $width = imagesx($img);
            $height = imagesy($img);

            $message_bin = '';
            for ($i = 0; $i < strlen($message); $i++) {
                $message_bin .= sprintf("%08b", ord($message[$i]));
            }

            $message_len = strlen($message_bin);
            $message_index = 0;

            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    if ($message_index < $message_len) {
                        $rgb = imagecolorat($img, $x, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;

                        // Menyisipkan bit pesan ke bit paling akhir pada nilai biru
                        $b = ($b & 0xFE) | intval($message_bin[$message_index]);
                        $message_index++;

                        $new_color = imagecolorallocate($img, $r, $g, $b);
                        imagesetpixel($img, $x, $y, $new_color);
                    }
                }
            }

            // Menyimpan gambar baru yang sudah disisipkan pesan
            $encrypted_image = 'encrypted_image.png';
            imagepng($img, $encrypted_image);
            imagedestroy($img);

            echo "<p>Gambar berhasil dienkripsi. <a href='$encrypted_image' download>Unduh gambar</a></p>";
            echo "<img src='$encrypted_image' alt='Gambar yang dienkripsi' style='max-width: 100%; height: auto;' />";
        } else {
            echo "<p>Silakan unggah gambar dan masukkan pesan.</p>";
        }
    }
}

// Fungsi untuk mendekripsi pesan dari gambar
if (isset($_POST['decrypt'])) {
    $image = $_FILES['image']['tmp_name'];

    if ($image) {
        // Mengecek apakah file yang diunggah adalah gambar PNG
        $image_info = getimagesize($image);
        $image_type = $image_info[2];

        if ($image_type != IMAGETYPE_PNG) {
            echo "<p>File yang diunggah bukan gambar PNG. Silakan unggah gambar berformat PNG.</p>";
        } else {
            $img = imagecreatefrompng($image);
            $width = imagesx($img);
            $height = imagesy($img);

            $message_bin = '';
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    $b = $rgb & 0xFF;

                    $message_bin .= ($b & 1);
                }
            }

            // Mengonversi pesan biner ke teks
            $message = '';
            for ($i = 0; $i < strlen($message_bin); $i += 8) {
                $char = chr(bindec(substr($message_bin, $i, 8)));
                if ($char === "\0") break;
                $message .= $char;
            }

            $decrypted_message = $message; // Inisialisasi hasil dekripsi
        }
    } else {
        echo "<p>Silakan unggah gambar untuk mendekripsi pesan.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/gambar.css">
    <title>Bukti Bayar</title>
</head>
<body>
    <h1>Upload Bukti Bayar (PNG)</h1>
    <form action="gambar.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required><br>
        <textarea name="message" placeholder="Masukkan pesan untuk disisipkan" required></textarea><br>
        <button type="submit" name="upload">Enkripsi Gambar</button>
    </form>

    <h1>Dekripsi Bukti Bayar</h1>
    <form action="gambar.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required><br>
        <button type="submit" name="decrypt">Dekripsi Gambar</button>
    </form>

    <?php if ($decrypted_message): ?>
        <h2>Hasil Dekripsi</h2>
        <p>Pesan terdekripsi: <?= htmlspecialchars($decrypted_message) ?></p>
        <h2>Gambar Terdekripsi</h2>
        <img src="<?= $decrypted_image_path ?>" alt="Gambar terdekripsi" style="max-width: 100%; height: auto;" />
    <?php endif; ?>
    
    <div class="center">
        <a href="home.php"><button>Kembali ke Home</button></a>
    </div>
</body>
</html>
