<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/BlogModel.php';

$pageTitle = 'ReservaStay - Detail Blog';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$blogModel = new BlogModel($conn);
$post = null;

if (!empty($slug)) {
    $post = $blogModel->getPostBySlug($slug);
} elseif ($id > 0) {
    $post = $blogModel->getPostById($id);
}

if (!$post) {
    header('Location: blog.php?error=not_found');
    exit;
}

// Only show published posts to non-admin users
if (($post['status'] ?? '') !== 'published') {
    $isAdmin = false;
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel($conn);
        $user = $userModel->findById($_SESSION['user_id']);
        $isAdmin = ($user['role'] ?? '') === 'admin';
    }
    
    if (!$isAdmin) {
        header('Location: blog.php?error=not_found');
        exit;
    }
}

$publishedDate = !empty($post['published_at']) ? date('d F Y', strtotime($post['published_at'])) : date('d F Y', strtotime($post['created_at']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - <?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <section id="blog-detail" class="page">
        <div class="container">
            <div class="page" style="max-width: 900px; margin: 0 auto;">
                <div style="margin-bottom: 30px;">
                    <a href="blog.php" class="btn btn-secondary" style="margin-bottom: 20px;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Blog
                    </a>
                </div>
                
                <article class="blog-article">
                    <h1 class="blog-title" style="font-size: 2.5rem; margin-bottom: 20px; color: var(--secondary-color);">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    
                    <div class="blog-meta" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid var(--gray-light);">
                        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap; color: var(--gray-dark);">
                            <?php if (!empty($post['published_at'])): ?>
                                <span><i class="fas fa-calendar"></i> <?php echo $publishedDate; ?></span>
                            <?php endif; ?>
                            <?php if (!empty($post['author_name'])): ?>
                                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author_name']); ?></span>
                            <?php endif; ?>
                            <?php if (isset($post['views'])): ?>
                                <span><i class="fas fa-eye"></i> <?php echo number_format($post['views']); ?> dilihat</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($post['image_url'])): ?>
                        <div class="blog-image" style="margin-bottom: 30px;">
                            <img src="../<?php echo htmlspecialchars($post['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>" 
                                 style="width: 100%; border-radius: var(--border-radius); max-height: 500px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($post['excerpt'])): ?>
                        <div class="blog-excerpt" style="font-size: 1.2rem; color: var(--gray-dark); margin-bottom: 30px; padding: 20px; background-color: var(--primary-light); border-radius: var(--border-radius); border-left: 4px solid var(--primary-color);">
                            <?php echo nl2br(htmlspecialchars($post['excerpt'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="blog-content" style="line-height: 1.8; color: var(--secondary-color); font-size: 1.1rem;">
                        <?php echo nl2br(htmlspecialchars($post['content'] ?? '')); ?>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    
    <script src="../script.js"></script>
</body>
</html>

