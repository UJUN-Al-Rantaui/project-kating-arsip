<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();

    // cek hak akses
} elseif ($_SESSION['admin'] != 2) {
    echo '<script language="javascript">
                window.alert("ERROR! Anda tidak memiliki hak akses untuk menghapus data ini");
                window.location.href="./admin.php?page=tp";
              </script>';
} else {

    if (isset($_SESSION['errQ'])) {
        $errQ = $_SESSION['errQ'];
        echo '<div id="alert-message" class="row jarak-card">
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
    $page = 'tsc';

    // import dependency yang diperlukan
    require_once __DIR__."/data/SuratCuti.php";
    require_once __DIR__."/data/SuratCutiRepository.php";
    require_once __DIR__."/data/SuratCutiRepositoryImpl.php";
    require_once __DIR__."/data/SuratCutiService.php";

    $suratCutiService = new SuratCutiService(new SuratCutiRepositoryImpl());
    $suratCuti = $suratCutiService->findByKodeCuti($_REQUEST['kode_cuti']);

    // memeriksa apakah data ada
    if ($suratCuti == null) {
    echo '<script language="javascript">
            window.alert("ERROR! Kode Cuti tidak bisa ditemukan");
            window.location.href="./admin.php?page='.$page.'";
            </script>';
    } else {
        // menampilkan Data
        echo '
                <!-- Row form Start -->
				<div class="row jarak-card">
				    <div class="col m12">
                    <div class="card">
                        <div class="card-content">
				        <table>
				            <thead class="red lighten-5 red-text">
				                <div class="confir red-text"><i class="material-icons md-36">error_outline</i>
				                Apakah Anda yakin akan menghapus data ini?</div>
				            </thead>
				            <tbody>
                                <tr>
                                    <td width="13%">Kode Cuti</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $suratCuti->getKodeCuti() . '</td>
                                </tr>
                                <tr>
                                    <td width="13%">No. NIP</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $suratCuti->getNip() . '</td>
                                </tr>
    			                <tr>
                                    <td width="13%">Nama</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $suratCuti->getNama() . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Jenis Cuti</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $suratCuti->getJenisCuti() . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Tanggal Mulai</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $suratCuti->getTanggalMulai()->format('d-m-Y') . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Tanggal Mulai</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $suratCuti->getTanggalSelesai()->format('d-m-Y') . '</td>
    			                </tr>
    			            </tbody>
    			   		</table>
                        </div>
                        <div class="card-action">
        	                <a href="?page=' . $page . '&act=del&submit=yes&kode_cuti=' . $suratCuti->getKodeCuti() . '" class="btn-large deep-orange waves-effect waves-light white-text">HAPUS <i class="material-icons">delete</i></a>
        	                <a href="?page=' . $page . '" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
    	                </div>
    	            </div>
                </div>
            </div>
            <!-- Row form END -->';

        if (isset($_REQUEST['submit'])) {
            // menghapus Data
            $isDeleted = $suratCutiService->delete($suratCuti->getKodeCuti());
            if ($isDeleted) {
                $_SESSION['succDel'] = 'SUKSES! Data berhasil dihapus<br/>';
                header("Location: ./admin.php?page=$page");
                die();
            } else {
                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                echo '<script language="javascript">
                                window.location.href="./admin.php?page=' . $page . '&act=del&kode_cuti=' . $suratCuti->getKodeCuti() . '";
                              </script>';
            }
        }
    }
}
