<?php require_once("header.php"); ?> 
<?php
    // Tangkap id_kategori & buka edit kategori berdasarkan id_kategori
    if(isset($_GET["k"])){
        $id_kategori = $_GET["k"];
        $result = $conn->query("select * from kategori where id_kategori = $id_kategori");
        $data = $result->fetch_object();
        $nama_kategori = $data->nama_kategori;
    }
?>
        <main>
          <div class="main-edit">
           <h3 id="judul-form-news"><strong>Update Kategori</strong></h3>
<?php
    if($pesan_error !== "") echo "<div class=\"alert alert-danger\">$pesan_error</div>";
                        
?>
            <form class="form-vertical" action="" method="post" id="edit-berita" enctype="multipart/form-data">
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Nama Kategori</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="nama_kategori" placeholder="" value="<?= $nama_kategori ?>">
                </div>
            </div>
            <div class="col-sm-8">
                <input type="hidden" name="id_kategori" value="<?= $id_kategori ?>">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary" name="edit_kategori">Update</button>
            </div>
            </form>            
            </div>
        </main>
    </div>
</body>
</html>