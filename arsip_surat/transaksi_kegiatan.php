<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} elseif ($_SESSION['admin'] != 2 and $_SESSION['admin'] != 4 and $_SESSION['admin'] != 5 and $_SESSION['admin'] != 6) {
    echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                    window.location.href="./";
                  </script>';
} else {

    if (isset($_REQUEST['act'])) {
        $act = $_REQUEST['act'];
        switch ($act) {
            case 'add':
                include "tambah_kegiatan.php";
                break;
            case 'edit':
                include "edit_kegiatan.php";
                break;
            case 'print':
                include "cetak_kegiatan.php";
                break;
            case 'del':
                include "hapus_kegiatan.php";
                break;
        }
    } else {
        require_once __DIR__ . "/data/Kegiatan.php";
        require_once __DIR__ . "/data/KegiatanRepository.php";
        require_once __DIR__ . "/data/KegiatanRepositoryImpl.php";
        require_once __DIR__ . "/data/KegiatanService.php";

        $kegiatanService = new KegiatanService(new KegiatanRepositoryImpl());

        $page = "tk";

        $query = mysqli_query($config, "SELECT referensi FROM tbl_sett");
        list($referensi) = mysqli_fetch_array($query);

        //pagging
        $limit = $referensi;
        $pg = @$_GET['pg'];
        if (empty($pg)) {
            $curr = 0;
            $pg = 1;
        } else {
            $curr = ($pg - 1) * $limit;
        } ?>

        <!-- Row Start -->
        <div class="row">
            <!-- Secondary Nav START -->
            <div class="col s12">
                <div class="z-depth-1">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <div class="col m7">
                                <ul class="left">
                                    <li class="waves-effect waves-light hide-on-small-only"><a href="?page=<?php echo $page ?>"
                                            class="judul"><i class="material-icons">assignment</i> Kegiatan</a></li>
                                    <!-- Tombol Tambah Surat Masuk -->
                                    <?php //if($_SESSION['admin'] == 2 )  {   ?>
                                    <li class="waves-effect waves-light">
                                        <a href="?page=<?php echo $page ?>&act=add"><i
                                                class="material-icons md-24">add_circle</i> Tambah Data</a>
                                    </li>
                                    <?php //}   ?>

                                </ul>
                            </div>
                            <div class="col m5 hide-on-med-and-down">
                                <form method="post" action="?page=<?php echo $page ?>">
                                    <div class="input-field round-in-box">
                                        <input id="search" type="search" name="cari"
                                            placeholder="Ketik dan tekan enter mencari data..." required>
                                        <label for="search"><i class="material-icons md-dark">search</i></label>
                                        <input type="submit" name="submit" class="hidden">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- Secondary Nav END -->
        </div>
        <!-- Row END -->

        <?php
        if (isset($_SESSION['succAdd'])) {
            $succAdd = $_SESSION['succAdd'];
            echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succAdd . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            unset($_SESSION['succAdd']);
        }
        if (isset($_SESSION['succEdit'])) {
            $succEdit = $_SESSION['succEdit'];
            echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succEdit . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            unset($_SESSION['succEdit']);
        }
        if (isset($_SESSION['succDel'])) {
            $succDel = $_SESSION['succDel'];
            echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succDel . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            unset($_SESSION['succDel']);
        }
        ?>

        <!-- Row form Start -->
        <div class="row jarak-form">

            <?php
            if (isset($_REQUEST['submit'])) {
                $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
                echo '
                        <div class="col s12" style="margin-top: -18px;">
                            <div class="card blue lighten-5">
                                <div class="card-content">
                                <p class="description">Hasil pencarian untuk kata kunci <strong>"' . stripslashes($cari) . '"</strong><span class="right"><a href="?page=' . $page . '"><i class="material-icons md-36" style="color: #333;">clear</i></a></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col m12" id="colres">
                        <table class="bordered" id="tbl">
                            <thead class="blue lighten-4" id="head">
                                <tr>
                                    <th width="6%">No. </th>
                                    <th width="6%">ID</th>
                                    <th width="28%">Kegiatan<hr>Tempat</th>
                                    <th width="16%">Tanggal Mulai<hr>Tanggal Selesai</th>
                                    <th width="26%">Pelaksana<hr>Peserta</th>
                                    <th width="18%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                                </tr>
                            </thead>
                            <tbody>';

                //script untuk mencari data
                $kegiatanArray = $kegiatanService->search($cari, current: $curr, limit: $limit);
                $dateFormat = "d-m-Y";
                if (count($kegiatanArray) > 0) {
                    $no = 1;
                    foreach ($kegiatanArray as $kegiatan) {
                        echo '
                                  <tr>
                                    <td>' . $no . '</td>
                                    <td>' . $kegiatan->getId() . '</td>
                                    <td>' . $kegiatan->getKegiatan() . '<hr>' . $kegiatan->getTempat() . '</td>
                                    <td>' . $kegiatan->getTanggalMulai()->format($dateFormat) . '<hr>' . $kegiatan->getTanggalSelesai()->format($dateFormat) . '</td>
                                    <td>' . $kegiatan->getPelaksanaString(", ") . '<hr>' . $kegiatan->getPesertaString(", ") . '</td>
                                    <td>';

                        if ($_SESSION['admin'] != 2) {
                            ?><button class="btn small blue-grey waves-effect waves-light"><i class="material-icons">error</i> No Action</button><?php
                        } else {
                            ?>
                            <a class="btn small blue waves-effect waves-light" href="?page=<?= $page ?>&act=edit&id=<?= $kegiatan->getId() ?>">
                                <i class="material-icons">edit</i> EDIT</a>
                            <a class="btn small deep-orange waves-effect waves-light"
                                href="?page=<?= $page ?>&act=del&id=<?= $kegiatan->getId() ?>">
                                <i class="material-icons">delete</i> DEL</a>
                            <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat=<?=$kegiatan->getId()?>" target="_blank">
                                <i class="material-icons">print</i> PRINT</a>
                            <?php
                        }
                        echo '
                                    </td>
                                </tr>';
                        $no++;
                    }
                } else {
                    echo '<tr><td colspan="5"><center><p class="add">Tidak ada data yang ditemukan</p></center></td></tr>';
                }
                echo '</tbody></table><br/><br/>
                        </div>
                    </div>
                    <!-- Row form END -->';

            } else {

                echo '
                        <div class="col m12" id="colres">
                            <table class="bordered" id="tbl">
                                <thead class="blue lighten-4" id="head">
                                    <tr>
                                        <th width="6%">No. </th>
                                        <th width="6%">ID</th>
                                        <th width="28%">Kegiatan<hr>Tempat</th>
                                        <th width="16%">Tanggal Mulai<hr>Tanggal Selesai</th>
                                        <th width="26%">Pelaksana<hr>Peserta</th>
                                        <th width="18%">Tindakan <span class="right tooltipped" data-position="left" data-tooltip="Atur jumlah data yang ditampilkan"><a class="modal-trigger" href="#modal"><i class="material-icons" style="color: #333;">settings</i></a></span></th>

                                            <div id="modal" class="modal">
                                                <div class="modal-content white">
                                                    <h5>Jumlah data yang ditampilkan per halaman</h5>';
                $query = mysqli_query($config, "SELECT id_sett,surat_masuk FROM tbl_sett");
                list($id_sett, $surat_masuk) = mysqli_fetch_array($query);
                echo '
                                                    <div class="row">
                                                        <form method="post" action="">
                                                            <div class="input-field col s12">
                                                                <input type="hidden" value="' . $id_sett . '" name="id_sett">
                                                                <div class="input-field col s1" style="float: left;">
                                                                    <i class="material-icons prefix md-prefix">looks_one</i>
                                                                </div>
                                                                <div class="input-field col s11 right" style="margin: -5px 0 20px;">
                                                                    <select class="browser-default validate" name="surat_masuk" required>
                                                                        <option value="' . $surat_masuk . '">' . $surat_masuk . '</option>
                                                                        <option value="5">5</option>
                                                                        <option value="10">10</option>
                                                                        <option value="20">20</option>
                                                                        <option value="50">50</option>
                                                                        <option value="100">100</option>
                                                                    </select>
                                                                </div>
                                                                <div class="modal-footer white">
                                                                    <button type="submit" class="modal-action waves-effect waves-green btn-flat" name="simpan">Simpan</button>';
                if (isset($_REQUEST['simpan'])) {
                    $id_sett = "1";
                    $surat_masuk = $_REQUEST['surat_masuk'];
                    $id_user = $_SESSION['id_user'];

                    $query = mysqli_query($config, "UPDATE tbl_sett SET surat_masuk='$surat_masuk',id_user='$id_user' WHERE id_sett='$id_sett'");
                    if ($query == true) {
                        header("Location: ./admin.php?page=$page");
                        die();
                    }
                }
                echo '
                                                                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Batal</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                    </tr>
                                </thead>
                                <tbody>';

                //script untuk menampilkan data
                $dateFormat = "d-m-Y";
                $kegiatanArray = $kegiatanService->search("", $curr, $limit);
                if (count($kegiatanArray) > 0) {
                    $no = 1;
                    foreach ($kegiatanArray as $kegiatan) {
                        echo '
                                      <tr>
                                      <td>' . $no . '</td>
                                      <td>' . $kegiatan->getId() . '</td>
                                      <td>' . $kegiatan->getKegiatan() . '<hr>' . $kegiatan->getTempat() . '</td>
                                      <td>' . $kegiatan->getTanggalMulai()->format($dateFormat) . '<hr>' . $kegiatan->getTanggalSelesai()->format($dateFormat) . '</td>
                                      <td>' . $kegiatan->getPelaksanaString(", ") . '<hr>' . $kegiatan->getPesertaString(", ") . '</td>
                                      <td>';
                        if ($_SESSION['admin'] != 2) {
                            echo '<button class="btn small blue-grey waves-effect waves-light"><i class="material-icons">error</i> No Action</button>';
                        } else {
                            ?>
                            <a class="btn small blue waves-effect waves-light" href="?page=<?=$page?>&act=edit&id=<?=$kegiatan->getId()?>">
                                <i class="material-icons">edit</i> EDIT</a>
                            <a class="btn small deep-orange waves-effect waves-light" href="?page=<?=$page?>&act=del&id=<?=$kegiatan->getId()?>">
                                <i class="material-icons">delete</i> DEL</a>
                            <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=<?=$page?>&act=print&id=<?=$kegiatan->getId()?>" target="_blank">
                                <i class="material-icons">print</i> PRINT</a>
                            <?php
                        }
                        echo '
                                        </td>
                                    </tr>';
                        $no++;
                    }
                } else {
                    echo '<tr><td colspan="5"><center><p class="add">Tidak ada data untuk ditampilkan. <u><a href="?page=' . $page . '&act=add">Tambah data baru</a></u></p></center></td></tr>';
                }
                echo '</tbody></table>
                        </div>
                    </div>
                    <!-- Row form END -->';

                $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk " . ($_SESSION['admin'] != 2 ? "WHERE tujuan_surat={$_SESSION['admin']}" : ""));
                $cdata = mysqli_num_rows($query);
                $cpg = ceil($cdata / $limit);

                echo '<br/><!-- Pagination START -->
                          <ul class="pagination">';

                if ($cdata > $limit) {

                    //first and previous pagging
                    if ($pg > 1) {
                        $prev = $pg - 1;
                        echo '<li><a href="?page=' . $page . '&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=' . $page . '&pg=' . $prev . '"><i class="material-icons md-48">chevron_left</i></a></li>';
                    } else {
                        echo '<li class="disabled"><a href="#"><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">chevron_left</i></a></li>';
                    }

                    //perulangan pagging
                    for ($i = 1; $i <= $cpg; $i++) {
                        if ((($i >= $pg - 3) && ($i <= $pg + 3)) || ($i == 1) || ($i == $cpg)) {
                            if ($i == $pg)
                                echo '<li class="active waves-effect waves-dark"><a href="?page=' . $page . '&pg=' . $i . '"> ' . $i . ' </a></li>';
                            else
                                echo '<li class="waves-effect waves-dark"><a href="?page=' . $page . '&pg=' . $i . '"> ' . $i . ' </a></li>';
                        }
                    }

                    //last and next pagging
                    if ($pg < $cpg) {
                        $next = $pg + 1;
                        echo '<li><a href="?page=' . $page . '&pg=' . $next . '"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=' . $page . '&pg=' . $cpg . '"><i class="material-icons md-48">last_page</i></a></li>';
                    } else {
                        echo '<li class="disabled"><a href="#"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">last_page</i></a></li>';
                    }
                    echo '
                        </ul>';
                } else {
                    echo '';
                }
            }
    }
}
?>