<?php

require_once __Dir__."/Helper.php";
require_once __DIR__."/Pegawai.php";
require_once __DIR__."/PegawaiRepository.php";

class PegawaiRepositoryImpl implements PegawaiRepository{
  
  public function findByNip(string $nip): ?Pegawai {
    $query = "SELECT * FROM tbl_pegawai WHERE nip=:nip";
    $connection = Helper\getConnection();
    $result = $connection->prepare($query);
    $result->bindValue("nip", $nip);
    $result->execute();

    $connection = null;
    if($row = $result->fetch()){
      $pegawai = new Pegawai(
        nip: $row['nip'],
        nama: $row['nama'],
        telepon: $row['telepon'],
        alamat: $row['alamat'],
      );  
      return $pegawai;
    } else {
      return null;
    }
  }

  function findAll(): array {
    $query = "SELECT * FROM tbl_pegawai ORDER BY nip";
    $connection = Helper\getConnection();
    $result = $connection->query($query);

    $connection = null;
    if($rows = $result->fetchAll()){
      $pegawais = [];

      foreach($rows as $row){
        $pegawais[] = new Pegawai(
          nip: $row['nip'],
          nama: $row['nama'],
          telepon: $row['telepon'],
          alamat: $row['alamat'],
        );
      }

      return $pegawais;
    } else {
      return [];
    }
  }

  public function save(Pegawai $pegawai): Pegawai {
    $query = "INSERT INTO tbl_pegawai(nip, nama, telepon, alamat) VALUES (:nip , :nama, :telepon, :alamat)";
    $connection = Helper\getConnection();
    $result = $connection->prepare($query);
    $result->bindValue("nip", $pegawai->getNip());
    $result->bindValue("nama", $pegawai->getNama());
    $result->bindValue("telepon", $pegawai->getTelepon());
    $result->bindValue("alamat", $pegawai->getAlamat());
    $result->execute();

    $pegawai = $this->findByNip($pegawai->getNip());

    $connection = null;
    return $pegawai;
  }
  
  public function edit(Pegawai $pegawai): Pegawai {
    $query = "UPDATE tbl_pegawai SET nama=:nama, telepon=:telepon, alamat=:alamat WHERE nip=:nip";
    $connection = Helper\getConnection();
    $result = $connection->prepare($query);
    $result->bindValue("nama", $pegawai->getNama());
    $result->bindValue("telepon", $pegawai->getTelepon());
    $result->bindValue("alamat", $pegawai->getAlamat());
    $result->bindValue("nip", $pegawai->getNip());
    $result->execute();

    $pegawai = $this->findByNip($pegawai->getNip());

    $connection = null;
    return $pegawai;
  }

  public function delete(string $nip): bool {
    $connection = Helper\getConnection();
    $query = "DELETE FROM tbl_pegawai WHERE nip={$connection->quote($nip)}";
    $berhasilMenghapus = (bool)$connection->exec($query);

    $connection = null;
    return $berhasilMenghapus;
  }

  public function search(string $keyWord,int $current=0,int $limit= 15): array {
    $keyWord = "%".$keyWord."%";
    $connection = Helper\getConnection();
    $query = "SELECT * FROM tbl_pegawai WHERE nip LIKE {$connection->quote($keyWord)} OR nama LIKE {$connection->quote($keyWord)} OR nip LIKE {$connection->quote($keyWord)} OR telepon={$connection->quote($keyWord)} OR alamat LIKE {$connection->quote($keyWord)} ORDER BY nip DESC LIMIT $current, $limit";
    $result = $connection->query($query);

    $connection = null;
    if($rows = $result->fetchAll()){
      $pegawais = [];

      foreach($rows as $row){
        $pegawais[] = new Pegawai(
          nip: $row['nip'],
          nama: $row['nama'],
          telepon: $row['telepon'],
          alamat: $row['alamat'],
        );
      }

      return $pegawais;
    } else {
      return [];
    }
  }
}