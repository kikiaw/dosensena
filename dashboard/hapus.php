<?php 
    // Jika berita dihapus
    if(isset($_GET["hap_ber"])){
        require_once("../koneksi.php");

        $id_berita = $_GET["hap_ber"];

        $result = $conn->query("select * from berita where id_berita = $id_berita");
        $data = $result->fetch_object();
        $gambar_berita = $data->gambar_berita;
        $judul_berita = $data->judul_berita;
        $result->free_result();

        $result = $conn->query("delete from berita where id_berita = $id_berita");
        if($result){
            $pesan = "Berita dengan judul \"<b>$judul_berita</b>\" berhasil di hapus";
            $pesan = urlencode($pesan);

            unlink("../assets/img/post-picture/$gambar_berita");
            header("Location: beranda.php?p=$pesan");
        }
    // Jika user dihapus
    }elseif(isset($_GET["hap_user"])){
        require_once("../koneksi.php");

        $id_user = $_GET["hap_user"];

        $result = $conn->query("select * from user where id_user = $id_user");
        $data = $result->fetch_object();
        $gambar_user = $data->gambar_user;
        $nama_user = $data->nama;
        $result->free_result();

        $result = $conn->query("delete from user where id_user = $id_user");
        if($result){
            $pesan = "User dengan nama \"<b>$nama_user</b>\" berhasil di hapus";
            $pesan = urlencode($pesan);

            unlink("../assets/img/user-picture/$gambar_user");
            header("Location: beranda.php?p=$pesan");
        }
    // Jika kategori dihapus
    }elseif(isset($_GET["hap_kat"])){
        require_once("../koneksi.php");

        $id_kategori = $_GET["hap_kat"];

        $result = $conn->query("select * from kategori where id_kategori = $id_kategori");
        $data = $result->fetch_object();
        $nama_kategori = $data->nama_kategori;
        $result->free_result();

        $result = $conn->query("delete from kategori where id_kategori = $id_kategori");
        if($result){
            $pesan = "Kategori dengan nama \"<b>$nama_kategori</b>\" berhasil di hapus";
            $pesan = urlencode($pesan);

            header("Location: beranda.php?p=$pesan");
        }
    // Jika tidak redirect ke halaman index
  }elseif(isset($_GET["hap_adm"])){
    require_once("../koneksi.php");

    $username = $_GET["hap_adm"];

    $result = $conn->query("select * from admin where username = '$username'");
    $data = $result->fetch_object();
    $username = $data->username;
    $result->free_result();

    $result = $conn->query("delete from admin where username = '$username'");
    if($result){
        $pesan = "Admin dengan Username \"<b>$username</b>\" berhasil di hapus";
        $pesan = urlencode($pesan);

        header("Location: beranda.php?p=$pesan");
    }
  }elseif(isset($_GET["hap_kom"])){
    require_once("../koneksi.php");

    $id_komentar = $_GET["hap_kom"];

    $result = $conn->query("delete from komentar where id_komentar = $id_komentar");
    if($result){
        header("Location: komentar.php");
    }
  }else{
        header("Location: index.php");
    }
?>
