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
                window.location.href="./admin.php?page=tk";
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
    $page = 'tk';

    // import dependency yang diperlukan
    require_once __DIR__."/data/Kegiatan.php";
    require_once __DIR__."/data/KegiatanRepository.php";
    require_once __DIR__."/data/KegiatanRepositoryImpl.php";
    require_once __DIR__."/data/KegiatanService.php";

    $kegiatanService = new KegiatanService(new KegiatanRepositoryImpl());
    $kegiatan = $kegiatanService->findById($_REQUEST['id']);

    // memeriksa apakah data ada
    if ($kegiatan == null) {
    echo '<script language="javascript">
            window.alert("ERROR! ID Kegiatan tidak bisa ditemukan");
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
                                    <td width="13%">ID Kegiatan</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $kegiatan->getId() . '</td>
                                </tr>
                                <tr>
                                    <td width="13%">Kegiatan</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $kegiatan->getKegiatan() . '</td>
                                </tr>
                                <tr>
                                    <td width="13%">Tanggal Mulai</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $kegiatan->getTanggalMulai()->format('d-m-Y') . '</td>
                                </tr>
                                <tr>
                                    <td width="13%">Tanggal Mulai</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $kegiatan->getTanggalSelesai()->format('d-m-Y') . '</td>
                                </tr>
    			                <tr>
                                    <td width="13%">Tempat</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . $kegiatan->getTempat() . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Pelaksana</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $kegiatan->getPelaksanaString(', ') . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Peserta</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $kegiatan->getPesertaString(', ') . '</td>
    			                </tr>
    			            </tbody>
    			   		</table>
                        </div>
                        <div class="card-action">
        	                <a href="?page=' . $page . '&act=del&submit=yes&id=' . $kegiatan->getId() . '" class="btn-large deep-orange waves-effect waves-light white-text">HAPUS <i class="material-icons">delete</i></a>
        	                <a href="?page=' . $page . '" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
    	                </div>
    	            </div>
                </div>
            </div>
            <!-- Row form END -->';

        if (isset($_REQUEST['submit'])) {
            // menghapus Data
            $isDeleted = $kegiatanService->delete($kegiatan->getId());
            if ($isDeleted) {
                $_SESSION['succDel'] = 'SUKSES! Data berhasil dihapus<br/>';
                header("Location: ./admin.php?page=$page");
                die();
            } else {
                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                echo '<script language="javascript">
                                window.location.href="./admin.php?page=' . $page . '&act=del&id=' . $kegiatan->getId() . '";
                              </script>';
            }
        }
    }
}
