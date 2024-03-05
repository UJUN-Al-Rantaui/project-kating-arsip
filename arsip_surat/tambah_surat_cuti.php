<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    $page = "tsc";

    // import dependency ang diperlukan
    require_once __DIR__ . "/data/SuratCuti.php";
    require_once __DIR__ . "/data/SuratCutiRepository.php";
    require_once __DIR__ . "/data/SuratCutiRepositoryImpl.php";
    require_once __DIR__ . "/data/SuratCutiService.php";
    require_once __DIR__ . "/data/PegawaiRepositoryImpl.php";
    require_once __DIR__ . "/data/PegawaiService.php";

    $suratCutiService = new SuratCutiService(new SuratCutiRepositoryImpl());

    if (isset($_REQUEST['submit'])) {
        //validasi form kosong
        if ($_REQUEST['nip'] == "" || $_REQUEST['jenis_cuti'] == "" || $_REQUEST['tanggal_mulai'] == "" || $_REQUEST['tanggal_selesai'] == "") {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            echo '<script language="javascript">window.history.back();</script>';
        } else {

            $suratCuti = new SuratCuti(
                kodeCuti: -1,
                nip: $_REQUEST['nip'],
                nama: "UNKNOWN",
                jenisCuti: $_REQUEST['jenis_cuti'],
                tanggalMulai: new DateTime($_REQUEST['tanggal_mulai']),
                tanggalSelesai: new DateTime($_REQUEST['tanggal_selesai']),
            );

            $pegawaiService = new PegawaiService(new PegawaiRepositoryImpl());

            //validasi input data
            if (!preg_match("/^[0-9]*$/", $suratCuti->getNip())) {
                $_SESSION['nip'] = 'Form NIP hanya boleh mengandung karakter angka';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $suratCuti->getJenisCuti())) {
                $_SESSION['jenis_cuti'] = 'Form Jenis Cuti hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif (is_null($pegawaiService->findByNip($suratCuti->getNip()))) {
                $_SESSION['errNotFound'] = "NIP Harus sesuai dengan pegawai yang terdaftar";
                echo '<script language="javascript">window.history.back();</script>';
            } elseif(!preg_match("/^[0-9.-]*$/", $suratCuti->getTanggalMulai()->format('d-m-Y'))){
                $_SESSION['tanggal_mulai'] = 'Form Tanggal Mulai hanya boleh mengandung angka dan minus(-)';
                echo '<script language="javascript">window.history.back();</script>';
            } elseif(!preg_match("/^[0-9.-]*$/", $suratCuti->getTanggalSelesai()->format('d-m-Y'))){
                $_SESSION['tanggal_selesai'] = 'Form Tanggal selesai hanya boleh mengandung angka dan minus(-)';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                //jika form valid -> tambahkan data
                $isAdded = (bool)$suratCutiService->register($suratCuti);

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
                                        class="material-icons">mail</i> Tambah Surat Cuti</a></li>
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
                    <i class="material-icons prefix md-prefix">looks_one</i>
                    <input id="nip" type="text" class="validate" name="nip" required>
                    <?php
                    if (isset($_SESSION['nip'])) {
                        $nip = $_SESSION['nip'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $nip . '</div>';
                        unset($_SESSION['nip']);
                    }
                    if (isset($_SESSION['errNotFound'])) {
                        $errNotFound = $_SESSION['errNotFound'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errNotFound . '</div>';
                        unset($_SESSION['errNotFound']);
                    }
                    ?>
                    <label for="nip">NIP</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">mail_outline</i><label>Jenis Cuti</label><br />
                    <div class="input-field col s11 right">
                        <select class="browser-default validate" name="jenis_cuti" id="jenis_cuti" required>
                            <option value=""> - Pilih Jenis Cuti - </option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Kehamilan">Kehamilan</option>
                            <option value="Pernikahan">Pernikahan</option>
                            <option value="Keagamaan">Keagamaan</option>
                            <option value="Kedukaan Kematian">Kedukaan Kematian</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Konpensasi">Konpensasi</option>
                        </select>
                    </div>
                    <?php
                    if (isset($_SESSION['jenis_cuti'])) {
                        $jenis_cuti = $_SESSION['jenis_cuti'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $jenis_cuti . '</div>';
                        unset($_SESSION['jenis_cuti']);
                    }
                    ?>
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