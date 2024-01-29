<?php

require_once __DIR__."/Helper.php";
require_once __DIR__."/SuratCuti.php";
require_once __DIR__."/SuratCutiRepository.php";

class SuratCutiRepositoryImpl implements SuratCutiRepository{
    
    public function findByKodeCuti(int | string $kodeCuti): ?SuratCuti{
        if(is_string($kodeCuti)) $kodeCuti = (int)$kodeCuti;

        $connection = Helper\getConnection();
        $query = "SELECT * FROM tbl_surat_cuti_view WHERE kode_cuti=:kode_cuti";
        $result = $connection->prepare($query);
        $result->bindValue("kode_cuti", $kodeCuti);
        $result->execute();

        $connection = null;
        if($row = $result->fetch()){
            $suratCuti = new SuratCuti(
                kodeCuti: $row['kode_cuti'],
                nip: $row['nip'],
                nama: $row['nama'],
                jenisCuti: $row['jenis_cuti'],
                tanggalMulai: new DateTime($row['tanggal_mulai']),
                tanggalSelesai: new DateTime($row['tanggal_selesai'])
            );
            return $suratCuti;
        } else {
            return null;
        }
    }

    public function findAll(): array {
        $connection = Helper\getConnection();
        $query = "SELECT * FROM tbl_surat_cuti_view";
        $result = $connection->query($query);
        $rows = $result->fetchAll();

        $connection = null;
        if(count($rows) > 0){
            $suratCutiArray = [];
            foreach($rows as $row){
                $suratCuti = new SuratCuti(
                    kodeCuti: $row['kode_cuti'],
                    nip: $row['nip'],
                    nama: $row['nama'],
                    jenisCuti: $row['jenis_cuti'],
                    tanggalMulai: new DateTime($row['tanggal_mulai']),
                    tanggalSelesai: new DateTime ($row['tanggal_selesai'])
                );
                $suratCutiArray[] = $suratCuti;
            }

            return $suratCutiArray;
        } else {
            return [];
        }
    }

    public function save(SuratCuti $suratCuti): SuratCuti {
        $dateFormat = 'Y-m-d';
        $connection = Helper\getConnection();
        $query = <<<QUERY
        INSERT INTO tbl_surat_cuti (
            nip,
            jenis_cuti,
            tanggal_mulai,
            tanggal_selesai
        ) VALUES (
            :nip,
            :jenis_cuti,
            :tanggal_mulai,
            :tanggal_selesai
        )
        QUERY;
        $result = $connection->prepare($query);
        $result->bindValue("nip",$suratCuti->getNip());
        $result->bindValue("jenis_cuti",$suratCuti->getJenisCuti());
        $result->bindValue("tanggal_mulai",$suratCuti->getTanggalMulai()->format($dateFormat));
        $result->bindValue("tanggal_selesai",$suratCuti->getTanggalSelesai()->format($dateFormat));
        $result->execute();

        $suratCuti = $this->findByKodeCuti($connection->lastInsertId());
        $connection = null;
        return $suratCuti;
    }

    public function edit(SuratCuti $suratCuti): SuratCuti {
        $dateFormat = 'Y-m-d';
        $connection = Helper\getConnection();
        $query = <<<QUERY
        UPDATE tbl_surat_cuti
        SET
            nip=:nip,
            jenis_cuti=:jenis_cuti,
            tanggal_mulai=:tanggal_mulai,
            tanggal_selesai=:tanggal_selesai
        WHERE kode_cuti=:kode_cuti
        QUERY;
        $result = $connection->prepare($query);
        $result->bindValue("nip",$suratCuti->getNip());
        $result->bindValue("jenis_cuti",$suratCuti->getJenisCuti());
        $result->bindValue("tanggal_mulai",$suratCuti->getTanggalMulai()->format($dateFormat));
        $result->bindValue("tanggal_selesai",$suratCuti->getTanggalSelesai()->format($dateFormat));
        $result->bindValue("kode_cuti",$suratCuti->getKodeCuti());
        $result->execute();

        $suratCuti = $this->findByKodeCuti($suratCuti->getKodeCuti());
        $connection = null;
        return $suratCuti;
    }

    public function delete(int | string $kodeCuti): bool {
        if(is_string($kodeCuti)) $kodeCuti = (int)$kodeCuti;

        $connection = Helper\getConnection();
        $query = "DELETE FROM tbl_surat_cuti WHERE kode_cuti=$kodeCuti";
        $isDeleted = $connection->exec($query);

        $connection = null;
        return $isDeleted;
    } 

    
  function search(string $keyWord, int $current=0, int $limit=15): array {
    $connection = Helper\getConnection();
    $query = <<<QUERY
        SELECT * FROM tbl_surat_cuti_view 
            WHERE 
                kode_cuti LIKE :keyword
                OR nip LIKE :keyword
                OR nama LIKE :keyword
                OR jenis_cuti LIKE :keyword
            ORDER BY kode_cuti DESC LIMIT $current, $limit
        QUERY;

    $keyWord = "%".$keyWord."%";

    $result = $connection->prepare($query);
    $result->bindValue("keyword",$keyWord);
    $result->execute();

    $connection = null;
    if($result->rowCount() > 0) {
        $suratCutiArray = [];
        foreach($result as $row) {
            $suratCutiArray[] = new SuratCuti(
                kodeCuti: $row["kode_cuti"],
                nip: $row["nip"],
                nama: $row["nama"],
                jenisCuti: $row["jenis_cuti"],
                tanggalMulai: new DateTime($row["tanggal_mulai"]),
                tanggalSelesai: new DateTime($row["tanggal_selesai"])
            );
        }

        return $suratCutiArray;
    } else {
        return [];
    }
  }

}