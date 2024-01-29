<?php
    ob_start();
    //cek session
    session_start();

    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {
?>
<!--

<!doctype html>
<html lang="en">

<!-- Include Head START -->
<?php include('include/head.php'); ?>
<!-- Include Head END -->

<!-- Body START -->
<body class="bg">

<!-- Header START -->
<header>

<!-- Include Navigation START -->
<?php include('include/menu.php'); ?>
<!-- Include Navigation END -->

</header>
<!-- Header END -->

<!-- Main START -->
<main>

    <!-- container START -->
    <div class="container">

    <?php
        if(isset($_REQUEST['page'])){
            $page = $_REQUEST['page'];
            switch ($page) {
                case 'tsm':
                    include "transaksi_surat_masuk.php";
                    break;
                case 'ctk':
                    include "cetak_disposisi.php";
                    break;
                case 'tsk':
                    include "transaksi_surat_keluar.php";
                    break;
                case 'asm':
                    include "agenda_surat_masuk.php";
                    break;
                case 'ask':
                    include "agenda_surat_keluar.php";
                    break;
                case 'ref':
                    include "referensi.php";
                    break;
                case 'sett':
                    include "pengaturan.php";
                    break;
                case 'pro':
                    include "profil.php";
                    break;
                case 'gsm':
                    include "galeri_sm.php";
                    break;
                case 'gsk':
                    include "galeri_sk.php";
                    break;
                case 'tp':
                    include "transaksi_pegawai.php";
                    break;
                case 'tsc':
                    include "transaksi_surat_cuti.php";
                    break;
                case 'tk':
                    include "transaksi_kegiatan.php";
                    break;
            }
        } else {
    ?>
        <!-- Row START -->
        <div class="row">

            <!-- Include Header Instansi START -->
            <?php include('include/header_instansi.php'); ?>
            <!-- Include Header Instansi END -->

            <!-- Welcome Message START -->
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <h4>Selamat Datang <?php echo $_SESSION['nama']; ?></h4>
                        <p class="description">Anda login sebagai
                        <?php
                            if($_SESSION['admin'] == 1){
                                echo "<strong>Administrator</strong>. Anda memiliki kemampuan manipulasi user dan mengubah tampilan website .";
                            } elseif($_SESSION['admin'] == 2){
                                echo "<strong>Admin</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                            } elseif($_SESSION['admin'] == 4) {
                                echo "<strong>PKM</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                            } elseif($_SESSION['admin'] == 5) {
                                echo "<strong>PKA</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                            } elseif($_SESSION['admin'] == 6) {
                                echo "<strong>P4GKA</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                            } else {
                                echo "<strong>Pengguna tak dikenali</strong>.";
                            }?></p>
                    </div>
                </div>
            </div>
            <!-- Welcome Message END -->

            <?php
                require_once __DIR__."/data/Pegawai.php";
                require_once __DIR__."/data/PegawaiRepository.php";
                require_once __DIR__."/data/PegawaiRepositoryImpl.php";
                require_once __DIR__."/data/PegawaiService.php";
                require_once __DIR__."/data/SuratCuti.php";
                require_once __DIR__."/data/SuratCutiRepository.php";
                require_once __DIR__."/data/SuratCutiRepositoryImpl.php";
                require_once __DIR__."/data/SuratCutiService.php";
                require_once __DIR__."/data/Kegiatan.php";
                require_once __DIR__."/data/KegiatanRepository.php";
                require_once __DIR__."/data/KegiatanRepositoryImpl.php";
                require_once __DIR__."/data/KegiatanService.php";

                $pegawaiService = new PegawaiService(new PegawaiRepositoryImpl());
                $suratCutiService = new SuratCutiService(new SuratCutiRepositoryImpl());
                $kegiatanService = new KegiatanService(new KegiatanRepositoryImpl());

                //menghitung jumlah surat masuk
                $count1 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_surat_masuk " . ($_SESSION['admin'] != 2 ? " WHERE tujuan_surat={$_SESSION['admin']}":"")));

                //menghitung jumlah surat masuk
                $count2 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_surat_keluar"));

                //menghitung jumlah surat masuk
                $count3 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_disposisi"));

                //menghitung jumlah klasifikasi
                $count4 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_klasifikasi"));

                //menghitung jumlah pengguna
                $count5 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_user"));

                // menghitung jumlah Pegawai
                $countPegawai = $pegawaiService->count();

                // menghitung jumlah Surat Cuti
                $countSuratCuti = $suratCutiService->count();

                // menghitung jumlah Kegiatan
                $countKegiatan = $kegiatanService->count();
            ?>

            <!-- Info Statistic START -->
            <?php if($_SESSION['admin'] == 2 || $_SESSION['admin'] >= 4 && $_SESSION['admin'] <= 6) { ?>
            <a href="?page=tsm">
                <div class="col s12 m4">
                    <div class="card cyan">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">mail</i> Jumlah Surat Masuk</span>
                            <?php echo '<h5 class="white-text link">'.$count1.' Surat Masuk</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=tsk">
                <div class="col s12 m4">
                    <div class="card lime darken-1">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">drafts</i> Jumlah Surat Keluar</span>
                            <?php echo '<h5 class="white-text link">'.$count2.' Surat Keluar</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=tp">
                <div class="col s12 m4">
                    <div class="card blue darken-1">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">person</i> Jumlah Pegawai</span>
                            <?php echo '<h5 class="white-text link">'.$countPegawai.' Pegawai</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=tk">
                <div class="col s12 m4">
                    <div class="card blue darken-1">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">person</i> Jumlah Kegiatan</span>
                            <?php echo '<h5 class="white-text link">'.$countKegiatan.' Kegiatan</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=tsc">
                <div class="col s12 m4">
                    <div class="card blue darken-1">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">mail</i> Jumlah Surat Cuti</span>
                            <?php echo '<h5 class="white-text link">'.$countSuratCuti.' Pegawai</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <div class="col s12 m4">
                <div class="card yellow darken-3">
                    <div class="card-content">
                        <span class="card-title white-text"><i class="material-icons md-36">description</i> Jumlah Disposisi</span>
                        <?php echo '<h5 class="white-text link">'.$count3.' Disposisi</h5>'; ?>
                    </div>
                </div>
            </div>

            <a href="?page=ref" style="display: none;">
                <div class="col s12 m4">
                    <div class="card deep-orange">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">class</i> Jumlah Klasifikasi Surat</span>
                            <?php echo '<h5 class="white-text link">'.$count4.' Klasifikasi Surat</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>
            <?php } ?>

        <?php if($_SESSION['admin'] == 1){?>
                <a href="?page=sett&sub=usr">
                    <div class="col s12 m4">
                        <div class="card blue accent-2">
                            <div class="card-content">
                                <span class="card-title white-text"><i class="material-icons md-36">people</i> Jumlah Pengguna</span>
                                <?php echo '<h5 class="white-text link">'.$count5.' Pengguna</h5>'; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <!-- Info Statistic START -->
        <?php
            }
        ?>

        </div>
        <!-- Row END -->
    <?php
        }
    ?>
    </div>
    <!-- container END -->

</main>
<!-- Main END -->

<!-- Include Footer START -->
<?php include('include/footer.php'); ?>
<!-- Include Footer END -->

</body>
<!-- Body END -->

</html>

<?php
    }
?>
