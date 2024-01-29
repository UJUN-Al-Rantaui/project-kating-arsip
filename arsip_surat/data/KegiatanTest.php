<?php

require_once __DIR__."/Kegiatan.php";
require_once __DIR__."/KegiatanRepository.php";
require_once __DIR__."/KegiatanRepositoryImpl.php";

$kegiatan = new Kegiatan(
    id: 1,
    kegiatan: "Tahi kucing babulu",
    tanggalMulai: new DateTime("2024-01-01"),
    tanggalSelesai: new DateTime("2023-12-15"),
    tempat: "KAlimantan Selatan",
    pelaksana: "ujun:ozon:ujunun:taichou:unjun",
    peserta: "Amang:syarwani:ipul:acad"
);

var_dump($kegiatan);

$repository = new KegiatanRepositoryImpl();

var_dump($repository->findById(1));

var_dump($repository->findAll());

var_dump($repository->save($kegiatan));

// var_dump($repository->edit($kegiatan));

// var_dump($repository->delete($kegiatan->getId()));

var_dump($repository->search("kucing"));
