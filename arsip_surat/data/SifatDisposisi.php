<?php

class SifatDisposisi {
    const BIASA = "Biasa";
    const PENTING = "Penting";
    const RAHASIA = "Rahasia";

    public function __construct(private string $sifat = self::BIASA) {
    }

    public function setSifat(string $sifat) {

        if($sifat != self::BIASA
           && $sifat != self::PENTING
           && $sifat != self::RAHASIA)
        {
            throw new Exception("INVALID set SifatDisposisi");
        }

        $this->sifat = $sifat;
    }

    public function getSifat(): string {
        return $this->sifat;
    }

    public function __toString(): string {
        return $this->sifat;
    }

}