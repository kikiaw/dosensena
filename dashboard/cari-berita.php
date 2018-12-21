<?php 
    // Panggil File koneksi & tangkap c jika ada
    require_once("../koneksi.php");
    if(isset($_GET["c"])) $nilai_cari = (string) $_GET["c"];
?>
              <div class="new-news">
               <h3>Pencarian Berita Dengan Judul : <strong><?= $nilai_cari ?></strong></h3>
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
    // Menampilkan hasil berdasarkan input user
    $result = $conn->query("select berita.id_berita, berita.judul_berita, user.nama, kategori.nama_kategori, berita.waktu_berita, berita.tampil from berita inner join user on berita.id_user = user.id_user inner join kategori on berita.id_kategori = kategori.id_kategori where berita.judul_berita like '%$nilai_cari%' order by tampil desc");
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
                                <span class="edit"><a href="edit-berita.php?e=<?= $data->id_berita ?>">Edit</a></span>
                                <span class="hapus"><a href="hapus.php?hap_ber=<?= $data->id_berita ?>">Hapus</a></span>
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
