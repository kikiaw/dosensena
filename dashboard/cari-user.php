<?php 
    // Panggil file koneksi & tangkap c
    require_once("../koneksi.php");
    if(isset($_GET["c"])) $nilai_cari = (string) $_GET["c"];
?>
              <div class="new-news">
               <h3>Pencarian User Dengan Nama : <strong><?= $nilai_cari ?></strong></h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Action</th>
                    </tr>
<?php
    // Tampilkan hasil berdasarkan input user
    $result = $conn->query("select * from user where nama like '%$nilai_cari%'");
    $i = 1;
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->nama ?></td>
                        <td><?= $data->email ?></td>
                        <td>
<?php 
    if($data->jenis_kelamin == "P") echo "Perempuan";
    else echo "Laki-laki";
?>
                       </td>
                        <td>
                            <form action="">
                                <span class="edit"><a href="edit-user.php?pro=<?= $data->id_user ?>">Edit</a></span>
                                <span class="hapus"><a href="hapus.php?hap_user=<?= $data->id_user ?>">Hapus</a></span>
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
