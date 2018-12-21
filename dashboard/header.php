<?php 
    // Atur zona waktu server
    date_default_timezone_set("Asia/Makassar");

    // Panggil file koneksi
    require_once("../koneksi.php");

    // Jalankan sesion & cek jika bukan admin redirect ke halaman utama
    session_start();
    if(!isset($_SESSION["admin"])) header("Location: ../index.php");

    $username = $_SESSION["admin"];
    $role = $_SESSION["role"];

    $conn->query("update admin set last_login = now() where username = '$username'");

    // Cek p jika ada
    if (isset($_GET["p"])) {
      $pesan = $_GET["p"];
    }

    // Jika diklik dari halaman edit berita
    if(isset($_POST["edit_berita"])){

        $judul_berita = trim(htmlentities(strip_tags($_POST["judul_berita"])));
        $kategori_berita = trim(htmlentities(strip_tags($_POST["kategori_berita"])));
        $isi_berita = trim(htmlentities(strip_tags($_POST["isi_berita"])));
        $id_berita = trim(htmlentities(strip_tags($_POST["id_berita"])));

        $pesan_error = "";

        if(empty($judul_berita)) $pesan_error = "Judul berita wajib diisi <br>";
        if(empty($kategori_berita)) $pesan_error .= "Kategori berita wajib diisi <br>";
        if(empty($isi_berita)) $pesan_error .= "Isi berita wajib diisi <br>";

        if($pesan_error === ""){

            $pesan = "Berita dengan judul \"<b>$judul_berita</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            $judul_berita = (string) $conn->real_escape_string($judul_berita);
            $isi_berita = (string) $conn->real_escape_string($isi_berita);
            $kategori_berita = (string) $conn->real_escape_string($kategori_berita);
            $id_berita = (int) $conn->real_escape_string($id_berita);

            $result = $conn->query("update berita set judul_berita = '$judul_berita', isi_berita = '$isi_berita', id_kategori = '$kategori_berita', waktu_berita = now() where id_berita = $id_berita");

            if($result) header("Location: berita.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika edit gambat berita di klik
    }elseif(isset($_POST["edit_gambar_berita"])){

        $id_berita = trim(htmlentities(strip_tags($_POST["id_berita"])));

        $result = $conn->query("select * from berita where id_berita = $id_berita");
        $data = $result->fetch_object();
        $judul_berita = $data->judul_berita;
        $kategori_berita = $data->id_kategori;
        $isi_berita = $data->isi_berita;
        $gambar_berita = $data->gambar_berita;

        $result->free_result();

        $pesan_error = "";

        $upload_error = $_FILES["gambar_berita_edit"]["error"];
        if ($upload_error !== 0){

            $arr_upload_error = array(
                1 => 'Ukuran file melewati batas maksimal',
                2 => 'Ukuran file melewati batas maksimal 1MB',
                3 => 'File hanya ter-upload sebagian',
                4 => 'Tidak ada file yang terupload',
                6 => 'Server Error',
                7 => 'Server Error',
                8 => 'Server Error',
            );
            $pesan_error .= $arr_upload_error[$upload_error];
        }else {
            $nama_folder = "post-picture";
            $nama_file = $_FILES["gambar_berita_edit"]["name"];
            $path_file = "../assets/img/$nama_folder/$nama_file";

            if (file_exists($path_file)) {
                $pesan_error .= "File dengan nama sama sudah ada di server <br>";
            }

            $ukuran_file = $_FILES["gambar_berita_edit"]["size"];
            if ($ukuran_file > 2097152) {
                $pesan_error .= "Ukuran file melebihi 2MB <br>";
            }

            $check = getimagesize($_FILES["gambar_berita_edit"]["tmp_name"]);
            if ($check === false){
                $pesan_error .= "Mohon upload file gambar (gif, png, atau jpg )";
            }
        }
        if($pesan_error === ""){

            unlink("../assets/img/post-picture/$gambar_berita");

            $nama_folder = "post-picture";
            $tmp = $_FILES["gambar_berita_edit"]["tmp_name"];
            $nama_file = $_FILES["gambar_berita_edit"]["name"];
            move_uploaded_file($tmp, "../assets/img/$nama_folder/$nama_file");

            $pesan = "Gambar dengan nama \"<b>$nama_file</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            $result = $conn->query("update berita set gambar_berita = '$nama_file' where id_berita = $id_berita");

            if($result) header("Location: berita.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika edit profil di klik
    }elseif(isset($_POST["edit_profil"])){

        $id_user = trim(htmlentities(strip_tags($_POST["id_user"])));
        $nama = trim(htmlentities(strip_tags($_POST["nama"])));
        $email = trim(htmlentities(strip_tags($_POST["email"])));
        $telepon = trim(htmlentities(strip_tags($_POST["telepon"])));
        $tgl = htmlentities(strip_tags(trim($_POST["tgl"])));
        $bln = htmlentities(strip_tags(trim($_POST["bln"])));
        $thn = htmlentities(strip_tags(trim($_POST["thn"])));
        $jenis_kelamin = htmlentities(strip_tags(trim($_POST["jenis_kelamin"])));
        $alamat = htmlentities(strip_tags(trim($_POST["alamat"])));
        $asal = htmlentities(strip_tags(trim($_POST["asal"])));
        $tempat_lahir = htmlentities(strip_tags(trim($_POST["tempat_lahir"])));

        $pesan_error = "";

        if(empty($nama)) $pesan_error = "Nama wajib diisi";
        if(empty($email)) $pesan_error = "Email wajib diisi";

        if($pesan_error === ""){

            $id_user = (int) $conn->real_escape_string($id_user);
            $nama = (string) $conn->real_escape_string($nama);
            $email = (string) $conn->real_escape_string($email);
            $telepon = (string) $conn->real_escape_string($telepon);
            $tgl = (string) $conn->real_escape_string($tgl);
            $bln = (string) $conn->real_escape_string($bln);
            $thn = (string) $conn->real_escape_string($thn);
            $jenis_kelamin = (string) $conn->real_escape_string($jenis_kelamin);
            $alamat = (string) $conn->real_escape_string($alamat);
            $asal = (string) $conn->real_escape_string($asal);
            $tempat_lahir = (string) $conn->real_escape_string($tempat_lahir);

            $tanggal_lahir = $thn."-".$bln."-".$tgl;

            $result = $conn->query("update user set nama = '$nama', email = '$email', telepon = '$telepon', alamat = '$alamat', jenis_kelamin = '$jenis_kelamin', asal = '$asal', tempat_lahir = '$tempat_lahir', tanggal_lahir = '$tanggal_lahir' where id_user = $id_user");

            $pesan = "User dengan nama \"<b>$nama</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            if($result) header("Location: user.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika edit gambar user diklik
    }elseif(isset($_POST["edit_gambar_user"])){

        $id_user = trim(htmlentities(strip_tags($_POST["id_user"])));
        $result = $conn->query("select * from user where id_user = $id_user");
        $data = $result->fetch_object();

        $email = $data->email;
        $nama = $data->nama;
        $alamat = $data->alamat;
        $telepon = $data->telepon;
        $asal = $data->asal;
        $jenis_kelamin = $data->jenis_kelamin;
        $tempat_lahir = $data->tempat_lahir;
        $tgl = substr($data->tanggal_lahir,8,2);
        $bln = substr($data->tanggal_lahir,5,2);
        $thn = substr($data->tanggal_lahir,0,4);

        $result->free_result();

        $select_laki = "";
        $select_perem = "";
        switch($jenis_kelamin){
            case "l" : $select_laki = "selected"; break;
            case "p" : $select_perem = "selected"; break;
        }

        $arr_bln = array( "1"=>"Januari",
                    "2"=>"Februari",
                    "3"=>"Maret",
                    "4"=>"April",
                    "5"=>"Mei",
                    "6"=>"Juni",
                    "7"=>"Juli",
                    "8"=>"Agustus",
                    "9"=>"September",
                    "10"=>"Oktober",
                    "11"=>"Nopember",
                    "12"=>"Desember" );

        $pesan_error = "";

        $upload_error = $_FILES["gambar_user"]["error"];
        if ($upload_error !== 0){

            $arr_upload_error = array(
                1 => 'Ukuran file melewati batas maksimal',
                2 => 'Ukuran file melewati batas maksimal 1MB',
                3 => 'File hanya ter-upload sebagian',
                4 => 'Tidak ada file yang terupload',
                6 => 'Server Error',
                7 => 'Server Error',
                8 => 'Server Error',
            );
            $pesan_error .= $arr_upload_error[$upload_error];
        }else {
            $nama_folder = "user-picture";
            $nama_file = $_FILES["gambar_user"]["name"];
            $path_file = "../assets/img/$nama_folder/$nama_file";

            if (file_exists($path_file)) {
                $pesan_error .= "File dengan nama sama sudah ada di server <br>";
            }

            $ukuran_file = $_FILES["gambar_user"]["size"];
            if ($ukuran_file > 2097152) {
                $pesan_error .= "Ukuran file melebihi 2MB <br>";
            }

            $check = getimagesize($_FILES["gambar_user"]["tmp_name"]);
            if ($check === false){
                $pesan_error .= "Mohon upload file gambar (gif, png, atau jpg )";
            }
        }
        if($pesan_error === ""){
            $nama_folder = "user-picture";
            $tmp = $_FILES["gambar_user"]["tmp_name"];
            $nama_file = $_FILES["gambar_user"]["name"];
            move_uploaded_file($tmp, "../assets/img/$nama_folder/$nama_file");

            $pesan = "Gambar user dengan nama \"<b>$nama_file</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            $result = $conn->query("update user set gambar_user = '$nama_file' where id_user = $id_user");

            if($result) header("Location: user.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika edit kategori di klik
    }elseif(isset($_POST["edit_kategori"])){
        $nama_kategori = trim(htmlentities(strip_tags($_POST["nama_kategori"])));
        $id_kategori = trim(htmlentities(strip_tags($_POST["id_kategori"])));

        $pesan_error = "";

        if(empty($nama_kategori)) $pesan_error = "Nama kategori harus diisi <br>";

        if($pesan_error === ""){
            $pesan = "Kategori dengan nama \"<b>$nama_kategori</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            $nama_kategori = (string) $conn->real_escape_string($nama_kategori);
            $id_kategori = (int) $conn->real_escape_string($id_kategori);

            $result = $conn->query("update kategori set nama_kategori = '$nama_kategori' where id_kategori = $id_kategori");
            if($result) header("Location: kategori.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika tambah kategori di klik
    }elseif(isset($_POST["tambah_kategori"])){
        $nama_kategori = trim(htmlentities(strip_tags($_POST["nama_kategori"])));

        $pesan_error = "";

        if(empty($nama_kategori)) $pesan_error = "Nama kategori harus diisi <br>";

        if($pesan_error === ""){
            $pesan = "Kategori dengan nama \"<b>$nama_kategori</b>\" berhasil di tambah";
            $pesan = urlencode($pesan);

            $nama_kategori = (string) $conn->real_escape_string($nama_kategori);

            $result = $conn->query("insert into kategori set nama_kategori = '$nama_kategori'");
            if($result) header("Location: kategori.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika halaman diakses pertama kali
  }elseif(isset($_POST["tambah_admin"])){
        $username = trim(htmlentities(strip_tags($_POST["username"])));
        $password = trim(htmlentities(strip_tags($_POST["password"])));
        $role = trim(htmlentities(strip_tags($_POST["role"])));

        $pesan_error = "";

        if(empty($username)) $pesan_error = "Username harus diisi <br>";
        if(empty($password)) $pesan_error = "Password harus diisi <br>";
        if(empty($role)) $pesan_error = "Role harus diisi <br>";

        if($pesan_error === ""){
            $pesan = "Username dengan nama \"<b>$username</b>\" berhasil di tambah";
            $pesan = urlencode($pesan);

            $username = (string) $conn->real_escape_string($username);
            $password = (string) $conn->real_escape_string($password);
            $password_hash = sha1(md5($password));
            $role = (string) $conn->real_escape_string($role);

            $result = $conn->query("insert into admin set username = '$username', password = '$password_hash', role = '$role'");
            if($result) header("Location: admin.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    // Jika halaman diakses pertama kali
  }else{
        $pesan_error = "";
        $judul_berita = "";
        $isi_berita = "";
        $tgl=1;$bln="1";$thn=1970;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/highcharts.js" defer></script>
    <script src="js/app.js" defer></script>
</head>
<body>
    <div id="container">
        <aside>
           <div class="admin-img">
               <img src="img/favicon.png">
               <h3>Admin Dasboard</h3>
           </div>
            <ul>
                <li><span class="fa fa-tachometer fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="beranda.php">Dasboard</a></li>
                <li><span class="fa fa-newspaper-o fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="berita.php">Data Berita</a></li>
                <li><span class="fa fa-users fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="user.php">Data User</a></li>
                <li><span class="fa fa-tags fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="kategori.php">Data Kategori</a></li>
                <?php
                  if($role === "admin"){
                ?>
                <li><span class="fa fa-user fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="admin.php">Data Admin</a></li>
                <?php
                  }
                 ?>
                 <li><span class="fa fa-comment fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="komentar.php">Data Komentar</a></li>
                <li><span class="fa fa-file-pdf-o fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="print-laporan.php">Laporan</a></li>
                <li><span class="fa fa-sign-out fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="logout.php">Log Out</a></li>
            </ul>
        </aside>
