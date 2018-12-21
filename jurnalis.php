<?php require_once("header.php"); ?>
<?php require_once("aside-left.php"); ?>
              <main id="main">
               <?php
                    // tampilkan pesan jika ada
                    if (isset($pesan)) {
                        echo "<div class=\"alert alert-info\">$pesan</div>";
                    }
                ?>
                <div class="profil text-center">
                    <?php
                        // Tampilkan data user yang sedang aktif
                        $result = $conn->query("select * from user where id_user = '$id_user'");
                        $data = $result->fetch_object();
                        if($data->gambar_user === NULL) {
                            $gambar_user = "favicon.png";
                            echo "<img src=\"assets/img/logo/$gambar_user\" class=\"img-circle\" id=\"profil\">";

                        }else {
                            $gambar_user = $data->gambar_user;
                            echo "<img src=\"assets/img/user-picture/$gambar_user\" class=\"img-circle\" id=\"profil\">";
                        }
                    ?>
                    <h3><strong><?= $data->nama ?></strong></h3>
                        <a href="jurnalis-profil.php?pro=<?= $data->id_user; ?>"><button class="form-control btn"><strong>Ubah Profil</strong></button></a>
                </div>
                <ul class="nav nav-tabs nav-justified" id="profilMenu">
                  <li role="presentation" class="active"><a href="jurnalis.php">My News</a></li>
                </ul>
                <table class="table table-hover table-striped">
                  <tr>
                      <th>Judul</th>
                      <th>Tampil</th>
                      <th>Action</th>
                  </tr>
                  <?php
                    // Tampilkan semua berita yang pernah dibuat oleh user
                    $result->free_result();
                    $result = $conn->query("select * from berita where id_user = $id_user");
                    while($data = $result->fetch_object()){
                        echo "<tr>";
                        echo "<td><a href=\"berita.php?b=$data->id_berita\">$data->judul_berita</a></td>";
                        echo "<td class=\"text-center\">$data->tampil</td>";
                        echo "<td>";
                        echo "<a href=\"edit-berita.php?e=$data->id_berita\"><button class=\"btn btn-primary btn-xs\"><strong class=\"fa fa-pencil-square-o\"></strong></button></a>";
                        echo "<form action=\"hapus-berita.php\" method=\"post\" class=\"inline-form\">";
                        echo "<input type=\"hidden\" name=\"h\" value=\"$data->id_berita\" id=\"inputHidden\">";
                        echo "<button type=\"submit\" id=\"hapus_berita\" class=\"btn btn-danger btn-xs\"><strong class=\"fa fa-times\"></strong></button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    $result->free_result();
                  ?>
                </table>
              </main>
<?php require_once("aside-right.php"); ?>
