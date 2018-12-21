<?php require_once("header.php"); ?> 
<?php
    // Menampilkan total berita
    $result = $conn->query("select * from komentar");
    $tot_komentar = $conn->affected_rows;

    $result->free_result();
?>
        <main>
<?php
    // Tampilkan pesan jika ada
    if(isset($pesan)){
        echo "<div class=\"alert alert-info\">$pesan</div>";
    }
?>
               <div id="inner-main">
                <ul>
                    <li style="background-color: purple"><span class="fa fa-comment fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="admin.php"><?= $tot_komentar ?><br><span>total komentar</span></a></li>
                </ul>
            <div class="new-news">
               <h3>All Komentar</h3>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Berita</th>
                        <th>Komentator</th>
                        <th>Komentar</th>
                        <th>Waktu</th>
                        <th>Action</th>
                    </tr>
<?php
    $i = 1;
    // Menampilkan berita terbaru
    $result = $conn->query("select komentar.id_komentar, komentar.isi_komentar, komentar.waktu_komentar, user.nama, berita.judul_berita, komentar.id_berita from komentar join user on komentar.id_user = user.id_user join berita on komentar.id_berita = berita.id_berita order by komentar.waktu_komentar");
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><a href="../berita.php?b=<?= $data->id_berita ?>" target="_blank"><strong><?= $data->judul_berita ?></strong></a></td>
                        <td><strong><?= $data->nama ?></strong></td>
                        <td><?= $data->isi_komentar ?></td>
                        <td><?= $data->waktu_komentar ?> WITA</td>
                        <td>
                            <form action="">
                                <span class="hapus"><a href="hapus.php?hap_kom=<?= $data->id_komentar ?>" class="fa fa-times"></a></span>
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
        </div>
        </main>
    </div>
</body>
</html>
