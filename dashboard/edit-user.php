<?php require_once("header.php"); ?> 
<?php
    // Tangkap id_user & buka edit user berdasarkan id_user
    if(isset($_GET["pro"])){
        
        $id_user = $_GET["pro"];
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
    }
?>
        <main>
          <div class="main-edit">
           <h3 id="judul-form-news"><strong>Update User</strong></h3>
<?php
    if($pesan_error !== "") echo "<div class=\"alert alert-danger\">$pesan_error</div>";
                        
?>
            <form class="form-vertical" action="" method="post" id="edit-berita" enctype="multipart/form-data">
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="email" placeholder="email@example.com" value="<?= $email ?>">
                </div>
            </div>
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="nama" placeholder="Nama User" value="<?= $nama ?>">
                </div>
            </div>
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Telepon</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="telepon" placeholder="" value="<?= $telepon ?>">
                </div>
            </div>
            <div class="form-inline form-news">
                <label class="col-sm-3 control-label">Tanggal Lahir</label>
                <div class="form-group col-sm-8">
                    <select name="tgl" class="form-control">
                    <?php
                        for($i = 1; $i <= 31; $i++){
                            if($i==$tgl){
                                echo "<option value = $i selected>";
                            }else{
                                echo "<option value = $i >";
                            }
                            echo str_pad($i,2,"0",STR_PAD_LEFT);
                            echo "</option>";
                        }
                    ?>
                    </select>
                    <select name="bln" id="" class="form-control">
                        <?php 
                            foreach ($arr_bln as $key => $value) {
                                if($key==$bln){
                                    echo "<option value=\"{$key}\" selected>{$value}</option>";
                                }else{
                                    echo "<option value=\"{$key}\">{$value}</option>";
                                } 
                            } 
                        ?>
                    </select>
                    <select name="thn" id="" class="form-control">
                        <?php
                            for($i = 1970; $i <= 2010; $i++) {
                               if($i==$thn){
                                       echo "<option value = $i selected>";
                                   }else{
                                       echo "<option value = $i >";
                                   }
                                   echo "$i </option>";
                               }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Jenis Kelamin</label>
                <div class="col-sm-8">
                    <select name="jenis_kelamin" class="form-control">
                    <option>--Jenis Kelamin--</option>
                    <option value="l" <?= $select_laki ?>>Laki-Laki</option>
                    <option value="p" <?= $select_perem ?>>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Alamat</label>
                <div class="col-sm-8">
                    <textarea name="alamat" cols="5" class="form-control" rows="9"><?= $alamat ?></textarea>
                </div>
            </div>
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Tempat Lahir</label>
                <div class="col-sm-8">
                    <textarea name="tempat_lahir" cols="5" class="form-control" rows="9"><?= $tempat_lahir ?></textarea>
                </div>
            </div>
            <div class="form-group form-news">
                <label class="col-sm-3 control-label">Asal</label>
                <div class="col-sm-8">
                    <textarea name="asal" cols="5" class="form-control" rows="9"><?= $asal ?></textarea>
                </div>
            </div>
            <div class="col-sm-8">
                <input type="hidden" name="id_user" value="<?= $id_user ?>">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary" name="edit_profil">Update</button>
            </div>
            </form>
            <div class="col-sm-4 col-sm-offset-3">
                <p id="eg2">Ingin mengganti gambar ? <button class="btn btn-default" id="eg">Edit Gambar</button></p>
                <form action="" method="post" enctype="multipart/form-data" id="edit-gambar">
                <div class="form-group form-news">
                    <label class="col-sm-12 control-label">Change News Image</label>
                    <div class="col-sm-8">
                        <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
                        <input type="file" name="gambar_user" accept="image/*">
                    </div>
                </div>
                <input type="hidden" name="id_berita" value="">
                <button type="submit" class="btn btn-primary" name="edit_gambar_user">Update Gambar</button>
                </form>
            </div>
            </div>
        </main>
    </div>
</body>
</html>