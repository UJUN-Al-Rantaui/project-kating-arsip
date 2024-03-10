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
            window.location.href="./admin.php?page=tp";
            </script>';
} else {

    $page = "tp";

    // import dependency ang diperlukan
    require_once __DIR__ . "/data/Pegawai.php";
    require_once __DIR__ . "/data/PegawaiRepository.php";
    require_once __DIR__ . "/data/PegawaiRepositoryImpl.php";
    require_once __DIR__ . "/data/PegawaiService.php";

    $pegawaiService = new PegawaiService(new PegawaiRepositoryImpl());

    if (isset($_REQUEST['submit'])) {
        //validasi form kosong
        if ($_REQUEST['nip'] == ""
                || $_REQUEST['nama'] == "" 
                || $_REQUEST['telepon'] == "" 
                || $_REQUEST['alamat'] == "") {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            openPreviousPage();
        } else {

            $pegawai = new Pegawai(
                nip: $_REQUEST['nip'],
                nama: $_REQUEST['nama'],
                telepon: $_REQUEST['telepon'],
                alamat: $_REQUEST['alamat'],
            );

            //validasi input data

            if (!preg_match("/^[0-9]*$/", $pegawai->getNip())) {
                $_SESSION['nip'] = 'Form NIP hanya boleh mengandung karakter angka';
                openPreviousPage();
            } elseif (!preg_match("/\b.{18}\b/", $pegawai->getNip())) {
                $_SESSION['nipLengthErr'] = 'Panjang NIP tidak boleh kurang dari atau lebih dari 18';
                openPreviousPage();
            } elseif (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $pegawai->getNama())) {
                $_SESSION['namaErr'] = 'Form Nama hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                openPreviousPage();
            } elseif (!preg_match("/^[0-9]*$/", $pegawai->getTelepon())) {
                $_SESSION['telepon'] = 'Form No. Telepon hanya boleh mengandung karakter angka 0 sampai 9';
                openPreviousPage();
            } elseif (!preg_match("/\b.{10,15}\b/", $pegawai->getTelepon())) {
                $_SESSION['telepon'] = 'Panjang No. Telepon hanya boleh 10 sampai 15';
                openPreviousPage();
            } elseif (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/", $pegawai->getAlamat())) {
                $_SESSION['alamat'] = 'Form Alamat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), kurung(), underscore(_), dan(&) persen(%) dan at(@)';
                openPreviousPage();
            } elseif (!is_null($pegawaiService->findByNip($pegawai->getNip()))) {
                $_SESSION['errDup'] = 'NIP sudah terpakai, gunakan yang lain!';
                openPreviousPage();
            } else {

                //jika form valid -> tambahkan data
                $isAdded = $pegawaiService->register($pegawai);

                if ($isAdded) {
                    $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                    header("Location: ./admin.php?page=$page");
                    die();
                } else {
                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                    openPreviousPage();
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
                            <li class="waves-effect waves-light"><a href="?page=<?=$page?>&act=add" class="judul"><i
                                        class="material-icons">person</i> Tambah Data Pegawai</a></li>
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
            <form class="col s12" method="POST" action="?page=<?=$page?>&act=add" enctype="multipart/form-data">

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
                    if (isset($_SESSION['nipLengthErr'])) {
                        $nipLengthErr = $_SESSION['nipLengthErr'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $nipLengthErr . '</div>';
                        unset($_SESSION['nipLengthErr']);
                    }
                    if (isset($_SESSION['errDup'])) {
                        $errDup = $_SESSION['errDup'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errDup . '</div>';
                        unset($_SESSION['errDup']);
                    }
                    ?>
                    <label for="nip">NIP</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">person</i>
                    <input id="nama" type="text" class="validate" name="nama" onchange="eval('')" required>
                    <?php
                    if (isset($_SESSION['namaErr'])) {
                        $nama = $_SESSION['namaErr'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $nama . '</div>';
                        unset($_SESSION['namaErr']);
                    }
                    ?>
                    <label for="nama">Nama</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">phone</i>
                    <input id="telepon" type="tel" class="validate" name="telepon" required>
                    <?php
                    if (isset($_SESSION['telepon'])) {
                        $telepon = $_SESSION['telepon'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $telepon . '</div>';
                        unset($_SESSION['telepon']);
                    }
                    ?>
                    <label for="telepon">No. Telepon</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">place</i>
                    <textarea id="alamat" class="materialize-textarea validate" name="alamat"
                            required></textarea>
                    <?php
                    if (isset($_SESSION['alamat'])) {
                        $alamat = $_SESSION['alamat'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $alamat . '</div>';
                        unset($_SESSION['alamat']);
                    }
                    ?>
                    <label for="alamat">Alamat</label>
                </div>
        </div>
        <!-- Row in form END -->

        <div class="row">
            <div class="col 6">
                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i
                        class="material-icons">done</i></button>
            </div>
            <div class="col 6">
                <a href="?page=<?=$page?>" class="btn-large deep-orange waves-effect waves-light">BATAL <i
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