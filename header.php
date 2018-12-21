<?php 
    // Setting waktu di server
    date_default_timezone_set("Asia/Makassar");

    // Jalankan session & panggil file koneksi
    session_start();
    require_once("koneksi.php");

    // tangkap pesan jika ada
    if (isset($_GET["p"])) {
      $pesan = $_GET["p"];
    }

    // Jika session nama ada, ambil id user
    if(isset($_SESSION["nama"])){
        $email = $_SESSION["nama"];
        $result = $conn->query("select * from user where email = '$email'");
        $data = $result->fetch_object();
        $id_user = $data->id_user;

        $conn->query("update user set last_login = now() where email = '$email'");
        $result->free_result();
    }

    // Halaman Create News
    if(isset($_POST["publish_berita"])){

        // Buat validasi pencegahan HTML Injection & XSS
        $judul_berita = trim(htmlentities(strip_tags($_POST["judul_berita"])));
        $kategori_berita = trim(htmlentities(strip_tags($_POST["kategori_berita"])));
        $isi_berita = trim(htmlentities(strip_tags($_POST["isi_berita"])));

        // Siapkan pesan error
        $pesan_error = "";

        // Jika form kosong isi pesan error
        if(empty($judul_berita)) $pesan_error = "Judul berita wajib diisi <br>";
        if(empty($kategori_berita)) $pesan_error .= "Kategori berita wajib diisi <br>";
        if(empty($isi_berita)) $pesan_error .= "Isi berita wajib diisi <br>";

        // Siapkan validasi form upload
        $upload_error = $_FILES["gambar_berita"]["error"];
        if ($upload_error !== 0){

            // gambar gagal di upload siapkan pesan error
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
            // tidak ada error, masuk ke validasi file upload berikutnya
            // periksa apakah ada file dengan nama yg sama
            $nama_folder = "post-picture";
            $nama_file = $_FILES["gambar_berita"]["name"];
            $path_file = "assets/img/$nama_folder/$nama_file";

            // Cek apakah ada file dengan nama yang sama
            if (file_exists($path_file)) {
                $pesan_error .= "File dengan nama sama sudah ada di server <br>";
            }

            // cek apakah ukuran file tidak melebihi 2MB(2097152 byte)
            $ukuran_file = $_FILES["gambar_berita"]["size"];
            if ($ukuran_file > 2097152) {
                $pesan_error .= "Ukuran file melebihi 2MB <br>";
            }

            // cek apakah memang file gambar
            $check = getimagesize($_FILES["gambar_berita"]["tmp_name"]);
            if ($check === false){
                $pesan_error .= "Mohon upload file gambar (gif, png, atau jpg )";
            }
        }
        if($pesan_error === ""){
            // pindahkan file ke folder_upload
            $nama_folder = "post-picture";
            $tmp = $_FILES["gambar_berita"]["tmp_name"];
            $nama_file = $_FILES["gambar_berita"]["name"];
            move_uploaded_file($tmp, "assets/img/$nama_folder/$nama_file");

            // Buat pesan berita berhasil diinput
            $pesan = "Berita dengan judul \"<b>$judul_berita</b>\" berhasil di posting";
            $pesan = urlencode($pesan);

            // Validasi untuk pencegahan SQL Injection
            $judul_berita = (string) $conn->real_escape_string($judul_berita);
            $isi_berita = (string) $conn->real_escape_string($isi_berita);
            $kategori_berita = (string) $conn->real_escape_string($kategori_berita);

            // Buat query untuk menyimpan berita
            $result = $conn->query("insert into berita (judul_berita, isi_berita, id_kategori, waktu_berita, gambar_berita, id_user, tampil) values ('$judul_berita', '$isi_berita', '$kategori_berita', now(), '$nama_file', '$id_user', 0)");

            // Jika query berhasil redirect ke halaman beranda, jika tidak tampilkan error
            if($result) header("Location: beranda.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    }elseif(isset($_POST["edit_berita"])){
        // Halaman Edit Berita

        // Buat validasi pencegahan HTML Injection & XSS
        $judul_berita = trim(htmlentities(strip_tags($_POST["judul_berita"])));
        $kategori_berita = trim(htmlentities(strip_tags($_POST["kategori_berita"])));
        $isi_berita = trim(htmlentities(strip_tags($_POST["isi_berita"])));
        $id_berita = trim(htmlentities(strip_tags($_POST["id_berita"])));

        // Siapkan pesan error
        $pesan_error = "";

        // Jika form kosong isi pesan error
        if(empty($judul_berita)) $pesan_error = "Judul berita wajib diisi <br>";
        if(empty($kategori_berita)) $pesan_error .= "Kategori berita wajib diisi <br>";
        if(empty($isi_berita)) $pesan_error .= "Isi berita wajib diisi <br>";

        if($pesan_error === ""){

            // Buat pesan berita berhasil di update
            $pesan = "Berita dengan judul \"<b>$judul_berita</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            // Validasi untuk pencegahan SQL Injection
            $judul_berita = (string) $conn->real_escape_string($judul_berita);
            $isi_berita = (string) $conn->real_escape_string($isi_berita);
            $kategori_berita = (string) $conn->real_escape_string($kategori_berita);
            $id_berita = (int) $conn->real_escape_string($id_berita);

            // Buat query untuk update berita
            $result = $conn->query("update berita set judul_berita = '$judul_berita', isi_berita = '$isi_berita', id_kategori = '$kategori_berita', waktu_berita = now() where id_berita = $id_berita");

            // Jika query berhasil redirect ke halaman jurnalis, jika tidak tampilkan error
            if($result) header("Location: jurnalis.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }
    }elseif(isset($_POST["edit_gambar_berita"])){

        // Ambil id berita
        $id_berita = trim(htmlentities(strip_tags($_POST["id_berita"])));

        // Ambil data berita dari database
        $result = $conn->query("select * from berita where id_berita = $id_berita");
        $data = $result->fetch_object();
        $judul_berita = $data->judul_berita;
        $kategori_berita = $data->id_kategori;
        $isi_berita = $data->isi_berita;
        $gambar_berita = $data->gambar_berita;

        $result->free_result();

        $pesan_error = "";

        // Siapkan validasi form upload
        $upload_error = $_FILES["gambar_berita_edit"]["error"];
        if ($upload_error !== 0){

            // gambar gagal di upload siapkan pesan error
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
            // tidak ada error, masuk ke validasi file upload berikutnya
            // periksa apakah ada file dengan nama yg sama
            $nama_folder = "post-picture";
            $nama_file = $_FILES["gambar_berita_edit"]["name"];
            $path_file = "assets/img/$nama_folder/$nama_file";

            // Cek apakah ada file dengan nama yang sama
            if (file_exists($path_file)) {
                $pesan_error .= "File dengan nama sama sudah ada di server <br>";
            }

            // cek apakah ukuran file tidak melebihi 2MB(2097152 byte)
            $ukuran_file = $_FILES["gambar_berita_edit"]["size"];
            if ($ukuran_file > 2097152) {
                $pesan_error .= "Ukuran file melebihi 2MB <br>";
            }

            // cek apakah memang file gambar
            $check = getimagesize($_FILES["gambar_berita_edit"]["tmp_name"]);
            if ($check === false){
                $pesan_error .= "Mohon upload file gambar (gif, png, atau jpg )";
            }
        }
        if($pesan_error === ""){

            // Hapus gambar lama yang ada di server
            unlink("assets/img/post-picture/$gambar_berita");

            // pindahkan file ke folder_upload
            $nama_folder = "post-picture";
            $tmp = $_FILES["gambar_berita_edit"]["tmp_name"];
            $nama_file = $_FILES["gambar_berita_edit"]["name"];
            move_uploaded_file($tmp, "assets/img/$nama_folder/$nama_file");

            // Buat pesan gambar berita berhasil update
            $pesan = "Gambar dengan nama \"<b>$nama_file</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            // Buat query untuk menyimpan gambar berita
            $result = $conn->query("update berita set gambar_berita = '$nama_file' where id_berita = $id_berita");

            // Jika query berhasil redirect ke halaman jurnalis, jika tidak tampilkan error
            if($result) header("Location: jurnalis.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }

    }elseif(isset($_POST["edit_profil"])){

        // Validasi form dari HTML Injection & XSS
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

        // Siapkan pesan error
        $pesan_error = "";

        // Jika nama dan/atau email kosong
        if(empty($nama)) $pesan_error = "Nama wajib diisi";
        if(empty($email)) $pesan_error = "Email wajib diisi";

        // Jika pesan error kosong
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

            if($result) header("Location: jurnalis.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }

    }elseif(isset($_POST["edit_gambar_user"])){

        // Ambil id user & data dari database
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

        // Jika data jenis kelamin & tanggal lahir bulan ada
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

        // Siapkan pesan error
        $pesan_error = "";

        // Siapkan validasi form upload
        $upload_error = $_FILES["gambar_user"]["error"];
        if ($upload_error !== 0){

            // gambar gagal di upload siapkan pesan error
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
            // tidak ada error, masuk ke validasi file upload berikutnya
            // periksa apakah ada file dengan nama yg sama
            $nama_folder = "user-picture";
            $nama_file = $_FILES["gambar_user"]["name"];
            $path_file = "assets/img/$nama_folder/$nama_file";

            // Cek apakah ada file dengan nama yang sama
            if (file_exists($path_file)) {
                $pesan_error .= "File dengan nama sama sudah ada di server <br>";
            }

            // cek apakah ukuran file tidak melebihi 2MB(2097152 byte)
            $ukuran_file = $_FILES["gambar_user"]["size"];
            if ($ukuran_file > 2097152) {
                $pesan_error .= "Ukuran file melebihi 2MB <br>";
            }

            // cek apakah memang file gambar
            $check = getimagesize($_FILES["gambar_user"]["tmp_name"]);
            if ($check === false){
                $pesan_error .= "Mohon upload file gambar (gif, png, atau jpg )";
            }
        }
        if($pesan_error === ""){
            // pindahkan file ke folder_upload
            $nama_folder = "user-picture";
            $tmp = $_FILES["gambar_user"]["tmp_name"];
            $nama_file = $_FILES["gambar_user"]["name"];
            move_uploaded_file($tmp, "assets/img/$nama_folder/$nama_file");

            // Buat pesan gambar user berhasil update
            $pesan = "Gambar user dengan nama \"<b>$nama_file</b>\" berhasil di update";
            $pesan = urlencode($pesan);

            // Buat query untuk menyimpan gambar user
            $result = $conn->query("update user set gambar_user = '$nama_file' where id_user = $id_user");

            // Jika query berhasil redirect ke halaman jurnalis, jika tidak tampilkan error
            if($result) header("Location: jurnalis.php?p=$pesan");
            else die("Query Error ".$conn->error());
        }

    }elseif(isset($_POST["button_komentar"])){
      // Buat validasi pencegahan HTML Injection & XSS
      $id_berita = trim(htmlentities(strip_tags($_POST["id_berita"])));
      $id_user = trim(htmlentities(strip_tags($_POST["id_user"])));
      $isi_komentar = trim(htmlentities(strip_tags($_POST["isi_komentar"])));

      // Siapkan pesan error
      $pesan_error = "";

      // Jika form kosong isi pesan error
      if(empty($isi_komentar)) $pesan_error .= "Isi komentar wajib diisi <br>";

      if($pesan_error === ""){

          // Validasi untuk pencegahan SQL Injection
          $id_berita = (int) $conn->real_escape_string($id_berita);
          $id_user = (int) $conn->real_escape_string($id_user);
          $isi_komentar = (string) $conn->real_escape_string($isi_komentar);

          // Buat query untuk menyimpan berita
          $result = $conn->query("insert into komentar values (null, $id_user, '$isi_komentar', now(), $id_berita)");

          // Jika query berhasil redirect ke halaman beranda, jika tidak tampilkan error
          if(!$result) die("Query Error ".$conn->error());
      }
    }else{
        // Jika diakses pertama kali
        $pesan_error = "";
        $judul_berita = "";
        $isi_berita = "";
        $tgl=1;$bln="1";$thn=1970;
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
    <link rel="stylesheet" href="dashboard/css/font-awesome/css/font-awesome.min.css">
  </head>
  <body>
      <div id="bg-top"></div>
      <div id="sub-bg-top"></div>

      <div class="container">

              <header id="header">
                <div id="header-top">
                 <div id="logo">
                    <a href="beranda.php"><img src="assets/img/logo/icon.png" class="img-responsive"></a>
                 </div>

                 <div id="pencarian">
                    <form class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Cari Judul Berita" id="kolomCari">
                            <button type="submit" class="btn btn-primary" id="tombolCari"><span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </form>
                 </div>

                 <div id="loginKiri">
                    <div id="menuLogin">
                        <?php if(!isset($_SESSION["nama"])){ ?>
                         <a href="login.php">Log In / Sign Up</a>
                         <span> | </span>
                         <a href="login.php">Create News</a>
                         <?php
                         }else{
                            $result = $conn->query("select * from user where id_user = $id_user");
                            $data = $result->fetch_object();
                            echo "<a href=\"jurnalis.php\">$data->nama</a>";
                            $result->free_result();
                         ?>
                         <span> | </span>
                         <a href="create-news.php">Create News</a>
                         <span> | </span>
                         <a href="logout.php">Log Out</a>
                       <?php } ?>
                     </div>
                 </div>
                 </div>

                 <div id="header-bottom">
                 <nav id="navMenu">
                     <ul class="list-inline text-center">
                     <?php
                         // Menampilkan daftar kategori secara dinamis
                         //$result = $conn->query("select * from kategori order by id_kategori");
                         //while($data = $result->fetch_object()){
                             //echo "<li class=\"nav-item\"><a href=\"kategori.php?k=$data->id_kategori\" class=\"btn btn-default\">$data->nama_kategori</a></li>";
                         //}
                         //$result->free_result();
						 $result = $conn->query("select * from kategori order by id_kategori");
						if( !$result)
						  die($conn->error);

						$queryResult = array();

						while ($row = $result->fetch_object())
						{
							$queryResult[] = $row->present_tense;
						}
                     ?>
                     </ul>
                 </nav>
                </div>
              </header>
