<?php

class Kegiatan
{
    public function __construct(
        private int | string $id,
        private string $kegiatan,
        private DateTime $tanggalMulai,
        private DateTime $tanggalSelesai,
        private string $tempat,
        private array | string $pelaksana,
        private array | string $peserta,
    ) {
        if(is_string($pelaksana)) {
            $this->pelaksana = explode(":", $this->pelaksana);
        }
        if(is_string($peserta)) {
            $this->peserta = explode(":", $this->peserta);
        }

        $wita = new DateTimeZone("Asia/Makassar");
        $this->tanggalMulai->setTimezone($wita);
        $this->tanggalSelesai->setTimezone($wita);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKegiatan(): string
    {
        return $this->kegiatan;
    }

    public function setKegiatan(string $kegiatan)
    {
        $this->kegiatan = $kegiatan;
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

    public function getTempat(): string
    {
        return $this->tempat;
    }

    public function setTempat(string $tempat)
    {
        $this->tempat = $tempat;
    }

    public function getPelaksana(): array
    {
        return $this->pelaksana;
    }

    public function setPelaksana(array | string $pelaksana, string $separator=":")
    {
        if(is_string($pelaksana)) {
            $this->pelaksana = explode($separator, $pelaksana);
        } else {
            $this->pelaksana = $pelaksana;
        }
    }

    public function getPelaksanaString(string $separator=":"): string {
        return join($separator, $this->pelaksana);
    } 

    public function getPeserta(): array
    {
        return $this->peserta;
    }

    public function getPesertaString(string $separator= ":"): string {
        return join($separator, $this->peserta);
    }

    public function setPeserta(array | string $peserta, string $separator= ":")
    {
        if(is_string($peserta)) {
            $this->peserta = explode($separator, $peserta);
        } else {
            $this->peserta = $peserta;
        }
    }
}