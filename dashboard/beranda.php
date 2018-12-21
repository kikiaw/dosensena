<?php require_once("header.php"); ?> 
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
<?php
    // Jika ada tampilkan pesan
    if(isset($pesan)){
        echo "<div class=\"alert alert-info\">$pesan</div>";
    }
?>
            <top-main>
                <ul>
                    <li><span class="fa fa-user fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="user.php"><?= $tot_user ?><br><span>total user</span></a></li>
                    <li><span class="fa fa-newspaper-o fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="berita.php"><?= $tot_berita ?><br><span>total berita</span></a></li>
                    <li><span class="fa fa-tags fa-lg" aria-hidden="true"></span>&nbsp;&nbsp;<a href="kategori.php"><?= $tot_kategori ?><br><span>total kategori</span></a></li>
                </ul>
            </top-main>
            <div id="chart">
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
                        <th>Action</th>
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
        </main>
    </div>
    <?php
        /*
            Menampilkan total tampil berita tiap kategori.
            Dengan bantuan Library HighchartJS
        */

        $nama_kat = array();
        $result = $conn->query("select nama_kategori from kategori order by id_kategori asc");
        while($data = $result->fetch_object()){
            $nama_kat[] = $data->nama_kategori;
        }
        $array_nama_kat = join(" ,",$nama_kat);


        $berita_kategori = array();
        $result = $conn->query("select sum(tampil) as berita_kategori from berita group by id_kategori order by id_kategori asc");
        while($data = $result->fetch_object()){
            $berita_kategori[] = $data->berita_kategori;
        }
        $array_berita_kategori = join(" ,",$berita_kategori);

        $result->free_result();

        $tgl_sekarang = date("F Y");
    ?>
    <script>
        $(document).ready(function(){
            Highcharts.chart('chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: '<strong>Data Tampil Berita <?= $tgl_sekarang ?></strong>'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: ["<?= implode($nama_kat, '","'); ?>"],
            },
            yAxis: {
                title: {
                    text: '<strong>Total Akses Berita Dalam Bulan Ini</strong>'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:1f}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:15px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}; font-size:15px">{point.name}</span>: <b style="font-size:15px">{point.y:2f}</b> of total<br/>'
            },

            series: [{
                name: 'Kategori',
                colorByPoint: true,
                data: [<?= implode($berita_kategori, ','); ?>],
            }]
        })
      })
    </script>
</body>
</html>
