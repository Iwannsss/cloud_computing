<?php
include 'db.php'; // koneksi ke database

$username = 'admin';
$password = 'admin123';
$nama_lengkap = 'Admin Dinas Perhubungan';

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$query = "INSERT INTO users (username, password, nama_lengkap)
          VALUES ('$username', '$hashed_password', '$nama_lengkap')";

if (mysqli_query($conn, $query)) {
    echo "Akun berhasil ditambahkan!";
} else {
    echo "Gagal menambahkan akun: " . mysqli_error($conn);
}
?>
