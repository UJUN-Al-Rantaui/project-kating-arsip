<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();

// cek hak akses
} elseif ( $_SESSION['admin'] != 2) {
    echo '<script language="javascript">
            window.alert("ERROR! Anda tidak memiliki hak akses untuk mengedit data ini");
            window.location.href="./admin.php?page=tk";
            </script>';
} else {

    $page = "tk";

    // import dependency ang diperlukan
    require_once __DIR__ . "/data/Kegiatan.php";
    require_once __DIR__ . "/data/KegiatanRepository.php";
    require_once __DIR__ . "/data/KegiatanRepositoryImpl.php";
    require_once __DIR__ . "/data/KegiatanService.php";

    $kegiatanService = new KegiatanService(new KegiatanRepositoryImpl());

    if (isset($_REQUEST['submit'])) {
        //validasi form kosong
        if ($_REQUEST['kegiatan'] == "" || $_REQUEST['tempat'] == "" || $_REQUEST['tanggal_mulai'] == "" || $_REQUEST['tanggal_selesai'] == ""
            || $_REQUEST['pelaksana'] == "" || $_REQUEST['peserta'] == "")  {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            echo '<script language="javascript">window.history.back();</script>';
        } else {

            $kegiatan = new Kegiatan(
                id: -1,
                kegiatan: $_REQUEST['kegiatan'],
                tanggalMulai: new DateTime($_REQUEST['tanggal_mulai']),
                tanggalSelesai: new DateTime($_REQUEST['tanggal_selesai']),
                tempat: $_REQUEST['tempat'],
                pelaksana: array(),
                peserta: array()
            );

            $separator = (PHP_OS == "Darwin" || PHP_OS == "Linux") ? "\n":"\r\n";
            $kegiatan->setPelaksana($_REQUEST['pelaksana'], $separator);
            $kegiatan->setPeserta($_REQUEST['peserta'], $separator);

            //validasi input data
            if (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $kegiatan->getKegiatan())) {
                $_SESSION['jenis_cuti'] = 'Form Jenis Cuti hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $kegiatan->getTempat())) {
                $_SESSION['jenis_cuti'] = 'Form Jenis Cuti hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif(!preg_match("/^[0-9.-]*$/", $kegiatan->getTanggalMulai()->format('d-m-Y'))){
                $_SESSION['tanggal_mulai'] = 'Form Tanggal Mulai hanya boleh mengandung angka dan minus(-)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif(!preg_match("/^[0-9.-]*$/", $kegiatan->getTanggalSelesai()->format('d-m-Y'))){
                $_SESSION['tanggal_selesai'] = 'Form Tanggal selesai hanya boleh mengandung angka dan minus(-)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif (!preg_match("/^[a-zA-Z0-9.,()\r\n \/ -]*$/", $_REQUEST['pelaksana'])) {
                $_SESSION['jenis_cuti'] = 'Form Jenis Cuti hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif (!preg_match("/^[a-zA-Z0-9.,()\r\n \/ -]*$/", $_REQUEST['peserta'])) {
                $_SESSION['jenis_cuti'] = 'Form Jenis Cuti hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                //jika form valid -> tambahkan data
                $isAdded = (bool)$kegiatanService->register($kegiatan);

                if ($isAdded) { 
                    $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                    header("Location: ./admin.php?page=$page");
                    die();
                } else {
                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                    echo '<script language="javascript">window.history.back();</script>';
                }
            }
        }
    } else { ?>

        <!-- Row Start -->
        <div class="row">
            <!-- Secondary Nav START -->
            <div class="col s12">
                <nav class="secondary-nav">
                    <div class="nav-wrapper blue-grey darken-1">
                        <ul class="left">
                            <li class="waves-effect waves-light"><a href="?page=<?php echo $page ?>&act=add" class="judul"><i
                                        class="material-icons">description</i>Tambah Kegiatan</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- Secondary Nav END -->
        </div>
        <!-- Row END -->

        <?php
        if (isset($_SESSION['errQ'])) {
            $errQ = $_SESSION['errQ'];
            echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> ' . $errQ . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
            unset($_SESSION['errQ']);
        }
        if (isset($_SESSION['errEmpty'])) {
            $errEmpty = $_SESSION['errEmpty'];
            echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> ' . $errEmpty . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
            unset($_SESSION['errEmpty']);
        }
        ?>

        <!-- Row form Start -->
        <div class="row jarak-form">

            <!-- Form START -->
            <form class="col s12" method="POST" action="?page=<?php echo $page ?>&act=add" enctype="multipart/form-data">

                <!-- Row in form START -->
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">description</i>
                    <textarea id="kegiatan" type="text" class="materialize-textarea validate" name="kegiatan" required></textarea>
                    <?php
                    if (isset($_SESSION['kegiatan'])) {
                        $kegiatan = $_SESSION['kegiatan'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $kegiatan . '</div>';
                        unset($_SESSION['kegiatan']);
                    }
                    ?>
                    <label for="kegiatan">Kegiatan</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">place</i>
                    <textarea id="tempat" type="text" class="materialize-textarea validate" name="tempat" required></textarea>
                    <?php
                    if (isset($_SESSION['tempat'])) {
                        $tempat = $_SESSION['tempat'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tempat . '</div>';
                        unset($_SESSION['tempat']);
                    }
                    ?>
                    <label for="tempat">Tempat</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">event</i>
                    <input id="tanggal_mulai" type="text" name="tanggal_mulai" class="datepicker" required>
                    <?php
                    if (isset($_SESSION['tanggal_mulai'])) {
                        $tanggal_mulai = $_SESSION['tanggal_mulai'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tanggal_mulai . '</div>';
                        unset($_SESSION['tanggal_mulai']);
                    }
                    ?>
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">event</i>
                    <input id="tanggal_selesai" type="text" class="datepicker" name="tanggal_selesai" required>
                    <?php
                    if (isset($_SESSION['tanggal_selesai'])) {
                        $tanggal_selesai = $_SESSION['tanggal_selesai'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tanggal_selesai . '</div>';
                        unset($_SESSION['tanggal_selesai']);
                    }
                    ?>
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">people</i>
                    <textarea id="pelaksana" type="text" class="materialize-textarea validate" name="pelaksana" required></textarea>
                    <?php
                    if (isset($_SESSION['pelaksana'])) {
                        $pelaksana = $_SESSION['pelaksana'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $pelaksana . '</div>';
                        unset($_SESSION['pelaksana']);
                    }
                    ?>
                    <label for="pelaksana">Pelaksana</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">people</i>
                    <textarea id="peserta" type="text" class="materialize-textarea validate" name="peserta" required></textarea>
                    <?php
                    if (isset($_SESSION['peserta'])) {
                        $peserta = $_SESSION['peserta'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $peserta . '</div>';
                        unset($_SESSION['peserta']);
                    }
                    ?>
                    <label for="peserta">Peserta</label>
                </div>
        </div>
        <!-- Row in form END -->

        <div class="row">
            <div class="col 6">
                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i
                        class="material-icons">done</i></button>
            </div>
            <div class="col 6">
                <a href="?page=<?php echo $page ?>" class="btn-large deep-orange waves-effect waves-light">BATAL <i
                        class="material-icons">clear</i></a>
            </div>
        </div>

        </form>
        <!-- Form END -->

        </div>
        <!-- Row form END -->

        <?php
    }
}
?>