-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Mar 2019 pada 07.45
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paylis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(30) NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `id_level` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `password`, `id_level`) VALUES
(3243271, 'Bayu Hidayah M.', 'admin', '9e07fe589844f39bd482073cf4c79830313062333832313538346331393134636338623162613938643732623133323839303836333235663365326133343335663761643835663562643462383166658369d7a3cbd63661b60c6920f9cd96', 1184211),
(3243273, 'Bank-1', 'bank1', '32ac38c275dcf88548fe611c8847ebeb63366266636632333739366331653838643964376364323162383331653035393064393161373965333437666565343235623936353064346537343465333931b58ffc83c4', 1184212);

-- --------------------------------------------------------

--
-- Struktur dari tabel `level`
--

CREATE TABLE `level` (
  `id_level` int(30) NOT NULL,
  `nama_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `level`
--

INSERT INTO `level` (`id_level`, `nama_level`) VALUES
(1184211, 'Admin'),
(1184212, 'Bank');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(30) NOT NULL,
  `id_tarif` int(30) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `id_tarif`, `nama_pelanggan`, `username`, `password`, `alamat`) VALUES
('379863362431', 100013212, 'Bayoe', 'bayu', '40070e87292bdcd4424167e37c41e9e365306265656434643533633261363365383835303662353735336232383239643661306535356637626136653561653863653737613265383334303265626264007b8789cb3380dc855e', 'Jln. Abadi no 67'),
('566496678423', 100013211, 'arnold', 'arnold', 'c19d5effd3d2d0f86e3ad4961daf988c313331643930373534663666653331646535656465366133356263306138623566326236653831333763353538313466646465616465613862373338323765398eaf8d98c4d0', 'Jln. Abadi no 67'),
('967381439365', 100013213, 'Andra', 'andra', 'd7e787952cc0b630eee263013ef5dbea32633162376630626362306361396339633032656435353761343139623563306233663361653864313763613162626130386237326638366630663034383934f08a2cd006', 'asd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` varchar(255) NOT NULL,
  `id_tagihan` int(30) NOT NULL,
  `id_pelanggan` varchar(30) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `bulan_bayar` varchar(10) NOT NULL,
  `biaya_admin` int(50) NOT NULL,
  `ppj` int(20) NOT NULL,
  `ppn` int(20) NOT NULL,
  `harga_pemakaian` int(30) NOT NULL,
  `total_harga` int(30) NOT NULL,
  `total_bayar` int(100) NOT NULL,
  `total_kembalian` int(20) NOT NULL,
  `id_admin` varchar(30) NOT NULL,
  `status_pembayaran` enum('Requested','Refuse','Confirmation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_tagihan`, `id_pelanggan`, `tanggal_pembayaran`, `bulan_bayar`, `biaya_admin`, `ppj`, `ppn`, `harga_pemakaian`, `total_harga`, `total_bayar`, `total_kembalian`, `id_admin`, `status_pembayaran`) VALUES
('5KHBrf7klRqaHhiYL4SxQbHgWws35BVt', 1653214, '379863362431', '2019-03-10', '5', 2500, 5274, 17580, 175800, 201154, 230000, 28846, '3243271', 'Confirmation'),
('5PuFkmjCYPIp52vcQqVWBbL2RTI5nDiB', 1653215, '566496678423', '2019-03-30', '3', 2500, 1245, 4150, 41500, 49395, 50000, 605, '3243271', 'Confirmation'),
('l1L8hKYDb1GIRgDhfQbHIoBOjx9r1TD3', 1653213, '379863362431', '2019-03-09', '4', 2500, 1758, 5860, 58600, 68718, 90000, 21282, '3243273', 'Confirmation'),
('mFRW8rK2ldAtuOvkIU7g9leG9Y3bXiGI', 1653216, '379863362431', '2019-03-17', '9', 2500, 2707, 9024, 90244, 104476, 120001, 15525, '3243273', 'Confirmation'),
('N8cPxznGM8YdS5equEpqbxukChxbQ9rg', 1653217, '379863362431', '2019-05-17', '6', 2500, 2637, 8790, 87900, 101827, 110000, 8173, '3243273', 'Confirmation'),
('vudGYKYqyb3pOrWaLHbx4rzzXlGUgfXx', 1653218, '566496678423', '2019-03-17', '8', 2500, 1494, 4980, 49800, 58774, 60000, 1226, '3243271', 'Confirmation');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penggunaan`
--

CREATE TABLE `penggunaan` (
  `id_penggunaan` int(30) NOT NULL,
  `id_pelanggan` varchar(30) NOT NULL,
  `bulan` varchar(30) NOT NULL,
  `tahun` varchar(10) NOT NULL,
  `meter_awal` varchar(40) NOT NULL,
  `meter_akhir` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penggunaan`
--

INSERT INTO `penggunaan` (`id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `meter_awal`, `meter_akhir`) VALUES
(2731634, '379863362431', '4', '2019', '3100', '3200'),
(2731635, '379863362431', '5', '2019', '4000', '4300'),
(2731636, '566496678423', '3', '2019', '3000', '3100'),
(2731637, '379863362431', '9', '2019', '40000', '40154'),
(2731638, '379863362431', '6', '2019', '4300', '4450'),
(2731639, '566496678423', '8', '2019', '4000', '4120');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

CREATE TABLE `tagihan` (
  `id_tagihan` int(30) NOT NULL,
  `id_penggunaan` int(30) NOT NULL,
  `id_pelanggan` varchar(30) NOT NULL,
  `bulan` varchar(10) NOT NULL,
  `tahun` varchar(10) NOT NULL,
  `jumlah_meter` int(50) NOT NULL,
  `status` enum('Belum Lunas','Lunas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tagihan`
--

INSERT INTO `tagihan` (`id_tagihan`, `id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `jumlah_meter`, `status`) VALUES
(1653213, 2731634, '379863362431', '4', '2019', 100, 'Lunas'),
(1653214, 2731635, '379863362431', '5', '2019', 300, 'Lunas'),
(1653215, 2731636, '566496678423', '3', '2019', 100, 'Lunas'),
(1653216, 2731637, '379863362431', '9', '2019', 154, 'Lunas'),
(1653217, 2731638, '379863362431', '6', '2019', 150, 'Lunas'),
(1653218, 2731639, '566496678423', '8', '2019', 120, 'Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarif`
--

CREATE TABLE `tarif` (
  `id_tarif` int(15) NOT NULL,
  `id_tarif_gol` int(20) NOT NULL,
  `gol_tarif` varchar(10) NOT NULL,
  `daya` varchar(25) NOT NULL,
  `tarifperkwh` int(30) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tarif`
--

INSERT INTO `tarif` (`id_tarif`, `id_tarif_gol`, `gol_tarif`, `daya`, `tarifperkwh`, `keterangan`) VALUES
(100013211, 101212581, 'R-1', '450 VA', 415, 'Subsidi'),
(100013212, 101212581, 'R-1', '900 VA', 586, 'Subsidi'),
(100013213, 101212581, 'R-1', '900 VA-RTM', 1352, 'Non-Subsidi'),
(100013214, 101212581, 'R-1', '1300 VA', 1467, 'Non-Subsidi'),
(100013215, 101212581, 'R-1', '2200 VA', 1467, 'Non-Subsidi'),
(100013216, 101212581, 'R-2', '3500 VA', 1467, 'Non-Subsidi'),
(100013217, 101212581, 'R-2', '4400 VA', 1467, 'Non-Subsidi'),
(100013218, 101212581, 'R-2', '5500 VA', 1467, 'Non-Subsidi'),
(100013219, 101212581, 'R-3', '6600 VA', 1467, 'Non-Subsidi'),
(100013220, 101212582, 'B-1', '450 VA', 535, '-'),
(100013221, 101212582, 'B-1', '900 VA', 630, '-'),
(100013222, 101212582, 'B-1', '1300 VA', 966, '-'),
(100013223, 101212582, 'B-1', '2200 VA', 1100, '-'),
(100013224, 101212582, 'B-1', '3500 VA', 1100, '-'),
(100013225, 101212582, 'B-1', '4400 VA', 1100, '-'),
(100013226, 101212582, 'B-1', '5500 VA', 1100, '-'),
(100013227, 101212583, 'S-1', '220 VA', 0, '-'),
(100013228, 101212583, 'S-2', '450 VA', 325, '-'),
(100013229, 101212583, 'S-2', '900 VA', 455, '-'),
(100013230, 101212583, 'S-2', '1300 VA', 708, '-'),
(100013231, 101212583, 'S-2', '2200 VA', 760, '-'),
(100013232, 101212583, 'S-2', '3500 VA s.d 200 kVA', 900, '-'),
(100013233, 101212584, 'I-1', '450 VA', 485, '-'),
(100013234, 101212584, 'I-1', '900 VA', 600, '-'),
(100013235, 101212584, 'I-1', '1300 VA', 930, '-'),
(100013236, 101212584, 'I-1', '2200 VA', 960, '-'),
(100013237, 101212584, 'I-1', '3500 VA s.d 14 kVA', 1112, '-'),
(100013238, 101212585, 'P-1', '450 VA', 685, '-'),
(100013239, 101212585, 'P-1', '900 VA', 760, '-'),
(100013240, 101212585, 'P-1', '1300 VA', 1049, '-'),
(100013241, 101212585, 'P-1', '2200 VA', 1076, '-'),
(100013242, 101212585, 'P-1', '3500 VA', 1076, '-'),
(100013243, 101212585, 'P-1', '4400 VA', 1076, '-'),
(100013244, 101212585, 'P-1', '5500 VA', 1076, '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarif_gol`
--

CREATE TABLE `tarif_gol` (
  `id_tarif_gol` int(20) NOT NULL,
  `nama_gol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tarif_gol`
--

INSERT INTO `tarif_gol` (`id_tarif_gol`, `nama_gol`) VALUES
(101212581, 'Rumah Tangga'),
(101212582, 'Bisnis'),
(101212583, 'Sosial'),
(101212584, 'Industri'),
(101212585, 'Publik');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indeks untuk tabel `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD PRIMARY KEY (`id_penggunaan`);

--
-- Indeks untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id_tagihan`);

--
-- Indeks untuk tabel `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indeks untuk tabel `tarif_gol`
--
ALTER TABLE `tarif_gol`
  ADD PRIMARY KEY (`id_tarif_gol`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3243275;

--
-- AUTO_INCREMENT untuk tabel `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1184213;

--
-- AUTO_INCREMENT untuk tabel `penggunaan`
--
ALTER TABLE `penggunaan`
  MODIFY `id_penggunaan` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2731640;

--
-- AUTO_INCREMENT untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id_tagihan` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1653219;

--
-- AUTO_INCREMENT untuk tabel `tarif`
--
ALTER TABLE `tarif`
  MODIFY `id_tarif` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100013245;

--
-- AUTO_INCREMENT untuk tabel `tarif_gol`
--
ALTER TABLE `tarif_gol`
  MODIFY `id_tarif_gol` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101212586;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
