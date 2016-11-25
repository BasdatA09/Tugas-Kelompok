CREATE TABLE berkas
(
    idberkas INTEGER NOT NULL,
    idmks INTEGER NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(100) NOT NULL,
    CONSTRAINT berkas_pkey PRIMARY KEY (idberkas, idmks)
);
CREATE TABLE dosen
(
    nip VARCHAR(20) PRIMARY KEY NOT NULL,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    institusi VARCHAR(100) NOT NULL
);
CREATE TABLE dosen_pembimbing
(
    idmks INTEGER NOT NULL,
    nipdosenpembimbing VARCHAR(20) NOT NULL,
    CONSTRAINT dosen_pembimbing_pkey PRIMARY KEY (idmks, nipdosenpembimbing)
);
CREATE TABLE dosen_penguji
(
    idmks INTEGER NOT NULL,
    nipdosenpenguji VARCHAR(20) NOT NULL,
    CONSTRAINT dosen_penguji_pkey PRIMARY KEY (idmks, nipdosenpenguji)
);
CREATE TABLE jadwal_non_sidang
(
    idjadwal INTEGER PRIMARY KEY NOT NULL,
    tanggalmulai DATE NOT NULL,
    tanggalselesai DATE NOT NULL,
    alasan VARCHAR(100) NOT NULL,
    repitisi VARCHAR(50),
    nipdosen VARCHAR(20) NOT NULL
);
CREATE TABLE jadwal_sidang
(
    idjadwal INTEGER NOT NULL,
    idmks INTEGER NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    idruangan INTEGER NOT NULL,
    CONSTRAINT jadwal_sidang_pkey PRIMARY KEY (idjadwal, idmks)
);
CREATE TABLE jenismks
(
    id INTEGER PRIMARY KEY NOT NULL,
    namamks VARCHAR(50) NOT NULL
);
CREATE TABLE mahasiswa
(
    npm CHAR(10) PRIMARY KEY NOT NULL,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    email_alternatif VARCHAR(100),
    telepon VARCHAR(100),
    notelp VARCHAR(100)
);
CREATE TABLE mata_kuliah_spesial
(
    idmks INTEGER NOT NULL,
    npm CHAR(10) NOT NULL,
    tahun INTEGER NOT NULL,
    semester INTEGER NOT NULL,
    judul VARCHAR(250) NOT NULL,
    issiapsidang BOOLEAN DEFAULT false,
    pengumpulanhardcopy BOOLEAN DEFAULT false,
    ijinmajusidang BOOLEAN DEFAULT false,
    idjenismks INTEGER,
    CONSTRAINT mata_kuliah_spesial_pkey PRIMARY KEY (idmks, npm, tahun, semester)
);
CREATE TABLE prodi
(
    id INTEGER PRIMARY KEY NOT NULL,
    namaprodi VARCHAR(50) NOT NULL
);
CREATE TABLE ruangan
(
    idruangan INTEGER PRIMARY KEY NOT NULL,
    namaruangan VARCHAR(20) NOT NULL
);
CREATE TABLE saran_dosen_penguji
(
    idmks INTEGER NOT NULL,
    nipsaranpenguji VARCHAR(20) NOT NULL,
    CONSTRAINT saran_dosen_penguji_pkey PRIMARY KEY (idmks, nipsaranpenguji)
);
CREATE TABLE term
(
    tahun INTEGER NOT NULL,
    semester INTEGER NOT NULL,
    CONSTRAINT term_pkey PRIMARY KEY (tahun, semester)
);
CREATE TABLE timeline
(
    idtimeline INTEGER PRIMARY KEY NOT NULL,
    namaevent VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    tahun INTEGER NOT NULL,
    semester INTEGER NOT NULL
);

CREATE TABLE DOSEN
(
NIP VARCHAR(20) PRIMARY KEY
, nama VARCHAR(100) NOT NULL
, username VARCHAR(50) NOT NULL
, password VARCHAR(50) NOT NULL
, email VARCHAR(100) NOT NULL
, institusi VARCHAR(100) NOT NULL
);

CREATE TABLE DOSEN_PEMBIMBING
(
IDMKS INT NOT NULL REFERENCES mata_kuliah_spesial(IdMKS) ON UPDATE CASCADE ON DELETE RESTRICT,
NIPdosenpembimbing  VARCHAR(20) NOT NULL REFERENCES DOSEN(NIP) ON UPDATE CASCADE ON DELETE RESTRICT,
PRIMARY KEY(IDMKS , NIPdosenpembimbing)
);


CREATE TABLE SARAN_DOSEN_PENGUJI
(
IDMKS INT NOT NULL REFERENCES mata_kuliah_spesial(IdMKS) ON UPDATE CASCADE ON DELETE RESTRICT,
NIPsaranpenguji VARCHAR(20) NOT NULL REFERENCES DOSEN(NIP) ON UPDATE CASCADE ON DELETE RESTRICT,
PRIMARY KEY(IDMKS , NIPsaranpenguji)
);

CREATE TABLE DOSEN_PENGUJI
(
IDMKS INT NOT NULL REFERENCES mata_kuliah_spesial(IdMKS) ON UPDATE CASCADE ON DELETE RESTRICT,
NIPdosenpenguji VARCHAR(20) NOT NULL  REFERENCES DOSEN(NIP) ON UPDATE CASCADE ON DELETE RESTRICT,
PRIMARY KEY(IDMKS , NIPdosenpenguji)
);

CREATE TABLE JADWAL_NON_SIDANG
(
    IdJadwal INTEGER PRIMARY KEY NOT NULL,
    Tanggalmulai DATE NOT NULL,
    Tanggalselesai DATE NOT NULL,
    Alasan VARCHAR(100) NOT NULL,
    Repitisi VARCHAR(50),
    NIPdosen VARCHAR(10) NOT NULL REFERENCES DOSEN(NIP)
);

CREATE TABLE PRODI
(
    ID INTEGER PRIMARY KEY NOT NULL,
    NamaProdi VARCHAR(50) NOT NULL
);

CREATE TABLE TIMELINE
(
    IdTimeline INTEGER PRIMARY KEY NOT NULL,
    NamaEvent VARCHAR(100) NOT NULL,
    Tanggal DATE NOT NULL,
    Tahun INTEGER NOT NULL,
    Semester INTEGER NOT NULL,
    FOREIGN KEY (tahun, semester) REFERENCES sisidang.term (tahun, semester)
);