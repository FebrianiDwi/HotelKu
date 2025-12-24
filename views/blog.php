<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/BlogModel.php';

$pageTitle = 'ReservaStay - Blog & Artikel';

$blogModel = new BlogModel($conn);
$articles = $blogModel->getPublishedPosts(10);

function getArticleIcon($title) {
    $titleLower = strtolower($title);
    if (strpos($titleLower, 'kamar') !== false || strpos($titleLower, 'hotel') !== false) {
        return 'bed';
    } elseif (strpos($titleLower, 'check-in') !== false || strpos($titleLower, 'perjalanan') !== false) {
        return 'calendar-alt';
    } elseif (strpos($titleLower, 'tren') !== false || strpos($titleLower, 'statistik') !== false) {
        return 'chart-bar';
    } else {
        return 'newspaper';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <!-- Blog Page -->
    <section id="blog" class="page">
        <div class="container">
            <div class="page">
                <h2 class="section-title">Blog & Artikel</h2>
                <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Temukan tips, tren, dan informasi terbaru seputar akomodasi dan perjalanan.</p>
                
                <div class="articles-grid">
                    <?php if (empty($articles)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                            <i class="fas fa-newspaper" style="font-size: 3rem; color: var(--gray-dark); margin-bottom: 20px;"></i>
                            <p style="color: var(--gray-dark);">Belum ada artikel yang dipublikasikan.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($articles as $article): 
                            $excerpt = !empty($article['excerpt']) ? $article['excerpt'] : substr(strip_tags($article['content'] ?? ''), 0, 150) . '...';
                            $icon = getArticleIcon($article['title']);
                            $publishedDate = !empty($article['published_at']) ? date('d F Y', strtotime($article['published_at'])) : '';
                        ?>
                        <div class="article-card">
                            <div class="article-image">
                                <i class="fas fa-<?php echo htmlspecialchars($icon); ?>"></i>
                            </div>
                            <div class="article-content">
                                <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                <?php if ($publishedDate): ?>
                                    <p style="font-size: 0.85rem; color: var(--gray-dark); margin-bottom: 10px;">
                                        <i class="fas fa-calendar"></i> <?php echo $publishedDate; ?>
                                        <?php if (!empty($article['author_name'])): ?>
                                            <span style="margin-left: 15px;"><i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author_name']); ?></span>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                <p class="article-excerpt"><?php echo htmlspecialchars($excerpt); ?></p>
                                <a href="#" class="btn btn-secondary btn-small">Baca Selengkapnya</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script src="../script.js"></script>
</body>
</html>

