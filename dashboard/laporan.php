<?php
    // Atur zona waktu server
    date_default_timezone_set("Asia/Makassar");
    
    $tgl_sekarang = date("F Y");

    // Panggil file koneksi
    require_once("../koneksi.php");

    // Jalankan sesion & cek jika bukan admin redirect ke halaman utama
    session_start();
    if(!isset($_SESSION["admin"])) header("Location: ../index.php");



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
    *{
    margin: 0;
    padding: 0;
}
ul, li{
    list-style: none;
}
a{
    text-decoration: none;
    color: inherit;
}
input[type="text"], textarea, select, input[type="submit"]{
    border: 1px solid #decdcd;
    border-radius: 3px;
}
input[type="submit"]{
    padding: 5px;
}
.alert-info, .alert-danger{
    margin: 0 20px 20px 20px;
    text-align: center;
}


#container{
    display: flex;
    height: 100vh;
}
aside{
    background-color: #041c1c;
    flex: 1 0 0;
    color: white;
    font-family: sans-serif;
}
main{
    background-color: #efefef;
    flex: 5 0 0;
    padding: 25px 0;
    overflow-y: scroll;
}
main #pencarian{
    margin: 10px 25px 20px 25px;    
}
main #pencarian .form-group{
    display: flex;
    justify-content: center;
}
main #pencarian #cariBerita, main #pencarian #cariUser{
    flex: 0 1 100%;
}

aside ul{
    padding: 10px;
    text-transform: uppercase;
    font-weight: bold;
}
aside ul li{
    padding: 10px;
    border-radius: 7px;
    margin: 10px 0;
    transition-duration: 0.3s;
    transition-timing-function: ease-in-out;
}
aside ul li.active{
    background-color: #094141;
}
aside ul li:hover{
    background-color: #094141;
}
aside ul li a:hover{
    color: white;
}
aside .admin-img{
    padding: 10px;
    text-align: center;
    border-bottom: 3px solid white;
}
aside .admin-img img{
    background-color: white;
    border-radius: 50%;
    padding: 5px;
    width: 75px;
}
aside h3{
    margin: 10px;
}


main ul{
    padding: 0 20px 20px 20px;
    display: flex;
    text-align: center;
}
main li{
    padding: 20px;
    font-family: sans-serif;
    text-transform: uppercase;
    flex: 1 1 100%;
    margin: 0 10px;
    background-color: white;
    border-radius: 7px;
    box-shadow: 0 0 10px white, 0 0 10px white;
    font-size: 30px;
    font-weight: bold;
}
main li:nth-child(1){
    background-color: orange;
    color: white;
}
main li:nth-child(2){
    background-color: teal;
    color: white;
}
main li:nth-child(3){
    background-color: cornflowerblue;
    color: white;
}
main li a:hover{
    color: white;
}
main li span{
    font-size: 16px;
}
main #chart{
    display: flex;
    justify-content: center;
    margin: 0 20px 20px 20px;
    padding: 10px;
    background-color: #e3e3e3;
    box-shadow: 0 0 10px white, 0 0 10px white;
    border-radius: 10px;
}

main .new-news{
    padding: 20px;
    margin: 20px;
    background-color: white;
    font-family: sans-serif;
    border-radius: 10px;
    box-shadow: 0 0 1px black;
}
main table{
    padding: 10px;
    width: 100%;
    text-align: left;    
}
main table tr:first-child{
    text-transform: uppercase;
}
main table tr, main table td, main table th{
    padding: 10px;
    border-bottom: 1px solid #908d8d;
}
main table tr:nth-child(odd){
    background-color: #f4f4f4;
}
main form .edit, main form .hapus{
    border-radius: 5px;
    margin: 3px;
    padding: 3px;
    flex: 1 1 0;
    color: white;
    text-transform: uppercase;
}
main form .edit{
    background-color: #00d8b7;
}
main form .hapus{
    background-color: #ff3f3f;
}
main .paging{
    display: flex;
    justify-content: center;
}
main .paging span{
    padding: 5px 10px;
    margin: 5px;
    border-radius: 10px;
    color: white;
    text-transform: uppercase;
    background-color: firebrick;
}
main .main-edit{
    padding: 20px;
}
main div.col-sm-8, label{
    margin: 10px;
}
main label{
    font-size: 18px;
}
    </style>
</head>
<body>
    <h2 style="text-align:center; margin-top: 20px;">Laporan Aplikasi <?= $tgl_sekarang ?></h2>
   
    <?php
    /*
        Menampilkan jumlah dari tabel user, berita & kategori
    */
    $result = $conn->query("select * from user");
    $tot_user = $conn->affected_rows;

    $result = $conn->query("select * from berita");
    $tot_berita = $conn->affected_rows;

    $result = $conn->query("select * from kategori");
    $tot_kategori = $conn->affected_rows;
    
    $result->free_result();
?>
        <main>
            <div class="new-news">
                <table>
                    <tr>
                        <th>Total User</th>
                        <th><?= $tot_user ?></th>
                    </tr>
                    <tr>
                        <th>Total Berita</th>
                        <th><?= $tot_berita ?></th>
                    </tr>
                    <tr>
                        <th>Total Kategori</th>
                        <th><?= $tot_kategori ?></th>
                    </tr>
                </table>
            </div>
            <div class="new-news">
               <h3><a href="#">Data Tampil Berita Dalam Sebulan</a></h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kategori</th>
                        <th>Total Berita Kategori</th>
                    </tr>
<?php
    /*
        Menampilkan 10 berita dengan jumlah tampil terbanyak
    */
    $result = $conn->query("select kategori.nama_kategori, sum(berita.tampil) as berita_kategori from berita inner join kategori on berita.id_kategori = kategori.id_kategori group by berita.id_kategori order by berita.id_kategori asc");
    $i = 1;
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->nama_kategori ?></td>
                        <td><?= $data->berita_kategori ?> Kali</td>
                    </tr>
<?php
    $i++;
    }
    $result->free_result();
?>
                </table>
            </div>
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
                    </tr>
<?php
    /*
        Menampilkan 10 berita dengan jumlah tampil terbanyak
    */
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
                    </tr>
<?php
    $i++;
    }
    $result->free_result();
?>
                </table>
            </div>
            <div class="new-news">
               <h3><a href="#">Top 10 User</a></h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Total Berita</th>
                    </tr>
<?php
    // Tampilkan user berdasarkan total tampil berita
    $result = $conn->query("select count(berita.id_berita) as tot_berita_user, berita.id_user, user.nama, user.email from berita inner join user on berita.id_user = user.id_user group by id_user order by tot_berita_user desc limit 10");
    $i = 1;
    while($data = $result->fetch_object()){
?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data->nama ?></td>
                        <td><?= $data->email ?></td>
                        <td><?= $data->tot_berita_user ?> Berita</td>
                    </tr>
<?php
    $i++;
    }
    $result->free_result();
?>
                </table>
            </div>
            <div class="new-news">
               <h3>All Kategori</h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kategori</th>
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
                    </tr>
<?php
    $i++;
    }
    $result->free_result();
?>
                </table>
            </div>
        </main>
</body>
</html>