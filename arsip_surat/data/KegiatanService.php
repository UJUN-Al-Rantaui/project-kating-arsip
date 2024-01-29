<?php

require_once __DIR__."/Kegiatan.php";
require_once __DIR__."/KegiatanRepository.php";
require_once __DIR__."/KegiatanRepositoryImpl.php";

class KegiatanService {

    public function __construct(private KegiatanRepository $repositoy) {
    }

    public function findById(int | string $id): ?Kegiatan {
        return $this->repositoy->findById($id);
    }

    public function findAll(): array {
        return $this->repositoy->findAll();
    }

    public function register(Kegiatan $kegiatan): Kegiatan {
        return $this->repositoy->save($kegiatan);
    } 

    public function edit(Kegiatan $kegiatan): Kegiatan {
        return $this->repositoy->edit($kegiatan);
    }

    public function delete(int | string $id): bool {
        return $this->repositoy->delete($id);
    }

    public function search(string $keyWord, int $current=0, int $limit=15): Array {
        return $this->repositoy->search($keyWord, $current, $limit);
    }

    public function count(): int {
        return count($this->repositoy->findAll());
    }

}