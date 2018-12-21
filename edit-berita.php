<?php require_once("header.php"); ?>
<?php require_once("aside-left.php"); ?>
<?php
    // Halaman Edit Berita
    if(isset($_GET["e"])){
        $id_berita = $_GET["e"];
        $result = $conn->query("select * from berita where id_berita = $id_berita");
        $data = $result->fetch_object();

        $judul_berita = $data->judul_berita;
        $isi_berita = $data->isi_berita;
        $kategori_berita = $data->id_kategori;
    }
?>
                <main id="main" class="">
                   <h3 id="judul-form-news"><strong>Update News</strong></h3>
                   <?php
                        // Tampilkan pesan error jika ada
                        if($pesan_error !== "") echo "<div class=\"alert alert-danger\">$pesan_error</div>";

                    ?>
                    <form class="form-vertical" action="edit-berita.php" method="post" id="edit-berita" enctype="multipart/form-data">
                      <div class="form-group form-news">
                        <label class="col-sm-12 control-label">News Header</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="judul_berita" placeholder="Judul Berita" value="<?= $judul_berita; ?>">
                        </div>
                      </div>
                      <div class="form-group form-news">
                        <label class="col-sm-12 control-label">News Category</label>
                        <div class="col-sm-12">
                            <select name="kategori_berita" class="form-control">
                            <option value="">--Kategori Berita--</option>
                             <?php
                                // Menampilkan daftar kategori secara dinamis
                                $result = $conn->query("select * from kategori order by id_kategori");
                                while($data = $result->fetch_object()){
                                    $selected = ($data->id_kategori == $kategori_berita) ? "selected" : "";
                                    echo "<option value=\"$data->id_kategori\" $selected>$data->nama_kategori</option>";
                                }
                                $result->free_result();
                             ?>
                            </select>
                        </div>
                      </div>
                      <div class="form-group form-news">
                        <label class="col-sm-12 control-label">News Text</label>
                        <div class="col-sm-12">
                            <textarea name="isi_berita" cols="30" class="form-control" rows="9"><?= $isi_berita; ?></textarea>
                        </div>
                      </div>
                           <input type="hidden" name="id_berita" value="<?= $id_berita ?>">
                            <button type="reset" class="btn btn-default">Reset</button>
                            <button type="submit" class="btn btn-primary" name="edit_berita">Publish</button>
                    </form>
                    <br>
                    <div class="col-sm-12">
                        <p id="eg2">Ingin mengganti gambar ? <button class="btn btn-default" id="eg">Edit Gambar</button></p>
                        <form action="edit-berita.php" method="post" enctype="multipart/form-data" id="edit-gambar">
                            <div class="form-group form-news">
                                <label class="col-sm-12 control-label">Change News Image</label>
                                <div class="col-sm-12">
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
                                <input type="file" name="gambar_berita_edit" accept="image/*">
                                </div>
                            </div>
                            <input type="hidden" name="id_berita" value="<?= $id_berita ?>">
                            <button type="submit" class="btn btn-primary" name="edit_gambar_berita">Update Gambar</button>
                        </form>
                    </div>
              </main>
<?php require_once("aside-right.php"); ?>
