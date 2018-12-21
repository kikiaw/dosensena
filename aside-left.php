<aside id="asideLeft" class="">

                  <h4><strong>10 Top Viral <small><a href="viral-news.php">View All</a></small></strong></h4>
                  <?php
                    $result = $conn->query("select * from berita order by tampil desc limit 10");
                    $i = 1;
                    while($data = $result->fetch_object()){
                  ?>
                  <div class="viral">
                      <span class="bullet">#<?= $i ?></span>
                      <h5 class="judul"><strong><a href="berita.php?b=<?= $data->id_berita ?>"><?= $data->judul_berita ?></a></strong>&nbsp;&nbsp;<small>(<?= number_format($data->tampil, 0, ",", "."); ?> kali dibaca)</small></h5>
                  </div>
                  <?php
                        $i++;
                    }
                        $result->free_result();
                  ?>
              </aside>
