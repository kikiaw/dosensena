<?php 
    require_once("koneksi.php");
    if(isset($_GET["c"])){
        $judul_berita = (string) $_GET["c"];
    }
                        
    $result = $conn->query("select berita.id_berita, berita.judul_berita, berita.waktu_berita, berita.gambar_berita, user.id_user, user.nama, user.gambar_user, kategori.id_kategori, kategori.nama_kategori from berita inner join kategori on berita.id_kategori = kategori.id_kategori inner join user on berita.id_user = user.id_user where berita.judul_berita like '%$judul_berita%' order by waktu_berita desc");
?>
<h4 class="text-left" id="judulAtas">Pencarian Berita Dengan Judul : <strong><?= $judul_berita; ?></strong></h4>
<?php
    while($data = $result->fetch_object()){ 
?>
<div class="content">
    <div class="postHeader">
<?php
    if($data->gambar_user === NULL) {
        $gambar_user = "favicon.png";
        echo "<img src=\"assets/img/logo/$gambar_user\" class=\"img-circle\">";
      
    }else{
        $gambar_user = $data->gambar_user; 
        echo "<img src=\"assets/img/user-picture/$gambar_user\" class=\"img-circle\">";
    }
?>
    <span><strong>explore</strong> oleh <strong><a href="kategori-user.php?u=<?= $data->id_user ?>" style="color:black;"><?= $data->nama ?></a></strong><br>
    <small><?= $data->waktu_berita ?> WITA</small></span>
    </div>
    <div class="postContent">
        <div class="subPostContent">
        <img src="assets/img/post-picture/<?= $data->gambar_berita ?>" class="img-responsive">
        </div>
        <h4 class="text-left"><strong><a href="berita.php?b=<?= $data->id_berita ?>"><?= $data->judul_berita ?></a></strong></h4>
    </div>
    <div class="postCategory"><a href="kategori.php?k=<?= $data->id_kategori ?>"><small><?= $data->nama_kategori ?></small></a></div>
</div>
   
<?php 
} 
    $result->free_result();      
  
?>
