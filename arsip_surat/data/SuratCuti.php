<?php

class SuratCuti {
  public function __construct(private int | string $kodeCuti,
                              private string $nip,
                              private string $nama,
                              private string $jenisCuti,
                              private DateTime $tanggalMulai,
                              private DateTime $tanggalSelesai) {
    if (is_string($this->kodeCuti)) {
      $this->kodeCuti = (int)$this->kodeCuti;
    }

    $wita = new DateTimeZone("Asia/Makassar");
    $this->tanggalMulai->setTimezone($wita);
    $this->tanggalSelesai->setTimezone($wita);
  }

  public function getKodeCuti(): int
  {
    return $this->kodeCuti;
  }

  public function getNip(): string
  {
    return $this->nip;
  }

  public function setNip(string $nip)
  {
    $this->nip = $nip;
  }

  public function getNama(): string
  {
    return $this->nama;
  }

  public function setNama(string $nama)
  {
    $this->nama = $nama;
  }

  public function getJenisCuti(): string
  {
    return $this->jenisCuti;
  }

  public function setJenisCuti(string $jenisCuti)
  {
    $this->jenisCuti = $jenisCuti;
  }

  public function getTanggalMulai(): DateTime
  {
    return $this->tanggalMulai;
  }

  public function setTanggalMulai(DateTime $tanggalMulai)
  {
    $this->tanggalMulai = $tanggalMulai;
  }

  public function getTanggalSelesai(): DateTime
  {
    return $this->tanggalSelesai;
  }

  public function setTanggalSelesai(DateTime $tanggalSelesai)
  {
    $this->tanggalSelesai = $tanggalSelesai;
  }
  
}