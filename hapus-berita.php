<?php
    // Jika halaman ini diakses langsung maka redirect ke halaman jurnalis
    if(!isset($_POST["h"])) header("Location: jurnalis.php");
    else{
        // Jika tidak maka jalankan query penghapusan berita & gambar yang ada di server
        require_once("koneksi.php");
        $id_berita = htmlentities(strip_tags($_POST["h"]));
        $id_berita = (string) $conn->real_escape_string($id_berita);
        
        $result = $conn->query("select * from berita where id_berita = '$id_berita'");
        $data = $result->fetch_object();
        $gambar_berita = $data->gambar_berita;
        $judul_berita = $data->judul_berita;
        $result->free_result();
        
        $result = $conn->query("delete from berita where id_berita = '$id_berita'");
        if($result){
            // Buat pesan berita berhasil diinput
            $pesan = "Berita dengan judul \"<b>$judul_berita</b>\" berhasil di hapus";
            $pesan = urlencode($pesan);
            
            unlink("assets/img/post-picture/$gambar_berita");
            header("Location: jurnalis.php?p=$pesan");
        }
    }
?>