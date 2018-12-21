<?php require_once("header.php"); ?> 
<?php
    // Menampilkan total berita
    $result = $conn->query("select * from berita");
    $tot_berita = $conn->affected_rows;

    $result->free_result();
?>
        <main>
<?php
    // Tampilkan pesan jika ada
    if(isset($pesan)){
        echo "<div class=\"alert alert-info\">$pesan</div>";
    }
?>
        <div id="pencarian">
            <form class="form-inline form-lg">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Cari Judul Berita" id="cariBerita">
                     <button type="submit" class="btn btn-primary" id="tombolCari"><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </form>
        </div>
               <div id="inner-main">
                <ul>
                    <li style="background-color: teal"><span class="fa fa-newspaper-o fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="berita.php"><?= $tot_berita ?><br><span>total berita</span></a></li>
                </ul>
            <div class="new-news">
               <h3><a href="#">Top 10 News</a></h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Judul Berita</th>
                        <th>User</th>
                        <th>Kategori</th>
                        <th>Waktu</th>
                        <th>Tampil</th>
                        <th>Action</th>
                    </tr>
<?php
    // Menampilkan Berita dengan Tampil terbanyak

    $result = $conn->query("select berita.id_berita, berita.judul_berita, user.nama, kategori.nama_kategori, berita.waktu_berita, berita.tampil from berita inner join user on berita.id_user = user.id_user inner join kategori on berita.id_kategori = kategori.id_kategori order by tampil desc limit 10");
    $i = 1;
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><a href="../berita.php?b=<?= $data->id_berita ?>" target="_blank"><?= $data->judul_berita ?></a></td>
                        <td><?= $data->nama ?></td>
                        <td><?= $data->nama_kategori ?></td>
                        <td><?= $data->waktu_berita ?> WITA</td>
                        <td><?= $data->tampil ?> Kali</td>
                        <td>
                            <form action="">
                                <span class="edit"><a href="edit-berita.php?e=<?= $data->id_berita ?>" class="fa fa-pencil-square-o"></a></span>
                                <span class="hapus"><a href="hapus.php?hap_ber=<?= $data->id_berita ?>" class="fa fa-times"></a></span>
                            </form>
                        </td>
                    </tr>
<?php
    $i++;
    }
    $result->free_result();
?>
                </table>
            </div>
            <div class="new-news">
               <h3><a href="#">Recent News</a></h3>
                <table>
                    <tr>
                        <th>Judul Berita</th>
                        <th>User</th>
                        <th>Kategori</th>
                        <th>Waktu</th>
                        <th>Tampil</th>
                        <th>Action</th>
                    </tr>
<?php
    // Buat pegination
    $batas = 10;
    if(isset($_GET["hal"])){
        $halaman = (int) $_GET["hal"];
    }
    if(empty($halaman)){
        $posisi = 0;
        $halaman = 1;
    }else{
        $posisi = ($halaman - 1) * $batas;
    }

    // Menampilkan berita terbaru
    $result = $conn->query("select berita.id_berita, berita.judul_berita, user.nama, kategori.nama_kategori, berita.waktu_berita, berita.tampil from berita inner join user on berita.id_user = user.id_user inner join kategori on berita.id_kategori = kategori.id_kategori order by waktu_berita desc limit $posisi,$batas");
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><a href="../berita.php?b=<?= $data->id_berita ?>" target="_blank"><?= $data->judul_berita ?></a></td>
                        <td><a href="#"><?= $data->nama ?></a></td>
                        <td><a href="#"><?= $data->nama_kategori ?></a></td>
                        <td><?= $data->waktu_berita ?> WITA</td>
                        <td><?= $data->tampil ?> Kali</td>
                        <td>
                            <form action="">
                                <span class="edit"><a href="edit-berita.php?e=<?= $data->id_berita ?>" class="fa fa-pencil-square-o"></a></span>
                                <span class="hapus"><a href="hapus.php?hap_ber=<?= $data->id_berita ?>" class="fa fa-times"></a></span>
                            </form>
                        </td>
                    </tr>
<?php
    }
    $result->free_result();
    $result = $conn->query("select * from berita");
    $data = $result->fetch_object();
    $jumlah = $result->num_rows;

    $jumlah_hal = ceil($jumlah/$batas);
?>
                </table>
                <div class="paging">
<?php
    if($halaman > 1){
        $link = $halaman-1;
        $prev = "<span><a href=\"berita.php?hal=$link\">Sebelumnya</a></span>";
    }else{
        $prev = "";
    }

    if($halaman < $jumlah_hal){
        $link = $halaman+1;
        $next = "<span><a href=\"berita.php?hal=$link\">Selanjutnya</a></span>";
    }else{
        $next = "";
    }

    echo $prev;
    echo $next;
?>
                </div>
            </div>
        </div>
        </main>
    </div>
</body>
</html>
