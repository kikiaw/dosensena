<?php require_once("header.php"); ?>
<?php
    // Tampilkan total user
    $result = $conn->query("select * from user");
    $tot_user = $conn->affected_rows;

    $result->free_result();
?>
        <main>
<?php
    if(isset($pesan)){
        echo "<div class=\"alert alert-info\">$pesan</div>";
    }
?>
           <div id="pencarian">
            <form class="form-inline form-lg">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Cari Nama User" id="cariUser">
                     <button type="submit" class="btn btn-primary" id="tombolCari"><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </form>
        </div>
           <div id="inner-main">
            <top-main>
                <ul>
                    <li><span class="fa fa-user fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="user.php"><?= $tot_user ?><br><span>total User</span></a></li>
                </ul>
            </top-main>
            <div class="new-news">
               <h3><a href="#">Top 10 User</a></h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Total Berita</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
<?php
    // Tampilkan user berdasarkan total tampil berita
    $result = $conn->query("select count(berita.id_berita) as tot_berita_user, berita.id_user, user.nama, user.email, user.last_login from berita inner join user on berita.id_user = user.id_user group by id_user order by tot_berita_user desc limit 10");
    $i = 1;
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->nama ?></td>
                        <td><?= $data->email ?></td>
                        <td><?= $data->tot_berita_user ?> Berita</td>
                        <td><?= $data->last_login ?> WITA</td>
                        <td>
                            <form action="">
                                <span class="edit"><a href="edit-user.php?pro=<?= $data->id_user ?>" class="fa fa-pencil-square-o"></a></span>
                                <span class="hapus"><a href="hapus.php?hap_user=<?= $data->id_user ?>" class="fa fa-times"></a></span>
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
               <h3><a href="#">All User</a></h3>
                <table>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
<?php
    // Buat pagination
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

    // Tampilkan semua user
    $result = $conn->query("select * from user order by id_user desc limit $posisi,$batas");
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $data->nama ?></td>
                        <td><?= $data->email ?></td>
                        <td>
<?php
    if($data->jenis_kelamin == "P") echo "Perempuan";
    else echo "Laki-laki";
?>
                       </td>
                       <td><?= $data->last_login ?></td>
                        <td>
                            <form action="">
                                <span class="edit"><a href="edit-user.php?pro=<?= $data->id_user ?>" class="fa fa-pencil-square-o"></a></span>
                                <span class="hapus"><a href="hapus.php?hap_user=<?= $data->id_user ?>" class="fa fa-times"></a></span>
                            </form>
                        </td>
                    </tr>
<?php
    }
    $result->free_result();
    $result = $conn->query("select * from user");
    $data = $result->fetch_object();
    $jumlah = $result->num_rows;

    $jumlah_hal = ceil($jumlah/$batas);
?>
                </table>
                <div class="paging">
<?php
    if($halaman > 1){
        $link = $halaman-1;
        $prev = "<span><a href=\"user.php?hal=$link\">Sebelumnya</a></span>";
    }else{
        $prev = "";
    }

    if($halaman < $jumlah_hal){
        $link = $halaman+1;
        $next = "<span><a href=\"user.php?hal=$link\">Selanjutnya</a></span>";
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
