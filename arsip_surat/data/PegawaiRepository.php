<?php

require_once __DIR__."/Pegawai.php";

interface PegawaiRepository {
  
  function findByNip(string $nip): ?Pegawai;
  function findAll(): array;
  function save(Pegawai $pegawai): Pegawai;
  function edit(Pegawai $pegawai): Pegawai;
  function delete(string $nip): bool;
  function search(string $keyWord, int $current, int $limit): array;
  
}