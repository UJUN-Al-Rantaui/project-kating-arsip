<?php

require_once __DIR__."/SuratCuti.php";
require_once __DIR__."/SuratCutiRepository.php";
require_once __DIR__."/SuratCutiRepositoryImpl.php";

class SuratCutiService {

    public function __construct(private SuratCutiRepository $repository) {
    }

    public function findByKodeCuti(int |string $kodeCuti): ?SuratCuti {
        return $this->repository->findByKodeCuti($kodeCuti);
    }

    public function findAll(): array {
        return $this->repository->findAll();
    }

    public function register(SuratCuti $suratCuti): SuratCuti {
        return $this->repository->save($suratCuti);
    }

    public function edit(SuratCuti $suratCuti): SuratCuti {
        if($this->repository->findByKodeCuti($suratCuti->getKodeCuti()) == null) {
            throw new Exception("Data Surat Cuti yang yang ingin di edit tidak bisa ditemukan");
        } else {
            return $this->repository->edit($suratCuti);
        }
    }

    public function delete(int | string $kodeCuti): bool {
        if($this->repository->findByKodeCuti($kodeCuti) == null) {
            throw new Exception("Data Surat Cuti yang ingin dihapus tidak bisa ditemukan");
        } else {
            return $this->repository->delete($kodeCuti);
        }
    } 

    public function search(string $keyWord,int $current = 0,int $limit = 15): array {
        return $this->repository->search($keyWord, $current, $limit);
    }

    public function count(): int {
        return count($this->repository->findAll());
    }

}