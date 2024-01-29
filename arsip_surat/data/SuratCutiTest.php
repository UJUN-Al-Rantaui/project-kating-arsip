<?php

require_once __DIR__."/SuratCuti.php";
require_once __DIR__."/SuratCutiRepository.php";
require_once __DIR__."/SuratCutiRepositoryImpl.php";

$suratCuti = new SuratCuti(
    kodeCuti: "12",
    nip: "01234",
    nama: "Junaid AL Bagdadi",
    jenisCuti: "Pernikahan",
    tanggalMulai: new DateTime('2020-01-10'),
    tanggalSelesai: new DateTime(),
);

var_dump($suratCuti);

$repository = new SuratCutiRepositoryImpl();

var_dump($repository->findByKodeCuti($suratCuti->getKodeCuti()));

var_dump($repository->findAll());

var_dump($repository->save($suratCuti));

// var_dump($repository->edit($suratCuti));

var_dump($repository->delete($suratCuti->getKodeCuti()));

var_dump($repository->search("zon"));

