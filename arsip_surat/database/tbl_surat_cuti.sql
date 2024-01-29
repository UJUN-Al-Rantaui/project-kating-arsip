CREATE TABLE tbl_surat_cuti (
    kode_cuti INT NOT NULL AUTO_INCREMENT,
    nip VARCHAR(20) NOT NULL,
    jenis_cuti VARCHAR(100) NOT NULL,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    CONSTRAINT pk_kode_cuti PRIMARY KEY (kode_cuti),
    FOREIGN KEY (nip) REFERENCES tbl_pegawai (nip) ON DELETE NO ACTION ON UPDATE RESTRICT
);

INSERT INTO tbl_surat_cuti(
    nip,
    jenis_cuti,
    tanggal_mulai,
    tanggal_selesai
) VALUES (
    "0123",
    "sakit",
    "2024-01-10",
    "2024-01-20"
);

CREATE VIEW tbl_surat_cuti_view AS
    SELECT 
        sc.kode_cuti AS kode_cuti,
        sc.nip AS nip,
        p.nama AS nama,
        sc.jenis_cuti AS jenis_cuti,
        sc.tanggal_mulai AS tanggal_mulai,
        sc.tanggal_selesai AS tanggal_selesai
    FROM 
        tbl_surat_cuti AS sc
            LEFT JOIN tbl_pegawai AS p
                ON (sc.nip = p.nip);