<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if (isset($_REQUEST['submit'])) {

        //validasi form kosong
        if (
            $_REQUEST['no_surat'] == ""
            || $_REQUEST['asal_surat'] == ""
            || $_REQUEST['klasifikasi'] == ""
            || $_REQUEST['perihal'] == ""
            || $_REQUEST['tgl_surat'] == ""
            || $_REQUEST['keterangan'] == ""
        ) {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            openPreviousPage();
        } else {

            $no_surat = $_REQUEST['no_surat'];
            $asal_surat = $_REQUEST['asal_surat'];
            $klasifikasi = $_REQUEST['klasifikasi'];
            $perihal = $_REQUEST['perihal'];
            $tgl_surat = $_REQUEST['tgl_surat'];
            $keterangan = $_REQUEST['keterangan'];
            $id_user = $_SESSION['id_user'];

            //validasi input data

            if (!preg_match("/^[a-zA-Z0-9.\/ -]*$/", $no_surat)) {
                $_SESSION['no_surat'] = 'Form No Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), minus(-) dan garis miring(/)';
                openPreviousPage();
            } else if (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $asal_surat)) {
                $_SESSION['asal_surat'] = 'Form Asal Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                openPreviousPage();
            } else if (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]+$/", $klasifikasi)) {
                $_SESSION['klasifikasi'] = 'Form Klasifikasi surat mengandung INVALID karakter';
                openPreviousPage();
            } else if (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]+$/", $perihal)) {
                $_SESSION['perihal'] = 'Form Perihal hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), kurung(), underscore(_), dan(&) persen(%) dan at(@)';
                openPreviousPage();
            } else if (!preg_match("/^[0-9.-]*$/", $tgl_surat)) {
                $_SESSION['tgl_surat'] = 'Form Tanggal Surat hanya boleh mengandung angka dan minus(-)';
                openPreviousPage();
            } else if (!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $keterangan)) {
                $_SESSION['keterangan'] = 'Form Keterangan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), dan kurung()';
                openPreviousPage();
            } else {

                $cek = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE no_surat='$no_surat'");
                $result = mysqli_num_rows($cek);

                if ($result > 0) {
                    $_SESSION['errDup'] = 'Nomor Surat sudah terpakai, gunakan yang lain!';
                    openPreviousPage();
                } else {

                    $ekstensi = array('jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf');
                    $file = $_FILES['file']['name'];
                    $x = explode('.', $file);
                    $eks = strtolower(end($x));
                    $ukuran = $_FILES['file']['size'];
                    $target_dir = "upload/surat_masuk/";

                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }

                    //jika form file tidak kosong akan mengeksekusi script dibawah ini
                    if ($file != "") {

                        $rand = rand(1, 10000);
                        $nfile = $rand . "-" . $file;

                        //validasi file
                        if (in_array($eks, $ekstensi) == true) {
                            if ($ukuran < 2500000) {

                                move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $nfile);

                                $query = mysqli_query($config, "INSERT INTO tbl_surat_masuk(no_surat,asal_surat,klasifikasi,perihal,tgl_surat,
                                                            tgl_diterima,file,keterangan,id_user)
                                                                VALUES('$no_surat','$asal_surat', $klasifikasi,'$perihal','$tgl_surat',NOW(),'$nfile','$keterangan','$id_user')");

                                if ($query == true) {
                                    $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                                    header("Location: ./admin.php?page=tsm");
                                    die();
                                } else {
                                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                    openPreviousPage();
                                }
                            } else {
                                $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
                                openPreviousPage();
                            }
                        } else {
                            $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.JPG, *.PNG, *.DOC, *.DOCX atau *.PDF!';
                            openPreviousPage();
                        }
                    } else {

                        //jika form file kosong akan mengeksekusi script dibawah ini
                        $query = mysqli_query($config, "INSERT INTO tbl_surat_masuk(no_surat,asal_surat,klasifikasi,perihal,tgl_surat,tgl_diterima,file,keterangan,id_user)
                                                    VALUES('$no_surat','$asal_surat', '$klasifikasi','$perihal','$tgl_surat',NOW(),'','$keterangan','$id_user')");

                        if ($query == true) {
                            $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                            header("Location: ./admin.php?page=tsm");
                            die();
                        } else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            openPreviousPage();
                        }
                    }
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
                            <li class="waves-effect waves-light"><a href="?page=tsm&act=add" class="judul"><i
                                        class="material-icons">mail</i> Tambah Data Surat Masuk</a></li>
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
            <form class="col s12" method="POST" action="?page=tsm&act=add" enctype="multipart/form-data">

                <!-- Row in form START -->
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">looks_one</i>
                    <input id="no_surat" type="text" class="validate" name="no_surat" required>
                    <?php
                    if (isset($_SESSION['no_surat'])) {
                        $no_surat = $_SESSION['no_surat'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $no_surat . '</div>';
                        unset($_SESSION['no_surat']);
                    }
                    if (isset($_SESSION['errDup'])) {
                        $errDup = $_SESSION['errDup'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errDup . '</div>';
                        unset($_SESSION['errDup']);
                    }
                    ?>
                    <label for="no_surat">Nomor Surat</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">place</i>
                    <input id="asal_surat" type="text" class="validate" name="asal_surat" required>
                    <?php
                    if (isset($_SESSION['asal_surat'])) {
                        $asal_surat = $_SESSION['asal_surat'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $asal_surat . '</div>';
                        unset($_SESSION['asal_surat']);
                    }
                    ?>
                    <label for="asal_surat">Asal Surat</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">view_list</i><label>Pilih Klasifikasi
                        Surat</label><br />
                    <div class="input-field col s11 right">
                        <select class="browser-default validate" name="klasifikasi" id="klasifikasi" required>
                            <option value="">Klasifikasi Surat</option>
                            <option value="biasa">Biasa</option>
                            <option value="penting">Penting</option>
                            <option value="undangan">Undangan</option>
                        </select>
                    </div>
                    <?php
                    if (isset($_SESSION['klasifikasi'])) {
                        $klasifikasi = $_SESSION['klasifikasi'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $klasifikasi . '</div>';
                        unset($_SESSION['klasifikasi']);
                    }
                    ?>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">date_range</i>
                    <input id="tgl_surat" type="text" name="tgl_surat" class="datepicker" required>
                    <?php
                    if (isset($_SESSION['tgl_surat'])) {
                        $tgl_surat = $_SESSION['tgl_surat'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tgl_surat . '</div>';
                        unset($_SESSION['tgl_surat']);
                    }
                    ?>
                    <label for="tgl_surat">Tanggal Surat</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">description</i>
                    <textarea id="perihal" class="materialize-textarea validate" name="perihal" required></textarea>
                    <?php
                    if (isset($_SESSION['perihal'])) {
                        $perihal = $_SESSION['ef'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $perihal . '</div>';
                        unset($_SESSION['perihal']);
                    }
                    ?>
                    <label for="perihal">Perihal</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix md-prefix">featured_play_list</i>
                    <input id="keterangan" type="text" class="validate" name="keterangan" required>
                    <?php
                    if (isset($_SESSION['keterangan'])) {
                        $keterangan = $_SESSION['keterangan'];
                        echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $keterangan . '</div>';
                        unset($_SESSION['keterangan']);
                    }
                    ?>
                    <label for="keterangan">Keterangan</label>
                </div>
                <div class="input-field col s6">
                    <div class="file-field input-field">
                        <div class="btn light-green darken-1">
                            <span>File</span>
                            <input type="file" id="file" name="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload file/scan gambar surat masuk">
                            <?php
                            if (isset($_SESSION['errSize'])) {
                                $errSize = $_SESSION['errSize'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errSize . '</div>';
                                unset($_SESSION['errSize']);
                            }
                            if (isset($_SESSION['errFormat'])) {
                                $errFormat = $_SESSION['errFormat'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errFormat . '</div>';
                                unset($_SESSION['errFormat']);
                            }
                            ?>
                            <small class="red-text">*Format file yang diperbolehkan *.JPG, *.PNG, *.DOC, *.DOCX, *.PDF dan
                                ukuran maksimal file 2 MB!</small>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Row in form END -->

        <div class="row">
            <div class="col 6">
                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i
                        class="material-icons">done</i></button>
            </div>
            <div class="col 6">
                <a href="?page=tsm" class="btn-large deep-orange waves-effect waves-light">BATAL <i
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