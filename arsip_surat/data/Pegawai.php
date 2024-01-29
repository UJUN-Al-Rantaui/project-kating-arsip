<?php

class Pegawai {
  
  public function __construct(private string $nama,
                              private string $nip,
                              private string $telepon,
                              private string $alamat)
  {
    /* */
  }
  
  public function getNama(): string {
    return $this->nama;
  }
  
  public function setNama(string $nama) {
    $this->nama = $nama;
  }
  
  public function getNip(): string {
    return $this->nip;
  }
  
  public function setNip(string $nip) {
    $this->nip = $nip;
  }
  
  public function getTelepon(): string {
    return $this->telepon;
  }
  
  public function setTelepon(string $telepon) {
    $this->telepon = $telepon;
  }
    
  public function getAlamat(): string {
    return $this->alamat;
  }
  
  public function setAlamat(string $alamat) {
    $this->alamat = $alamat;
  }
  
}