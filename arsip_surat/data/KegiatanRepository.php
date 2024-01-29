<?php

require_once __DIR__."/Kegiatan.php";

interface KegiatanRepository {

    public function findById(int | string $id): ?Kegiatan;
    public function findAll(): array;
    public function save(Kegiatan $kegiatan): Kegiatan;
    public function edit(Kegiatan $kegiatan): Kegiatan;
    public function delete(int | string $id): bool;
    public function search(string $keyWord,int $current, int $limit): array;

}