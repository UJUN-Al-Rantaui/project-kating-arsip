<?php

require_once __DIR__."/Helper.php";
require_once __DIR__."/Kegiatan.php";
require_once __DIR__."/KegiatanRepository.php";

class KegiatanRepositoryImpl implements KegiatanRepository {

    public function findById(int | string $id): ?Kegiatan {
        if(is_string($id)) $id = (int)$id;

        $connection = Helper\getConnection();
        $query = "SELECT * FROM tbl_kegiatan WHERE id=$id";
        $result = $connection->query($query);

        $connection = null;
        if($row = $result->fetch()) {
            $kegiatan = new Kegiatan(
                id: $row["id"],
                kegiatan: $row["kegiatan"],
                tanggalMulai: new DateTime($row["tanggal_mulai"]),
                tanggalSelesai: new DateTime($row["tanggal_selesai"]),
                tempat: $row["tempat"],
                pelaksana: $row["pelaksana"],
                peserta: $row["peserta"]
            );

            return $kegiatan;
        } else {
            return null;
        }
    }

    public function findAll(): array {
        $connection = Helper\getConnection();
        $query = "SELECT * FROM tbl_kegiatan";
        $result = $connection->query($query);

        $connection = null;
        if($result->rowCount() > 0) {
            $rows = $result->fetchAll();
            $kegiatanArray = [];
            foreach($rows as $row) {
                $kegiatanArray[] = new Kegiatan(
                    id: $row["id"],
                    kegiatan: $row["kegiatan"],
                    tanggalMulai: new DateTime($row["tanggal_mulai"]),
                    tanggalSelesai: new DateTime($row["tanggal_selesai"]),
                    tempat: $row["tempat"],
                    pelaksana: $row["pelaksana"],
                    peserta: $row["peserta"]
                );
            }

            return $kegiatanArray;
        } else {
            return [];
        }
    }

    public function save(Kegiatan $kegiatan): Kegiatan {
        $dateFormat = "Y-m-d";
        $connection = Helper\getConnection();
        $query = <<<QUERY
        INSERT INTO tbl_kegiatan(
            kegiatan,
            tanggal_mulai,
            tanggal_selesai,
            tempat,
            pelaksana,
            peserta
        ) VALUES (
            :kegiatan,
            :tanggal_mulai,
            :tanggal_selesai,
            :tempat,
            :pelaksana,
            :peserta
        )
        QUERY;
        $result = $connection->prepare($query);
        $result->bindValue("kegiatan", $kegiatan->getKegiatan()); 
        $result->bindValue("tanggal_mulai", $kegiatan->getTanggalMulai()->format($dateFormat)); 
        $result->bindValue("tanggal_selesai", $kegiatan->getTanggalSelesai()->format($dateFormat)); 
        $result->bindValue("tempat", $kegiatan->getTempat()); 
        $result->bindValue("pelaksana", $kegiatan->getPelaksanaString()); 
        $result->bindValue("peserta", $kegiatan->getPesertaString()); 
        $result->execute();

        $kegiatan = $this->findById($connection->lastInsertId());
        $connection = null;
        return $kegiatan;
    }

    public function edit(Kegiatan $kegiatan): Kegiatan {
        $dateFormat = "Y-m-d";
        $connection = Helper\getConnection();
        $query = <<<QUERY
        UPDATE tbl_kegiatan 
        SET
            kegiatan=:kegiatan,
            tanggal_mulai=:tanggal_mulai,
            tanggal_selesai=:tanggal_selesai,
            tempat=:tempat,
            pelaksana=:pelaksana,
            peserta=:peserta
        WHERE id=:id
        QUERY;
        $result = $connection->prepare($query);
        $result->bindValue("kegiatan", $kegiatan->getKegiatan()); 
        $result->bindValue("tanggal_mulai", $kegiatan->getTanggalMulai()->format($dateFormat)); 
        $result->bindValue("tanggal_selesai", $kegiatan->getTanggalSelesai()->format($dateFormat)); 
        $result->bindValue("tempat", $kegiatan->getTempat()); 
        $result->bindValue("pelaksana", $kegiatan->getPelaksanaString()); 
        $result->bindValue("peserta", $kegiatan->getPesertaString()); 
        $result->bindValue("id", $kegiatan->getId()); 
        $result->execute();

        $kegiatan = $this->findById($kegiatan->getId());
        $connection = null;
        return $kegiatan;
    }
    public function delete(int | string $id): bool {
        if(is_string($id)) $id=(int) $id;

        $connection = Helper\getConnection();
        $query = "DELETE FROM tbl_kegiatan WHERE id=$id";
        $isDeleted = (bool)$connection->exec($query);

        $connection = null;
        return $isDeleted;
    }
    public function search(string $keyWord,int $current=0, int $limit=15): array {
        $keyWordInt = is_numeric($keyWord) ? (int)$keyWord : 0;
        $keyWord = "%".$keyWord."%";

        $connection = Helper\getConnection();
        $query = <<<QUERY
        SELECT * FROM tbl_kegiatan 
            WHERE 
                id=:id
                OR kegiatan LIKE :kegiatan
                OR tempat LIKE :tempat
                OR pelaksana LIKE :pelaksana
                OR peserta LIKE :peserta
        QUERY;
        $result = $connection->prepare($query);
        $result->bindValue("id", $keyWordInt);
        $result->bindValue("kegiatan", $keyWord);
        $result->bindValue("tempat", $keyWord);
        $result->bindValue("pelaksana", $keyWord);
        $result->bindValue("peserta", $keyWord);
        $result->execute();

        $connection = null;
        if($result->rowCount() > 0) {
            $rows = $result->fetchAll();
            $kegiatanArray = [];
            foreach($rows as $row) {
                $kegiatanArray[] = new Kegiatan(
                    id: $row["id"],
                    kegiatan: $row["kegiatan"],
                    tanggalMulai: new DateTime($row["tanggal_mulai"]),
                    tanggalSelesai: new DateTime($row["tanggal_selesai"]),
                    tempat: $row["tempat"],
                    pelaksana: $row["pelaksana"],
                    peserta: $row["peserta"],
                );
            }
            return $kegiatanArray;
        } else {
            return [];
        }
    }

}