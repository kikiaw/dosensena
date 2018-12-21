<?php require_once("header.php"); ?>
<?php require_once("aside-left.php"); ?>
                <main id="main" class="">
                   <?php

                        // Buat Query untuk pagination & berita
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

                        $result = $conn->query("select berita.id_berita, berita.judul_berita, berita.waktu_berita, berita.gambar_berita, berita.tampil, user.id_user, user.nama, user.gambar_user, kategori.id_kategori, kategori.nama_kategori from berita inner join kategori on berita.id_kategori = kategori.id_kategori inner join user on berita.id_user = user.id_user order by berita.tampil desc limit $posisi,$batas");
                    ?>
                    <h4 class="text-left" id="judulAtas"><strong>Viral</strong></h4>
                    <?php
                        while($data = $result->fetch_object()){
                    ?>
                    <div class="content">
                     <div class="postHeader">
                          <?php
                            if($data->gambar_user === NULL) {
                                $gambar_user = "favicon.png";
                                echo "<img src=\"assets/img/logo/$gambar_user\" class=\"img-circle\">";

                                }else {
                                $gambar_user = $data->gambar_user;
                                echo "<img src=\"assets/img/user-picture/$gambar_user\" class=\"img-circle\">";
                            }
                          ?>
                          <span><strong>explore</strong> oleh <strong><a href="kategori-user.php?u=<?= $data->id_user ?>" style="color:black;"><?= $data->nama ?></a></strong><br>
                          <small><?= $data->waktu_berita ?> WITA</small></span>
                      </div>
                      <div class="postContent">
                         <div class="subPostContent">
                              <img src="assets/img/post-picture/<?= $data->gambar_berita ?>" class="img-responsive">
                          </div>
                          <h4 class="text-left"><strong><a href="berita.php?b=<?= $data->id_berita ?>"><?= $data->judul_berita ?></a></strong></h4>
                      </div>
                      <div class="postCategory"><a href="kategori.php?k=<?= $data->id_kategori ?>"><small><?= $data->nama_kategori ?></small></a></div>
                     </div>

                     <?php
                     }

                    $result->free_result();

                    $result = $conn->query("select * from berita");
                    $data = $result->fetch_object();
                    $jumlah = $result->num_rows;

                    $jumlah_hal = ceil($jumlah/$batas);


                     ?>

                    <div id="paging" class="text-center">
                        <nav aria-label="Page navigation">
                          <ul class="pagination">
                           <?php
                            // Buat link Sebelumnya
                            if($halaman > 1){
                                $link = $halaman-1;
                                $prev = "<li><a href=\"viral-news.php?hal=$link\">Sebelumnya</a></li>";
                            }else{
                                $prev = "";
                            }

                            // Buat link Selanjutnya
                            if($halaman < $jumlah_hal){
                                $link = $halaman+1;
                                $next = "<a href=\"viral-news.php?hal=$link\">Selanjutnya</a>";
                            }else{
                                $next = "";
                            }

                              // Tampilkan Link
                              echo "<li>$prev</li>";
                              echo "<li>$next</li>";
                            ?>
                          </ul>
                        </nav>
                    </div>
              </main>
<?php require_once("aside-right.php"); ?>
