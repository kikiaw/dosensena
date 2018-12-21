<aside id="asideRight">
                  <h4><strong>Topik Pilihan</strong></h4>
                  <?php
                        $result = $conn->query("select * from berita order by rand() limit 10");
                        while($data = $result->fetch_object()){
                  ?>
                  <div class="topikPilihan">
                      <span class="bullet"></span><h5 class="judul"><a href="berita.php?b=<?= $data->id_berita ?>"><?= $data->judul_berita ?></a></h5>
                  </div>
                  <?php
                    }
                    $result->free_result();
                  ?>
              </aside>
<?php
    $conn->close();
?>
