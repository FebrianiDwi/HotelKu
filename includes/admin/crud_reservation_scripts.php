<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../models/RoomTypeModel.php';
$roomTypeModel = new RoomTypeModel($conn);
$allRoomTypes = $roomTypeModel->getAllRoomTypes();
?>
<script>
function editReservation(bookingCode) {
    fetch('../controllers/get_reservation_api.php?booking_code=' + encodeURIComponent(bookingCode))
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error: ' + (data.error || 'Gagal memuat data reservasi'));
                return;
            }

            const res = data.reservation;
            const allRoomTypes = <?php echo json_encode($allRoomTypes); ?>;
            
            let roomTypeOptions = '';
            allRoomTypes.forEach(rt => {
                roomTypeOptions += `<option value="${rt.id}" ${res.room_type_id == rt.id ? 'selected' : ''}>${rt.type_name} (${rt.type_code})</option>`;
            });

            const modalContent = `
                <form id="editReservationForm">
                    <input type="hidden" id="editReservationBookingCode" value="${res.booking_code}">
                    <div class="form-group">
                        <label for="editReservationRoomType" class="form-label">Tipe Kamar</label>
                        <select id="editReservationRoomType" class="form-select" required>
                            ${roomTypeOptions}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editReservationRoomCount" class="form-label">Jumlah Kamar</label>
                        <input type="number" id="editReservationRoomCount" class="form-input" value="${res.room_count}" min="1" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editReservationCheckin" class="form-label">Check-in</label>
                            <input type="date" id="editReservationCheckin" class="form-input" value="${res.checkin_date}" required>
                        </div>
                        <div class="form-group">
                            <label for="editReservationCheckout" class="form-label">Check-out</label>
                            <input type="date" id="editReservationCheckout" class="form-input" value="${res.checkout_date}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editReservationGuestName" class="form-label">Nama Tamu</label>
                        <input type="text" id="editReservationGuestName" class="form-input" value="${res.guest_name}" required>
                    </div>
                    <div class="form-group">
                        <label for="editReservationGuestEmail" class="form-label">Email</label>
                        <input type="email" id="editReservationGuestEmail" class="form-input" value="${res.guest_email}" required>
                    </div>
                    <div class="form-group">
                        <label for="editReservationGuestPhone" class="form-label">Telepon</label>
                        <input type="text" id="editReservationGuestPhone" class="form-input" value="${res.guest_phone}" required>
                    </div>
                    <div class="form-group">
                        <label for="editReservationSpecialRequests" class="form-label">Permintaan Khusus</label>
                        <textarea id="editReservationSpecialRequests" class="form-input" rows="3">${res.special_requests || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="editReservationStatus" class="form-label">Status</label>
                        <select id="editReservationStatus" class="form-select" required>
                            <option value="pending" ${res.status === 'pending' ? 'selected' : ''}>Menunggu</option>
                            <option value="confirmed" ${res.status === 'confirmed' ? 'selected' : ''}>Dikonfirmasi</option>
                            <option value="checked_in" ${res.status === 'checked_in' ? 'selected' : ''}>Check-in</option>
                            <option value="checked_out" ${res.status === 'checked_out' ? 'selected' : ''}>Check-out</option>
                            <option value="completed" ${res.status === 'completed' ? 'selected' : ''}>Selesai</option>
                            <option value="cancelled" ${res.status === 'cancelled' ? 'selected' : ''}>Dibatalkan</option>
                        </select>
                    </div>
                </form>
            `;

            if (typeof showModal === 'function') {
                showModal('Edit Reservasi', modalContent, [
                    {
                        text: 'Simpan Perubahan',
                        class: 'btn-primary',
                        action: function() {
                            const formData = new FormData();
                            formData.append('action', 'update');
                            formData.append('booking_code', document.getElementById('editReservationBookingCode').value);
                            formData.append('room_type_id', document.getElementById('editReservationRoomType').value);
                            formData.append('room_count', document.getElementById('editReservationRoomCount').value);
                            formData.append('checkin_date', document.getElementById('editReservationCheckin').value);
                            formData.append('checkout_date', document.getElementById('editReservationCheckout').value);
                            formData.append('guest_name', document.getElementById('editReservationGuestName').value);
                            formData.append('guest_email', document.getElementById('editReservationGuestEmail').value);
                            formData.append('guest_phone', document.getElementById('editReservationGuestPhone').value);
                            formData.append('special_requests', document.getElementById('editReservationSpecialRequests').value);
                            formData.append('status', document.getElementById('editReservationStatus').value);

                            fetch('../controllers/reservation_process.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Reservasi berhasil diperbarui');
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.error || 'Gagal memperbarui reservasi'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memperbarui reservasi');
                            });
                        }
                    },
                    {
                        text: 'Batal',
                        class: 'btn-secondary',
                        action: function() {
                            if (typeof closeModal === 'function') closeModal();
                        }
                    }
                ]);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data reservasi');
        });
}

function deleteReservation(bookingCode) {
    if (!confirm('Apakah Anda yakin ingin menghapus reservasi ' + bookingCode + '? Semua data terkait termasuk pengajuan pembatalan akan ikut terhapus.')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('booking_code', bookingCode);

    fetch('../controllers/reservation_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reservasi berhasil dihapus');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Gagal menghapus reservasi'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus reservasi');
    });
}

window.editReservation = editReservation;
window.deleteReservation = deleteReservation;
</script>

