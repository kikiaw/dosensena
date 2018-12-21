<?php require_once("header.php"); ?>
<?php 
    // tampilkan jumlah kategori
    $result = $conn->query("select * from kategori");
    $tot_kategori = $conn->affected_rows;

    $result->free_result();
?>
        <main>
<?php
    // Ambil pesan jika ada
    if(isset($pesan)){
        echo "<div class=\"alert alert-info\">$pesan</div>";
    }
?>
            <top-main>
                <ul>
                    <li style="background-color: cornflowerblue"><span class="fa fa-tags fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="#"><?= $tot_kategori ?><br><span>Total Kategori</span></a></li>
                </ul>
            </top-main>
            <div class="new-news">
               <h3>All Kategori &nbsp;<a href="tambah-kategori.php" class="btn btn-primary"><strong>Tambah Kategori</strong></a></h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kategori</th>
                        <th>Action</th>
                    </tr>
<?php
    // Tampilkan semua kategori
    $result = $conn->query("select * from kategori order by id_kategori");
    $i = 1;
    while($data = $result->fetch_object()){
?>
                    <tr>
                       <td><?= $i ?></td>
                        <td><?= $data->nama_kategori ?></td>
                        <td>
                            <form action="">
                                <span class="edit"><a href="edit-kategori.php?k=<?= $data->id_kategori ?>" class="fa fa-pencil-square-o"></a></span>
                                <span class="hapus"><a href="hapus.php?hap_kat=<?= $data->id_kategori ?>" class="fa fa-times"></a></span>
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
        </main>
    </div>
</body>
</html>
