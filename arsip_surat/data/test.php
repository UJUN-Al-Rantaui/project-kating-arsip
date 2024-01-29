<?php

require_once __DIR__."/PegawaiRepositoryImpl.php";
require_once __DIR__."/Pegawai.php";


$pegawaiRepository = new PegawaiRepositoryImpl();

$pegawai = $pegawaiRepository->findByNip("01291");

var_dump($pegawai);

var_dump($pegawaiRepository->findAll());

$pegawai1= new Pegawai(
    nip: "3210",
    nama: "Ahmad Badawi Manusia Tertampan Di Bumi",
    telepon: "0985456",
    alamat: "Handil Labuan Amas",
);

var_dump($pegawaiRepository->save($pegawai1));

// var_dump($pegawaiRepository->edit($pegawai1));

var_dump($pegawaiRepository->delete($pegawai1->getNip()));

var_dump($pegawaiRepository->search("91"));

