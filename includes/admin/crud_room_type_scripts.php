<script>
document.getElementById('addRoomTypeBtn')?.addEventListener('click', function() {
    const modalContent = `
        <form id="addRoomTypeForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="roomTypeCode" class="form-label">Kode Tipe</label>
                <input type="text" id="roomTypeCode" class="form-input" required placeholder="standard, deluxe, suite, etc">
            </div>
            <div class="form-group">
                <label for="roomTypeName" class="form-label">Nama Tipe Kamar</label>
                <input type="text" id="roomTypeName" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="roomTypeDescription" class="form-label">Deskripsi</label>
                <textarea id="roomTypeDescription" class="form-input" rows="3"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="roomTypePrice" class="form-label">Harga per Malam (Rp)</label>
                    <input type="number" id="roomTypePrice" class="form-input" min="0" step="1000" required>
                </div>
                <div class="form-group">
                    <label for="roomTypeOccupancy" class="form-label">Max Occupancy</label>
                    <input type="number" id="roomTypeOccupancy" class="form-input" min="1" max="10" value="2" required>
                </div>
            </div>
            <div class="form-group">
                <label for="roomTypeFeatures" class="form-label">Fasilitas (pisahkan dengan koma)</label>
                <input type="text" id="roomTypeFeatures" class="form-input" placeholder="WiFi Gratis, AC, TV Kabel, Sarapan">
            </div>
            <div class="form-group">
                <label for="roomTypeImage" class="form-label">Gambar</label>
                <input type="file" id="roomTypeImage" name="image" accept="image/*" class="form-input">
                <small style="color: var(--gray-dark);">Format: JPEG, PNG, GIF, WebP. Maksimal 5MB</small>
            </div>
            <div class="form-group">
                <label for="roomTypeImageUrl" class="form-label">Atau URL Gambar</label>
                <input type="text" id="roomTypeImageUrl" class="form-input" placeholder="https://example.com/image.jpg">
            </div>
        </form>
    `;

    if (typeof showModal === 'function') {
        showModal('Tambah Tipe Kamar', modalContent, [
            {
                text: 'Simpan',
                class: 'btn-primary',
                action: function() {
                    const formData = new FormData();
                    formData.append('action', 'create');
                    formData.append('type_code', document.getElementById('roomTypeCode').value);
                    formData.append('type_name', document.getElementById('roomTypeName').value);
                    formData.append('description', document.getElementById('roomTypeDescription').value);
                    formData.append('price_per_night', document.getElementById('roomTypePrice').value);
                    formData.append('max_occupancy', document.getElementById('roomTypeOccupancy').value);
                    formData.append('features', document.getElementById('roomTypeFeatures').value);

                    if (document.getElementById('roomTypeImage').files.length > 0) {
                        formData.append('image', document.getElementById('roomTypeImage').files[0]);
                    } else if (document.getElementById('roomTypeImageUrl').value) {
                        formData.append('image_url', document.getElementById('roomTypeImageUrl').value);
                    }

                    fetch('../controllers/room_type_process.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Tipe kamar berhasil ditambahkan');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.error || 'Gagal menambahkan tipe kamar'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menambahkan tipe kamar');
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
});

function editRoomType(id) {
    fetch('../controllers/get_room_type_api.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error: ' + (data.error || 'Gagal memuat data tipe kamar'));
                return;
            }

            const rt = data.room_type;
            const modalContent = `
                <form id="editRoomTypeForm" enctype="multipart/form-data">
                    <input type="hidden" id="editRoomTypeId" value="${rt.id}">
                    <div class="form-group">
                        <label for="editRoomTypeCode" class="form-label">Kode Tipe</label>
                        <input type="text" id="editRoomTypeCode" class="form-input" value="${rt.type_code}" required>
                    </div>
                    <div class="form-group">
                        <label for="editRoomTypeName" class="form-label">Nama Tipe Kamar</label>
                        <input type="text" id="editRoomTypeName" class="form-input" value="${rt.type_name}" required>
                    </div>
                    <div class="form-group">
                        <label for="editRoomTypeDescription" class="form-label">Deskripsi</label>
                        <textarea id="editRoomTypeDescription" class="form-input" rows="3">${rt.description || ''}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editRoomTypePrice" class="form-label">Harga per Malam (Rp)</label>
                            <input type="number" id="editRoomTypePrice" class="form-input" value="${rt.price_per_night}" min="0" step="1000" required>
                        </div>
                        <div class="form-group">
                            <label for="editRoomTypeOccupancy" class="form-label">Max Occupancy</label>
                            <input type="number" id="editRoomTypeOccupancy" class="form-input" value="${rt.max_occupancy}" min="1" max="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editRoomTypeFeatures" class="form-label">Fasilitas</label>
                        <input type="text" id="editRoomTypeFeatures" class="form-input" value="${rt.features || ''}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gambar Saat Ini</label>
                        ${rt.image_url ? '<img src="../' + rt.image_url + '" style="max-width: 200px; margin-bottom: 10px; display: block;"><br>' : '<p>Tidak ada gambar</p>'}
                        <label><input type="radio" name="update_room_image" value="keep" checked> Pertahankan gambar</label><br>
                        <label><input type="radio" name="update_room_image" value="upload"> Upload baru</label>
                        <input type="file" id="editRoomTypeImage" name="image" accept="image/*" class="form-input" style="margin-top: 10px; display: none;">
                        <label><input type="radio" name="update_room_image" value="url"> URL gambar</label>
                        <input type="text" id="editRoomTypeImageUrl" class="form-input" style="margin-top: 10px; display: none;">
                        <label><input type="radio" name="update_room_image" value="remove"> Hapus gambar</label>
                    </div>
                    <div class="form-group">
                        <label for="editRoomTypeStatus" class="form-label">Status</label>
                        <select id="editRoomTypeStatus" class="form-select" required>
                            <option value="active" ${rt.status === 'active' ? 'selected' : ''}>Aktif</option>
                            <option value="inactive" ${rt.status === 'inactive' ? 'selected' : ''}>Nonaktif</option>
                        </select>
                    </div>
                </form>
            `;

            setTimeout(function() {
                const radios = document.querySelectorAll('input[name="update_room_image"]');
                radios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const editImage = document.getElementById('editRoomTypeImage');
                        const editImageUrl = document.getElementById('editRoomTypeImageUrl');
                        if (editImage) editImage.style.display = this.value === 'upload' ? 'block' : 'none';
                        if (editImageUrl) editImageUrl.style.display = this.value === 'url' ? 'block' : 'none';
                    });
                });
            }, 100);

            if (typeof showModal === 'function') {
                showModal('Edit Tipe Kamar', modalContent, [
                    {
                        text: 'Simpan Perubahan',
                        class: 'btn-primary',
                        action: function() {
                            const formData = new FormData();
                            formData.append('action', 'update');
                            formData.append('id', document.getElementById('editRoomTypeId').value);
                            formData.append('type_code', document.getElementById('editRoomTypeCode').value);
                            formData.append('type_name', document.getElementById('editRoomTypeName').value);
                            formData.append('description', document.getElementById('editRoomTypeDescription').value);
                            formData.append('price_per_night', document.getElementById('editRoomTypePrice').value);
                            formData.append('max_occupancy', document.getElementById('editRoomTypeOccupancy').value);
                            formData.append('features', document.getElementById('editRoomTypeFeatures').value);
                            formData.append('status', document.getElementById('editRoomTypeStatus').value);

                            const updateImage = document.querySelector('input[name="update_room_image"]:checked').value;
                            formData.append('update_image', updateImage);

                            if (updateImage === 'upload' && document.getElementById('editRoomTypeImage').files.length > 0) {
                                formData.append('image', document.getElementById('editRoomTypeImage').files[0]);
                            } else if (updateImage === 'url' && document.getElementById('editRoomTypeImageUrl').value) {
                                formData.append('image_url', document.getElementById('editRoomTypeImageUrl').value);
                            }

                            fetch('../controllers/room_type_process.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Tipe kamar berhasil diperbarui');
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.error || 'Gagal memperbarui tipe kamar'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memperbarui tipe kamar');
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
            alert('Terjadi kesalahan saat memuat data tipe kamar');
        });
}

function deleteRoomType(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus tipe kamar ini? Tipe kamar yang sudah digunakan dalam reservasi tidak dapat dihapus.')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);

    fetch('../controllers/room_type_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tipe kamar berhasil dihapus');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Gagal menghapus tipe kamar'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus tipe kamar');
    });
}

window.editRoomType = editRoomType;
window.deleteRoomType = deleteRoomType;
</script>

