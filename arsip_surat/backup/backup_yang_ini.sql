DROP TABLE tbl_disposisi;

CREATE TABLE `tbl_disposisi` ( `id_disposisi` int NOT NULL AUTO_INCREMENT, `tujuan` varchar(250) NOT NULL, `isi_disposisi` mediumtext NOT NULL, `sifat` varchar(100) NOT NULL, `batas_waktu` date NOT NULL, `catatan` varchar(250) NOT NULL, `id_surat` int NOT NULL, `id_user` tinyint NOT NULL, PRIMARY KEY (`id_disposisi`) ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

DROP TABLE tbl_instansi;

CREATE TABLE `tbl_instansi` ( `id_instansi` tinyint(1) NOT NULL, `institusi` varchar(150) NOT NULL, `nama` varchar(150) NOT NULL, `status` varchar(150) NOT NULL, `alamat` varchar(150) NOT NULL, `kepsek` varchar(50) NOT NULL, `nip` varchar(25) NOT NULL, `website` varchar(50) NOT NULL, `email` varchar(50) NOT NULL, `logo` varchar(250) NOT NULL, `id_user` tinyint NOT NULL, PRIMARY KEY (`id_instansi`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_instansi VALUES("1","Yayasan Tutor","ITE Official","Terakreditasi B","Jl. Bohusami No. 167 Manado","Akif Abdil Azhim, S.Pd","123456789","https://sim.mtsalkhairaatboroko.xyz/","iteofficial@gmail.com","ITE.png","1");

DROP TABLE tbl_klasifikasi;

CREATE TABLE `tbl_klasifikasi` ( `id_klasifikasi` int NOT NULL AUTO_INCREMENT, `kode` varchar(30) NOT NULL, `nama` varchar(250) NOT NULL, `uraian` mediumtext NOT NULL, `id_user` tinyint NOT NULL, PRIMARY KEY (`id_klasifikasi`) ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

DROP TABLE tbl_sett;

CREATE TABLE `tbl_sett` ( `id_sett` tinyint(1) NOT NULL, `surat_masuk` tinyint NOT NULL, `surat_keluar` tinyint NOT NULL, `referensi` tinyint NOT NULL, `id_user` tinyint NOT NULL, PRIMARY KEY (`id_sett`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tbl_sett VALUES("1","10","10","10","1");

DROP TABLE tbl_surat_keluar;

CREATE TABLE `tbl_surat_keluar` ( `id_surat` int NOT NULL AUTO_INCREMENT, `no_agenda` int NOT NULL, `tujuan` varchar(250) NOT NULL, `no_surat` varchar(50) NOT NULL, `isi` mediumtext NOT NULL, `kode` varchar(30) NOT NULL, `tgl_surat` date NOT NULL, `tgl_catat` date NOT NULL, `file` varchar(250) NOT NULL, `keterangan` varchar(250) NOT NULL, `id_user` tinyint NOT NULL, PRIMARY KEY (`id_surat`) ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO tbl_surat_keluar VALUES("9","1","sdf","fsda","fasf","fasdf","2023-12-03","2023-12-09","","asdf","7");

DROP TABLE tbl_surat_masuk;

CREATE TABLE `tbl_surat_masuk` ( `id_surat` int NOT NULL AUTO_INCREMENT, `no_surat` varchar(50) NOT NULL, `asal_surat` varchar(250) NOT NULL, `tujuan_surat` tinyint(1) NOT NULL, `perihal` mediumtext NOT NULL, `tgl_surat` date NOT NULL, `tgl_diterima` date NOT NULL, `file` varchar(250) NOT NULL, `keterangan` varchar(250) NOT NULL, `id_user` tinyint NOT NULL, PRIMARY KEY (`id_surat`) ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO tbl_surat_masuk VALUES("7","867","Jamal","4","Ucup Bermain DI nebula","2023-12-08","2023-12-08","","Langit indah","7");

DROP TABLE tbl_user;

CREATE TABLE `tbl_user` ( `id_user` tinyint NOT NULL AUTO_INCREMENT, `username` varchar(30) NOT NULL, `password` varchar(35) NOT NULL, `nama` varchar(50) NOT NULL, `nip` varchar(25) NOT NULL, `admin` tinyint(1) NOT NULL, PRIMARY KEY (`id_user`) ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO tbl_user VALUES("1","administrator","827ccb0eea8a706c4c34a16891f84e7b","Administrator","123456789","1");

INSERT INTO tbl_user VALUES("5","riski","01cfcd4f6b8770febfb40cb906715822","riski","123456789","3");

INSERT INTO tbl_user VALUES("7","junaidi","a708cb9bebf84a140d408a8251450091","Muhammad Junaidi","2210100","2");

INSERT INTO tbl_user VALUES("8","fadli","0a539e9da09b0ab58fd282832c07b6ab","Fadli","2210101","4");

INSERT INTO tbl_user VALUES("9","amang_anal","056ae031fabc561bd2e4b3500450d486","Muhammad Rafii","2210102","5");

INSERT INTO tbl_user VALUES("10","maidi","4ad677d8f1af3bcb9438eafa5d36defe","Muhammad Humaidi","2210103","6");