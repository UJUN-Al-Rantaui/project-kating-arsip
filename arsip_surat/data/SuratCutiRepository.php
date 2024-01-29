<?php

require_once __DIR__."/SuratCuti.php";

interface SuratCutiRepository {
  
  function findByKodeCuti(int | string $kodeCuti): ?SuratCuti;
  function findAll(): array;
  function save(SuratCuti $suratCuti): SuratCuti;
  function edit(SuratCuti $suratCuti): SuratCuti;
  function delete(int | string $kodeCuti): bool;
  function search(string $keyWord, int $current, int $limit): array;
  
}