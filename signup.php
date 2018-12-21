<?php 
    if(isset($_POST["button_sign_up"])){
        
        // Saat tombol button di klik, maka form akan di validasi
        // Siapkan validasi untuk pencegahan HTML Injection & XSS
        $nama = trim(htmlentities(strip_tags($_POST["nama_sign_up"])));
        $email = trim(htmlentities(strip_tags($_POST["email_sign_up"])));
        $password = trim(htmlentities(strip_tags($_POST["password_sign_up"])));
        $retype_password = trim(htmlentities(strip_tags($_POST["retype_password_sign_up"])));
        
        // Siapkan pesan error
        $pesan_error = "";
        
        // Isi pesan error jika form kosong & password tidak ditulis 2 kali
        if(empty($nama)) $pesan_error = "Nama harus diisi <br>";
        if(empty($email)) $pesan_error .= "Email harus diisi <br>";
        if(empty($password)) $pesan_error .= "Password harus diisi <br>";
        if(empty($retype_password)) $pesan_error .= "Retype Password harus diisi <br>";
        if($password !== $retype_password) $pesan_error .= "Password harus ditulis 2 kali <br>";
        
        // Jika pesan error kosong buat query ke dabatabase
        if($pesan_error === ""){
        
            // Koneksi ke database
            require_once("koneksi.php");
            
            // Validasi form untuk pencegahan SQL Injection & lakukan tipe casting
            $nama = (string) $conn->real_escape_string($nama);
            $email = (string) $conn->real_escape_string($email);
            $password = (string) $conn->real_escape_string($password);
            $retype_password = (string) $conn->real_escape_string($retype_password);
            
            // Enkripsi password
            $password_hash = sha1(md5($password));
            
            // Buat query pendaftaran peserta ke database
            $result = $conn->query("insert into user (nama, email, password) values ('$nama', '$email', '$password_hash')");
            
            // Putuskan koneksi database & redirect ke halaman login
            $conn->close();
            header("Location: login.php");
        }
        
    }else{
        
        // Jika halaman signup pertama kali dibuka
        $pesan_error = "";
        $nama = "";
        $email = "";
        $password = "";
        $retype_password = "";
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
                    if($pesan_error !== "") echo "<h4 id=\"errorSignUp\" class=\"error-login\">$pesan_error</h4>";
                  ?>
                  <form class="form-horizontal" action="signup.php" method="post">
                      <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Nama Lengkap</label>
                        <div class="col-sm-6">
                          <input type="text" name="nama_sign_up" class="form-control" placeholder="Nama Lengkap" id="namaSignUp" value="<?= $nama; ?>">
                        </div>
                      </div>
                      <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-6">
                          <input type="email" name="email_sign_up" class="form-control" placeholder="Email" id="emailSignUp" value="<?= $email; ?>">
                        </div>
                      </div>
                      <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-6">
                          <input type="password" name="password_sign_up" class="form-control" placeholder="Password" id="passwordSignUp" value="<?= $password; ?>">
                        </div>
                      </div>
                      <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Retype Password</label>
                        <div class="col-sm-6">
                          <input type="password" name="retype_password_sign_up" class="form-control" placeholder="Retype Password" id="retypePasswordSignUp" value="<?= $retype_password; ?>">
                        </div>
                      </div>
                      <div class="form-group form-group-lg">
                        <div class="col-sm-offset-4 col-sm-8">
                          <button type="submit" name="button_sign_up" class="btn btn-default btn-lg buttonSignUp" id="buttonLogin">Sign Up</button>
                        </div>
                      </div>
                    </form>
              </div>
      </div>
  </body>
</html>