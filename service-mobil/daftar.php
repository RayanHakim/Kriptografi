<?php
if (isset($_GET['message'])) {
    if ($_GET['message'] == "sudah_ada") {
        $msudah_ada = "Email sudah terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="src/daftar.css">
    <title>Kriptografi - Daftar Akun</title>
</head>

<body>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-5" style="width: 35%; box-shadow: 9px 10px 10px 4px #000;">
            <div class="text-center mb-3" id="head-card" style="font-family: 'Poppins', sans-serif;">
                <h1>Daftar</h1>
                <hr>
            </div>
            <div class="text-center">
                <p class="text-danger">
                    <?php
                    if (isset($_GET['message'])) {
                        if ($_GET['message'] == "sudah_ada") {
                            echo $msudah_ada;
                        }
                    }
                    ?>
                </p>
            </div>

            <form action="daftar_proses.php" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                </div>
                <br>
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-group input-group-lg mb-3">
                    <input id="email" type="text" class="form-control bg-opacity-10 fs-6" placeholder="honda@gmail.com" aria-label="Username" name="email" required>
                </div>
                <label for="password" class="form-label">Password</label>
                <div class="input-group input-group-lg mb-3">
                    <input id="password" type="password" class="form-control bg-opacity-10 fs-6" aria-label="password" name="password" required>
                </div>

                <!-- Tombol untuk Tampilkan/Sembunyikan Password -->
                <button type="button" class="btn btn-secondary mt-2" onclick="togglePasswordVisibility('password')">
                    Tampilkan/Sembunyikan Password
                </button>
                <br>
                <a href="index.php" class="btn btn-primary container-fluid fw-bold mt-3" style="font-family: 'Poppins', sans-serif;">Halaman Awal</a>
                <br>
                <br>
                <p style="opacity: 0.5; display:inline">Sudah punya akun? <a href="login.php">Login</a></p>
                
                <button type="submit" class="btn btn-danger container-fluid fw-bold mt-3" style="font-family: 'Poppins', sans-serif;">Daftar</button>
            </form>
        </div>
    </div>

    <script>
        const char = "!#$%&\\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~";
        let showed = false;
        let readyclicked = true;
        let value;

        function togglePasswordVisibility(passwordFieldId) {
            if (readyclicked) {
                const pass = document.getElementById(passwordFieldId);

                if (!showed) {
                    value = pass.value;
                    pass.value = pass.value.split("").map(() => "•").join("");
                    setTimeout(() => {
                        pass.type = "text";
                    }, 1);
                    for (let i = 0; i < value.length * 2 + 20; i++) {
                        setTimeout(() => {
                            pass.value = pass.value.split("").map((letter, index) => {
                                if (index <= (i - 20) / 2) return value[index];
                                else if (index <= i / 2) return char[Math.floor(Math.random() * 93)];
                                else return "•";
                            }).join("");
                        }, 25 * i);
                    }
                    pass.value = value
                    setTimeout(() => {
                        readyclicked = true
                    }, 25 * (value.length * 2 + 20) + 2)
                } else {
                    value = pass.value;
                    for (let i = 0; i < value.length * 2 + 20; i++) {
                        setTimeout(() => {
                            pass.value = pass.value.split("").map((letter, index) => {
                                if (index <= (i - 20) / 2) return "•";
                                else if (index <= i / 2) return char[Math.floor(Math.random() * 93)];
                                else return value[index];
                            }).join("");
                        }, 25 * i);
                    }
                    setTimeout(() => {
                        pass.type = "password";
                        pass.value = value;
                        readyclicked = true;
                    }, (value.length * 2 + 20) * 25 + 1);
                    setTimeout(() => {
                        readyclicked = true;
                    }, 25 * (value.length * 2 + 20) + 2);
                }

                readyclicked = false;
                showed = !showed;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>
