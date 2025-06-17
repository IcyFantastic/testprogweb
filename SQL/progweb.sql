-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jun 2025 pada 17.05
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progweb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `lamaran`
--

CREATE TABLE `lamaran` (
  `id` int(11) NOT NULL,
  `pelamar_id` int(11) DEFAULT NULL,
  `lowongan_id` int(11) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `portofolio` varchar(255) DEFAULT NULL,
  `surat_lamaran` varchar(255) DEFAULT NULL,
  `waktu_lamaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lowongan`
--

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `jenis_pekerjaan` varchar(50) DEFAULT NULL,
  `level_pekerjaan` varchar(50) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `gaji` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `keahlian` text DEFAULT NULL,
  `kualifikasi` text DEFAULT NULL,
  `tanggal_posting` date DEFAULT NULL,
  `tanggal_batas` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lowongan`
--

INSERT INTO `lowongan` (`id`, `perusahaan_id`, `judul`, `lokasi`, `jenis_pekerjaan`, `level_pekerjaan`, `pendidikan`, `gaji`, `deskripsi`, `keahlian`, `kualifikasi`, `tanggal_posting`, `tanggal_batas`) VALUES
(1, 3, 'Part Time Jaga Stand', 'Kota Bandung', 'Part Time', 'Junior / Entry Level', 'SMA / SMK', 'Kompetitif', '- Goreng Cireng\r\n- Melayani Konsumen dgn Baik\r\n- Mencatat Laporan Penjualan\r\n- Set Up Booth Ditempat', '- Kemampuan komunikasi\r\n- Kemampuan menggoreng', '- Pria, diutamakan sekitar jam 4 sore waktunya kosong\r\n- Usia min. 18th\r\n- Memiliki Kendaraan & Memiliki SIM C', '2025-05-22', '2025-06-30'),
(2, 4, 'Freelance Event Organizer', 'Jakarta Selatan', 'Freelance', 'Junior / Entry Level', 'Diploma/D1/D2/D3, SMA / SMK / STM', 'Negosiasi', 'Menyiapkan event mulai dari ide, persiapan, hingga eksekusi event', '- Bisa dekorasi Bunga\r\n- Bisa dekorasi Balon\r\n- Bisa handle event mulai dari ide, persiapan, hingga eksekusi event', '- Pria/Wanita\r\n- Fresh graduate silahkan melamar', '2025-05-22', '2025-06-30'),
(3, 5, 'Project Manager', 'Bogor', 'Full Time', 'Executive Level', 'Sarjana / S1', 'Rp 4.000.000,00 - Rp 5.000.000,00', '- Merencanakan dan mengawasi jalannya proyek\r\n- Memantau progres proyek\r\n- Berkomunikasi dengan klien\r\n- Membuat estimasi anggaran instalasi (BoQ)', '- Kemampuan membaca gambar kerja, RAP, dan RAB\r\n- Penguasaan perangkat lunak seperti Microsoft Office, AutoCAD\r\n- Keterampilan komunikasi dan koordinasi', '- Pendidikan minimal SMA/SMK atau D3, diutamakan jurusan Teknik Sipil, Teknik Arsitektur\r\n- Berpengalaman 3-4 Tahun\r\n- Bersedia bekerja di kantor Bogor dan lokasi proyek', '2025-05-22', '2025-06-30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelamar`
--

CREATE TABLE `pelamar` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelamar`
--

INSERT INTO `pelamar` (`id`, `user_id`, `nama_lengkap`, `tanggal_lahir`, `no_hp`, `email`) VALUES
(1, 1, 'Budi Santoso', '2000-01-15', '081234567890', ''),
(2, 2, 'Siti Rahma', '1999-06-25', '081298765432', ''),
(3, 6, 'Hansel', '2025-06-10', '082115778919', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_perusahaan` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `company_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perusahaan`
--

INSERT INTO `perusahaan` (`id`, `user_id`, `nama_perusahaan`, `lokasi`, `logo`, `company_desc`) VALUES
(1, 1, 'PT. Mitra Makmur Sahabat', 'Jakarta Barat', 'PT._Mitra_Makmur_Sahabat.jpg', 'PT. Mitra Makmur Sahabat berdiri sejak tahun 2010. Kami menyediakan berbagai macam macam compressor AC dimana kami adalah sebuah perusahaan yang berkomitmen dalam menyediakan compresor AC dengan kualitas terbaik dan selalu memberikan pelayanan terbaik. Kami adalah satu-satunya penyedia compressor terlengkap di Indonesia yang sudah memberikan layanan...'),
(2, 2, 'Warung Nasi Indonesia', 'Jakarta Selatan', 'Warung_Makan_Indonesia.png', 'Warung Makan IndoNASIa merupakan warung makan yang menyediakan makanan khas Nusantara. Dengan menu yang dihadirkan, warung makan ini menciptakan nuansa masakan rumahan yang sudah tidak asing bagi penikmatnya. Motto utama adalah HEMAT dan HALAL....'),
(3, 3, 'Cireng Napoleon', 'Kiara Condong & Batununggal', 'Cireng_Napoleon.png', 'Cireng paling enak se-Nusantara'),
(4, 4, 'Jakarta Surprise Planner', 'Tanggerang', 'Jakarta_Surprise_Planner.jpg', 'Perencana acara dan dekorasi, meliputi : - Ulang tahun - Bridal Shower - Baby Shower - Lamaran - Anniversary dll...'),
(5, 5, 'PT. AgriFam', 'Bogor', 'PT._AgriFam.jpg', 'AgriFam merupakan perusahaan yang bergerak di bidang pembangunan, konsultan teknologi pertanian modern, pembuatan, Project Greenhouse, Smart Greenhouse, Smart Irrigation, Solar Dryer, Hydroponic System, Indoor Vertical Farming Teknologi Pertanian dan Pengembangan IoT untuk Pertanian...');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pelamar','perusahaan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'PTMakmur', 'ptmakmur@gmail.com', 'adminmakmur', 'perusahaan'),
(2, 'WarungNasi', 'warungnasi@gmail.com', 'adminwarung', 'perusahaan'),
(3, 'CirengNapoleon', 'cirengnapoleon@gmail.com', 'admincireng', 'perusahaan'),
(4, 'JakartaSurprise', 'jakartasurprise@gmail.com', 'adminsurprise', 'perusahaan'),
(5, 'PTAgriFam', 'ptagrifam@gmail.com', 'adminagri', 'perusahaan'),
(6, 'Hansel', 'hansel@gmail.com', 'Hansel', 'pelamar'),
(7, 'Darryl', 'darryl@gmail.com', 'Darryl', 'pelamar');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `lamaran`
--
ALTER TABLE `lamaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lamaran_ibfk_1` (`pelamar_id`),
  ADD KEY `lamaran_ibfk_2` (`lowongan_id`);

--
-- Indeks untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lowongan_ibfk_1` (`perusahaan_id`);

--
-- Indeks untuk tabel `pelamar`
--
ALTER TABLE `pelamar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `lamaran`
--
ALTER TABLE `lamaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pelamar`
--
ALTER TABLE `pelamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `lamaran`
--
ALTER TABLE `lamaran`
  ADD CONSTRAINT `lamaran_ibfk_1` FOREIGN KEY (`pelamar_id`) REFERENCES `pelamar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lamaran_ibfk_2` FOREIGN KEY (`lowongan_id`) REFERENCES `lowongan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lowongan`
--
ALTER TABLE `lowongan`
  ADD CONSTRAINT `lowongan_ibfk_1` FOREIGN KEY (`perusahaan_id`) REFERENCES `perusahaan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
