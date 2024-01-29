<?php

use PHPUnit\Framework\TestCase;

class PegawaiTest extends TestCase {

    public function pegawaiData(): array {
        return [
            [""]
        ];
    }

    /**
     * @dataProvider pegawaiData
     */
    public function testPegawaiInstance() {
        $this->assertInstanceOf(Pegawai::class, new Pegawai());
    }

}