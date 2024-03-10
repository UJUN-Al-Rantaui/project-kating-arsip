<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
    header("Location: ./");
    die();
} else {

    ?>
    <style type="text/css">
        table {
            background: #fff;
            padding: 5px;
        }

        tr,
        td {
            border: table-cell;
            border: 1px solid #444;
        }

        tr,
        td {
            vertical-align: top !important;
        }

        #right {
            border-right: none !important;
        }

        #left {
            border-left: none !important;
        }

        .isi {
            height: 300px !important;
        }

        .disp {
            text-align: center;
            padding: 1.5rem 0;
            margin-bottom: .5rem;
        }

        .logodisp {
            float: left;
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 0 0 1rem;
        }

        #lead {
            width: auto;
            position: relative;
            margin: 25px 0 0 75%;
        }

        .lead {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: -10px;
        }

        .tgh {
            text-align: center;
        }

        #nama {
            font-size: 2.1rem;
            margin-bottom: -1rem;
        }

        #alamat {
            font-size: 16px;
        }

        .up {
            text-transform: uppercase;
            margin: 0;
            line-height: 2.2rem;
            font-size: 1.5rem;
        }

        .status {
            margin: 0;
            font-size: 1.3rem;
            margin-bottom: .5rem;
        }

        #lbr {
            font-size: 20px;
            font-weight: bold;
        }

        .separator {
            border-bottom: 2px solid #616161;
            margin: -1.3rem 0 1.5rem;
        }

        @media print {
            body {
                font-size: 12px;
                color: #212121;
            }

            nav {
                display: none;
            }

            table {
                width: 100%;
                font-size: 12px;
                color: #212121;
            }

            tr,
            td {
                border: table-cell;
                border: 1px solid #444;
                padding: 8px !important;

            }

            tr,
            td {
                vertical-align: top !important;
            }

            #lbr {
                font-size: 20px;
            }

            .isi {
                height: 200px !important;
            }

            .tgh {
                text-align: center;
            }

            .disp {
                text-align: center;
                margin: -.5rem 0;
            }

            .logodisp {
                float: left;
                position: relative;
                width: 80px;
                height: 80px;
                margin: .5rem 0 0 .5rem;
            }

            #lead {
                width: auto;
                position: relative;
                margin: 15px 0 0 75%;
            }

            .lead {
                font-weight: bold;
                text-decoration: underline;
                margin-bottom: -10px;
            }

            #nama {
                font-size: 20px !important;
                font-weight: bold;
                text-transform: uppercase;
                margin: -10px 0 -20px 0;
            }

            .up {
                font-size: 17px !important;
                font-weight: normal;
            }

            .status {
                font-size: 17px !important;
                font-weight: normal;
                margin-bottom: -.1rem;
            }

            #alamat {
                margin-top: -15px;
                font-size: 13px;
            }

            #lbr {
                font-size: 17px;
                font-weight: bold;
            }

            .separator {
                border-bottom: 2px solid #616161;
                margin: -1rem 0 1rem;
            }

        }
    </style>
    
    <body onload="window.print()">
    
        <!-- Container START -->
        <div id="colres">
            <div class="disp">
                <?php

                require_once "./data/Helper.php";
                require_once "./data/SuratCuti.php";
                require_once "./data/SuratCutiRepository.php";
                require_once "./data/SuratCutiRepositoryImpl.php";
                require_once "./data/SuratCutiService.php";

                $query2 = mysqli_query($config, "SELECT institusi, nama, status, alamat, logo FROM tbl_instansi");
                list($institusi, $nama, $status, $alamat, $logo) = mysqli_fetch_array($query2);
                ?>
                <img class="logodisp" src="./upload/<?= $logo ?>" />
                <h6 class="up"><?= $institusi ?></h6>
                <h5 class="up" id="nama"><?= $nama ?></h5><br />
                <!-- <h6 class="status"><?= $status ?></h6> -->
                <span id="alamat">
                    <?= $alamat ?>
                </span>
            </div>

            <div class="separator"></div>
            <?php

            $suratCutiService = new SuratCutiService(new SuratCutiRepositoryImpl());
            $suratCuti = $suratCutiService->findByKodeCuti($_GET['kode_cuti']);

            if ($suratCuti != null) {
                    ?>

                    <table class="bordered" id="tbl">
                        <tbody>
                            <tr>
                                <td class="tgh" id="lbr" colspan="5">Surat Cuti</td>
                            </tr>
                            <tr>
                                <td id="right"><strong>Kode Cuti</strong></td>
                                <td id="left" colspan="2">: <?=$suratCuti->getKodeCuti()?></td>
                            </tr>
                            <tr>
                                <td id="right"><strong>NIP</strong></td>
                                <td id="left" colspan="2">: <?=$suratCuti->getNip() ?></td>
                            </tr>
                            <tr><td id="right"><strong>Nama</strong></td>
                                <td id="left" colspan="2">: <?=$suratCuti->getNama() ?></td>
                            </tr>
                            <tr>
                                <td id="right"><strong>Jenis Cuti</strong></td>
                                <td id="left" colspan="2">: <?=$suratCuti->getJenisCuti() ?></td>
                            </tr>
                            <tr>
                                <td id="right"><strong>Tanggal Mulai</strong></td>
                                <td id="left" colspan="2">: <?=$suratCuti->getTanggalMulai()->format("d-m-Y") ?></td>
                            </tr>
                            <tr>
                                <td id="right"><strong>Tanggal Selesai</strong></td>
                                <td id="left" colspan="2">: <?=$suratCuti->getTanggalSelesai()->format("d-m-Y") ?></td>
                            </tr>
                    <?php
                
                echo '
                </tbody>
            </table>
            <div id="lead">
                <p>Kepala Bidang</p>
                <div style="height: 50px;"></div>';
                $query = mysqli_query($config, "SELECT kepsek, nip FROM tbl_instansi");
                list($kepsek, $nip) = mysqli_fetch_array($query);
                if (!empty($kepsek)) {
                    echo '<p class="lead">' . $kepsek . '</p>';
                } else {
                    echo '<p class="lead">kosong</p>';
                }
                if (!empty($nip)) {
                    echo '<p>NIP. ' . $nip . '</p>';
                } else {
                    echo '<p>NIP. -</p>';
                }
                echo '
            </div>
        </div>
        <div class="jarak2"></div>
    <!-- Container END -->

    </body>';
            }
}
?>