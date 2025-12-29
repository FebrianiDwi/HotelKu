<?php
// JavaScript untuk CRUD operations (Blog, User, Room Type)
// File ini berisi semua fungsi CRUD JavaScript
?>

<script>
// Fungsi placeholder untuk edit/delete (bisa diimplementasikan lebih lanjut)
function editReservation(bookingCode) {
    alert('Edit reservasi: ' + bookingCode);
}

function deleteReservation(bookingCode) {
    if (confirm('Apakah Anda yakin ingin menghapus reservasi ' + bookingCode + '?')) {
        alert('Reservasi akan dihapus: ' + bookingCode);
    }
}

