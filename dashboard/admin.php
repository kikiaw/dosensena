<?php require_once("header.php"); ?>
<?php
    // Menampilkan total berita
    $result = $conn->query("select * from admin");
    $tot_admin = $conn->affected_rows;

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
                    <li style="background-color: maroon"><span class="fa fa-user fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="admin.php"><?= $tot_admin ?><br><span>total admin</span></a></li>
                </ul>
            <div class="new-news">
               <h3>All Admin &nbsp;<a href="tambah-admin.php" class="btn btn-primary"><strong>Tambah Admin</strong></a></h3>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
<?php
    $i = 1;
    // Menampilkan berita terbaru
    $result = $conn->query("select * from admin");
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->username ?></td>
                        <td><?= $data->role ?></td>
                        <td><?= $data->last_login ?> WITA</td>
                        <td>
                            <form action="">
                                <span class="hapus"><a href="hapus.php?hap_adm=<?= $data->username ?>" class="fa fa-times"></a></span>
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
