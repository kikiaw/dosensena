<?php 
    if(isset($_GET["button_log_in"])){

        // Saat tombol button di klik, maka form akan di validasi
        // Siapkan validasi untuk pencegahan HTML Injection & XSS
        $username = trim(htmlentities(strip_tags($_GET["username_log_in"])));
        $password = trim(htmlentities(strip_tags($_GET["password_log_in"])));

        // Siapkan pesan error
        $pesan_error = "";

        // Isi pesan error jika form kosong
        if(empty($username)) $pesan_error = "Username harus diisi <br>";
        elseif(empty($password)) $pesan_error .= "Password harus diisi <br>";

        // Koneksi ke database
        require_once("koneksi.php");

        // Validasi form untuk pencegahan SQL Injection & lakukan tipe casting
        $username = (string) $conn->real_escape_string($username);
        $password = (string) $conn->real_escape_string($password);

        // Enkripsi password
        $password_hash = sha1(md5($password));

        // Buat query pengecekan peserta ke database
        $result = $conn->query("select * from admin where username = '$username' and password = '$password_hash'");

        // Periksa jika username & passord tidak ditemukan
        if($result->num_rows == 0) $pesan_error .= "Username dan/atau Password Tidak Sesuai <br>";

        // Bebaskan memory saat tidak dipakai
        $result->free_result();

        // Putuskan koneksi ke database
        $conn->close();

        // Jika pesan error tidak ada maka buat Session nama & redirect ke beranda
        if($pesan_error === ""){
            session_start();
            $_SESSION["admin"] = $username;
            $_SESSION["role"] = $username;
            header("Location: dashboard");
        }
    }else{

        // Jika halaman signup pertama kali dibuka
        $pesan_error = "";
        $username = "";
        $password = "";
    }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>Explorasi Duniamu Disini | explore.id</title>
    <link rel="icon" href="assets/img/logo/favicon.png" type="image/x-icons">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/jQuery/jquery-3.1.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/app.js" defer></script>
  </head>
  <body id="login">
      <div id="container">
              <div id="inner-container">
                  <div id="loginHeader">
                      <img src="assets/img/logo/icon.png" class="">
                      <h3><em>Explorasi seluruh informasi yang ada <br> di sekitarmu melalui <strong>explore</strong></em></h3>
                  </div>
                  <?php
                    // Tampilkan pesan error jika ada
                    if($pesan_error !== "") echo "<div id=\"errorSignUp\" class=\"error-login\">$pesan_error</div>";
                  ?>
                  <form class="form-horizontal" action="" method="get">
                      <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" placeholder="Username" name="username_log_in" value="<?= $username ?>">
                        </div>
                      </div>
                      <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-6">
                          <input type="password" class="form-control" placeholder="Password" name="password_log_in" value="<?= $password ?>">
                        </div>
                      </div>
                      <div class="form-group form-group-lg">
                        <div class="col-sm-offset-4 col-sm-8">
                          <button type="submit" class="btn btn-default btn-lg" id="buttonLogin" name="button_log_in">Log in</button>
                        </div>
                      </div>
                    </form>
              </div>
      </div>
  </body>
</html>
