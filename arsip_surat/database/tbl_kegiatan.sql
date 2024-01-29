CREATE TABLE tbl_kegiatan (
    id int NOT NULL AUTO_INCREMENT,
    kegiatan TEXT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    tempat TEXT NOT NULL,
    pelaksana TEXT NOT NULL,
    peserta TEXT NOT NULL,
    CONSTRAINT pk_tbl_kegiatan PRIMARY KEY (id)
);

INSERT INTO tbl_kegiatan(
    kegiatan,
    tanggal_mulai,
    tanggal_selesai,
    tempat,
    pelaksana,
    peserta
) VALUES ("MAKAN-MAKAN", '2020-12-12', '2025-05-20', "Banjarmasin", "UNISKA", "UNLAM");