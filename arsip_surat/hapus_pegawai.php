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
    $page = 'tp';

    // import dependency yang diperlukan
    require_once __DIR__ . "/data/Pegawai.php";
    require_once __DIR__ . "/data/PegawaiRepository.php";
    require_once __DIR__ . "/data/PegawaiRepositoryImpl.php";
    require_once __DIR__ . "/data/PegawaiService.php";

    $pegawaiService = new PegawaiService(new PegawaiRepositoryImpl());
    $pegawai = $pegawaiService->findByNip($_REQUEST['nip']);

    // memeriksa apakah data ada
    if ($pegawai == null) {
    echo '<script language="javascript">
            window.alert("ERROR! NIP Pegawai tidak bisa ditemukan");
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
                                    <td width="13%">No. NIP</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $pegawai->getNip() . '</td>
                                </tr>
    			                <tr>
                                    <td width="13%">Nama</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $pegawai->getNama() . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">No. Telepon</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $pegawai->getTelepon() . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Alamat</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $pegawai->getAlamat() . '</td>
    			                </tr>
    			            </tbody>
    			   		</table>
                        </div>
                        <div class="card-action">
        	                <a href="?page=' . $page . '&act=del&submit=yes&nip=' . $pegawai->getNip() . '" class="btn-large deep-orange waves-effect waves-light white-text">HAPUS <i class="material-icons">delete</i></a>
        	                <a href="?page=' . $page . '" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
    	                </div>
    	            </div>
                </div>
            </div>
            <!-- Row form END -->';

        if (isset($_REQUEST['submit'])) {
            // menghapus Data
            $isDeleted = $pegawaiService->delete($pegawai->getNip());
            if ($isDeleted) {
                $_SESSION['succDel'] = 'SUKSES! Data berhasil dihapus<br/>';
                header("Location: ./admin.php?page=$page");
                die();
            } else {
                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                echo '<script language="javascript">
                                window.location.href="./admin.php?page=' . $page . '&act=del&nip=' . $pegawai->getNip() . '";
                              </script>';
            }
        }
    }
}
