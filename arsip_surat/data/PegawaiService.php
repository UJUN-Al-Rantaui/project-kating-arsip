<?php

require_once __DIR__."/Helper.php";
require_once __DIR__."/Pegawai.php";
require_once __DIR__."/PegawaiRepository.php";
require_once __DIR__."/PegawaiRepositoryImpl.php";

class PegawaiService {

    public function __construct(private PegawaiRepository $repository) {
    }

    public function findByNip(string $nip): ?Pegawai {
        return $this->repository->findByNip($nip);
    }
    
    public function register(Pegawai $pegawai): Pegawai {
        if($this->repository->findByNip($pegawai->getNip()) != null) {
            throw new Exception("Pegawai sudah ada");
        } else {
            return $this->repository->save($pegawai);
        }
    }
    
    public function edit(Pegawai $pegawai): Pegawai {
        if($this->repository->findByNip($pegawai->getNip()) == null) {
            throw new Exception("Pegawai Tidak ditemukan");
        } else {
            return $this->repository->edit($pegawai);
        }
    }

    public function delete(string $nip): bool {
        if($this->repository->findByNip($nip) == null) {
            throw new Exception("Data Pegawai Tidak ditemukan");
        } else {
            return $this->repository->delete($nip);
        }
    }

    public function search(string $keyWord, int $current=0, int $limit=15): array {
        return $this->repository->search($keyWord, $current, $limit);
    }

    public function count() {
        return count($this->repository->findAll());
    }

}