<?php require_once("header.php"); ?>
<?php require_once("aside-left.php"); ?>
<?php
    // Halaman Berita
    if(isset($_GET["b"])){
        $id_berita = $_GET["b"];

        // Buat query untuk tambah views
        $result = $conn->query("update berita set tampil = tampil + 1 where id_berita = $id_berita");

        // Buat query untuk mengambil data dari database
        $result = $conn->query("select berita.judul_berita, berita.waktu_berita, berita.gambar_berita, berita.isi_berita, user.nama, user.id_user, kategori.id_kategori, kategori.nama_kategori from berita inner join user on berita.id_user = user.id_user inner join kategori on berita.id_kategori = kategori.id_kategori where berita.id_berita = $id_berita");
        $data = $result->fetch_object();

        $waktu_berita = date("d-m-Y - H:i:s", strtotime($data->waktu_berita));

        $result->free_result();
    }
?>
                <main id="main" class="">
                    <h4 class="text-left" id="judulAtas"><strong><a href="berita.php?b=<?= $id_berita ?>"><?= $data->judul_berita ?></a></strong></h4>
                    <div class="content" id="berita">
                     <div class="postHeader">
                          <img src="./assets/img/logo/favicon.png" class="img-thumbnail">
                          <span><strong>explore</strong> oleh <strong><a href="kategori-user.php?u=<?= $data->id_user ?>" style="color:black;"><?= $data->nama ?></a></strong><br>
                          <small><?= $waktu_berita ?> WITA</small></span>
                      </div>
                      <div class="postContent">
                         <div class="subPostContent">
                              <img src="assets/img/post-picture/<?= $data->gambar_berita ?>" class="img-responsive">
                          </div>
                          <p><?= $data->isi_berita ?></p>
                      </div>
                      <div class="postCategory"><a href="kategori.php?k=<?= $data->id_kategori ?>"><small><?= $data->nama_kategori ?></small></a></div>
                    </div>
                    <div class="komentar">
                      <h4>Komentar</h4>
                      <hr>
                      <?php
                        $result = $conn->query("select * from komentar join berita using (id_berita) join user on komentar.id_user = user.id_user where komentar.id_berita = $id_berita order by waktu_komentar");

                        while ($data = $result->fetch_object()) {
                      ?>
                      <div class="komentar-inner">
                      <h5><strong><?= $data->nama ?></strong>&nbsp;&nbsp; <?= $data->waktu_komentar ?> WITA </h5>
                      <p><?= $data->isi_komentar ?></p>
                      </div>
                      <hr>
                      <?php
                        }

                        if(isset($_SESSION["nama"])){

                      ?>
                      <form class="" action="" method="post">
                        <textarea name="isi_komentar" rows="5" cols="5" class="form-control"></textarea><br>
                        <input type="hidden" name="id_berita" value="<?= $id_berita ?>">
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">
                        <input type="submit" name="button_komentar" value="Komentar" class="btn btn-primary">
                      </form>
                      <?php
                        }
                       ?>
                    </div>
                </main>

<?php require_once("aside-right.php"); ?>
