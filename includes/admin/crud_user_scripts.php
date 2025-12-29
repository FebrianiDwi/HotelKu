<script>
function editUser(userId) {
    fetch('../controllers/get_user_api.php?id=' + userId)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error: ' + (data.error || 'Gagal memuat data user'));
                return;
            }

            const user = data.user;
            const modalContent = `
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" value="${user.id}">
                    <div class="form-group">
                        <label for="editUserFirstName" class="form-label">Nama Depan</label>
                        <input type="text" id="editUserFirstName" class="form-input" value="${user.first_name}" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserLastName" class="form-label">Nama Belakang</label>
                        <input type="text" id="editUserLastName" class="form-input" value="${user.last_name}" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail" class="form-label">Email</label>
                        <input type="email" id="editUserEmail" class="form-input" value="${user.email}" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserPhone" class="form-label">Telepon</label>
                        <input type="text" id="editUserPhone" class="form-input" value="${user.phone}" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserRole" class="form-label">Role</label>
                        <select id="editUserRole" class="form-select" required>
                            <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                            <option value="staff" ${user.role === 'staff' ? 'selected' : ''}>Staff</option>
                            <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUserStatus" class="form-label">Status</label>
                        <select id="editUserStatus" class="form-select" required>
                            <option value="active" ${user.status === 'active' ? 'selected' : ''}>Aktif</option>
                            <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUserPassword" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" id="editUserPassword" class="form-input" placeholder="Biarkan kosong untuk tidak mengubah">
                    </div>
                </form>
            `;

            if (typeof showModal === 'function') {
                showModal('Edit User', modalContent, [
                    {
                        text: 'Simpan Perubahan',
                        class: 'btn-primary',
                        action: function() {
                            const formData = new FormData();
                            formData.append('action', 'update');
                            formData.append('id', document.getElementById('editUserId').value);
                            formData.append('first_name', document.getElementById('editUserFirstName').value);
                            formData.append('last_name', document.getElementById('editUserLastName').value);
                            formData.append('email', document.getElementById('editUserEmail').value);
                            formData.append('phone', document.getElementById('editUserPhone').value);
                            formData.append('role', document.getElementById('editUserRole').value);
                            formData.append('status', document.getElementById('editUserStatus').value);
                            
                            const password = document.getElementById('editUserPassword').value.trim();
                            if (password) {
                                formData.append('password', password);
                            }

                            fetch('../controllers/user_process.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('User berhasil diperbarui');
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.error || 'Gagal memperbarui user'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memperbarui user');
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
            alert('Terjadi kesalahan saat memuat data user');
        });
}

function deleteUser(userId) {
    if (!confirm('Apakah Anda yakin ingin menghapus user ini? User yang memiliki reservasi aktif tidak dapat dihapus.')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', userId);

    fetch('../controllers/user_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User berhasil dihapus');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Gagal menghapus user'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus user');
    });
}

window.editUser = editUser;
window.deleteUser = deleteUser;
</script>

