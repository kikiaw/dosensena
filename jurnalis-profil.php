<?php require_once("header.php"); ?> 
<?php require_once("aside-left.php"); ?>
<?php
    // Halaman Jurnalis Profil
    if(isset($_GET["pro"])){

        // Ambil data dari Query String & Database
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
    }
?>
                <main id="main" class="">
                   <h3 id="judul-form-news"><strong>Update My Profil</strong></h3>
                   <?php
                        // Tampilkan pesan error jika ada
                        if($pesan_error !== "") echo "<div class=\"alert alert-danger\">$pesan_error</div>";

                    ?>
                    <form class="form-vertical" action="jurnalis-profil.php" method="post" id="edit-berita" enctype="multipart/form-data">
                      <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Nama</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="nama" value="<?= $nama; ?>">
                        </div>
                      </div>
                       <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Email</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="email" value="<?= $email; ?>">
                        </div>
                      </div>
                       <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Telepon</label>
                        <div class="col-sm-12">
                          <input type="tel" class="form-control" name="telepon" value="<?= $telepon; ?>">
                        </div>
                      </div>
                       <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Tanggal Lahir</label>
                        <div class="col-sm-12">
                            <select name="tgl" id="tgl">
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
                            <select name="bln">
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
                            <select name="thn">
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
                        <label class="col-sm-12 control-label">Jenis Kelamin</label>
                        <div class="col-sm-12">
                            <select name="jenis_kelamin" class="form-control">
                            <option>--Jenis Kelamin--</option>
                            <option value="l" <?= $select_laki ?>>Laki-Laki</option>
                            <option value="p" <?= $select_perem ?>>Perempuan</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Alamat</label>
                        <div class="col-sm-12">
                            <textarea name="alamat" cols="30" class="form-control" rows="4"><?= $alamat; ?></textarea>
                        </div>
                      </div>
                       <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Asal</label>
                        <div class="col-sm-12">
                            <textarea name="asal" cols="30" class="form-control" rows="2"><?= $asal; ?></textarea>
                        </div>
                      </div>
                       <div class="form-group form-news">
                        <label class="col-sm-12 control-label">Tempat Lahir</label>
                        <div class="col-sm-12">
                            <textarea name="tempat_lahir" cols="30" class="form-control" rows="2"><?= $tempat_lahir; ?></textarea>
                        </div>
                      </div>
                           <input type="hidden" name="id_user" value="<?= $id_user ?>">
                            <button type="reset" class="btn btn-default">Reset</button>
                            <button type="submit" class="btn btn-primary" name="edit_profil">Update</button>
                    </form>
                    <br>
                    <div class="col-sm-12">
                        <p id="eg2">Ingin mengganti gambar Profil ? <button class="btn btn-default" id="eg">Edit Gambar</button></p>
                        <form action="jurnalis-profil.php" method="post" enctype="multipart/form-data" id="edit-gambar">
                            <div class="form-group form-news">
                                <label class="col-sm-12 control-label">Change Profil Image</label>
                                <div class="col-sm-12">
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
                                <input type="file" name="gambar_user" accept="image/*">
                                </div>
                            </div>
                            <input type="hidden" name="id_user" value="<?= $id_user ?>">
                            <button type="submit" class="btn btn-primary" name="edit_gambar_user">Update Gambar</button>
                        </form>
                    </div>
              </main>
<?php require_once("aside-right.php"); ?>
