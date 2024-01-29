CREATE TABLE tbl_pegawai (
    nip VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL, 
    telepon VARCHAR(20) NOT NULL, 
    alamat TEXT NOT NULL,
    CONSTRAINT fk_nip PRIMARY KEY(nip)
);

INSERT INTO tbl_pegawai VALUES ("01291", "Gafur", "08125363792", "Martapura");

INSERT INTO tbl_pegawai VALUES ("0123", "ubun", "08125363792", "MTP");

INSERT INTO tbl_pegawai VALUES ("01234", "ubuntu", "08125363792", "Handil");