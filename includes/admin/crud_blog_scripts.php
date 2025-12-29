<script>
document.getElementById('addArticleBtn')?.addEventListener('click', function() {
    const modalContent = `
        <form id="addArticleForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="articleTitle" class="form-label">Judul Artikel</label>
                <input type="text" id="articleTitle" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="articleExcerpt" class="form-label">Ringkasan</label>
                <textarea id="articleExcerpt" class="form-input" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="articleContent" class="form-label">Konten</label>
                <textarea id="articleContent" class="form-input" rows="8" required></textarea>
            </div>
            <div class="form-group">
                <label for="articleImage" class="form-label">Gambar</label>
                <input type="file" id="articleImage" name="image" accept="image/*" class="form-input">
                <small style="color: var(--gray-dark);">Format: JPEG, PNG, GIF, WebP. Maksimal 5MB</small>
            </div>
            <div class="form-group">
                <label for="articleImageUrl" class="form-label">Atau URL Gambar</label>
                <input type="text" id="articleImageUrl" class="form-input" placeholder="https://example.com/image.jpg">
            </div>
            <div class="form-group">
                <label for="articleStatus" class="form-label">Status</label>
                <select id="articleStatus" class="form-select" required>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
        </form>
    `;

    if (typeof showModal === 'function') {
        showModal('Tambah Artikel', modalContent, [
            {
                text: 'Simpan',
                class: 'btn-primary',
                action: function() {
                    const title = document.getElementById('articleTitle').value.trim();
                    const content = document.getElementById('articleContent').value.trim();

                    if (!title || !content) {
                        alert('Judul dan konten wajib diisi!');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('action', 'create');
                    formData.append('title', title);
                    formData.append('excerpt', document.getElementById('articleExcerpt').value.trim());
                    formData.append('content', content);
                    formData.append('status', document.getElementById('articleStatus').value);

                    if (document.getElementById('articleImage').files.length > 0) {
                        formData.append('image', document.getElementById('articleImage').files[0]);
                    } else if (document.getElementById('articleImageUrl').value.trim()) {
                        formData.append('image_url', document.getElementById('articleImageUrl').value.trim());
                    }

                    fetch('../controllers/blog_process.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Artikel berhasil ditambahkan');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.error || 'Gagal menambahkan artikel'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menambahkan artikel: ' + error.message);
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

function editArticle(articleId) {
    fetch('../controllers/get_blog_api.php?id=' + articleId)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error: ' + (data.error || 'Gagal memuat data artikel'));
                return;
            }

            const article = data.post;
            const modalContent = `
                <form id="editArticleForm" enctype="multipart/form-data">
                    <input type="hidden" id="editArticleId" value="${article.id}">
                    <div class="form-group">
                        <label for="editArticleTitle" class="form-label">Judul Artikel</label>
                        <input type="text" id="editArticleTitle" class="form-input" value="${article.title}" required>
                    </div>
                    <div class="form-group">
                        <label for="editArticleExcerpt" class="form-label">Ringkasan</label>
                        <textarea id="editArticleExcerpt" class="form-input" rows="3">${article.excerpt || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="editArticleContent" class="form-label">Konten</label>
                        <textarea id="editArticleContent" class="form-input" rows="8" required>${article.content || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gambar Saat Ini</label>
                        ${article.image_url ? '<img src="../' + article.image_url + '" style="max-width: 200px; margin-bottom: 10px; display: block;"><br>' : '<p>Tidak ada gambar</p>'}
                        <label><input type="radio" name="update_image" value="keep" checked> Pertahankan gambar saat ini</label><br>
                        <label><input type="radio" name="update_image" value="upload"> Upload gambar baru</label>
                        <input type="file" id="editArticleImage" name="image" accept="image/*" class="form-input" style="margin-top: 10px; display: none;">
                        <label><input type="radio" name="update_image" value="url"> Gunakan URL gambar</label>
                        <input type="text" id="editArticleImageUrl" class="form-input" placeholder="https://example.com/image.jpg" style="margin-top: 10px; display: none;">
                        <label><input type="radio" name="update_image" value="remove"> Hapus gambar</label>
                    </div>
                    <div class="form-group">
                        <label for="editArticleStatus" class="form-label">Status</label>
                        <select id="editArticleStatus" class="form-select" required>
                            <option value="draft" ${article.status === 'draft' ? 'selected' : ''}>Draft</option>
                            <option value="published" ${article.status === 'published' ? 'selected' : ''}>Published</option>
                            <option value="archived" ${article.status === 'archived' ? 'selected' : ''}>Archived</option>
                        </select>
                    </div>
                </form>
            `;

            setTimeout(function() {
                const radios = document.querySelectorAll('input[name="update_image"]');
                radios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const editImage = document.getElementById('editArticleImage');
                        const editImageUrl = document.getElementById('editArticleImageUrl');
                        if (editImage) editImage.style.display = this.value === 'upload' ? 'block' : 'none';
                        if (editImageUrl) editImageUrl.style.display = this.value === 'url' ? 'block' : 'none';
                    });
                });
            }, 100);

            if (typeof showModal === 'function') {
                showModal('Edit Artikel', modalContent, [
                    {
                        text: 'Simpan Perubahan',
                        class: 'btn-primary',
                        action: function() {
                            const formData = new FormData();
                            formData.append('action', 'update');
                            formData.append('id', document.getElementById('editArticleId').value);
                            formData.append('title', document.getElementById('editArticleTitle').value);
                            formData.append('excerpt', document.getElementById('editArticleExcerpt').value);
                            formData.append('content', document.getElementById('editArticleContent').value);
                            formData.append('status', document.getElementById('editArticleStatus').value);

                            const updateImage = document.querySelector('input[name="update_image"]:checked').value;
                            formData.append('update_image', updateImage);

                            if (updateImage === 'upload' && document.getElementById('editArticleImage').files.length > 0) {
                                formData.append('image', document.getElementById('editArticleImage').files[0]);
                            } else if (updateImage === 'url' && document.getElementById('editArticleImageUrl').value) {
                                formData.append('image_url', document.getElementById('editArticleImageUrl').value);
                            }

                            fetch('../controllers/blog_process.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Artikel berhasil diperbarui');
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.error || 'Gagal memperbarui artikel'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memperbarui artikel');
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
            alert('Terjadi kesalahan saat memuat data artikel');
        });
}

function deleteArticle(articleId) {
    if (!confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', articleId);

    fetch('../controllers/blog_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Artikel berhasil dihapus');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Gagal menghapus artikel'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus artikel');
    });
}

window.editArticle = editArticle;
window.deleteArticle = deleteArticle;
</script>

