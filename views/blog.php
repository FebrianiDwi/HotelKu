<?php
$pageTitle = 'ReservaStay - Blog & Artikel';
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
    <!-- Blog Page -->
    <section id="blog" class="page">
        <div class="container">
            <div class="page">
                <h2 class="section-title">Blog & Artikel</h2>
                <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Temukan tips, tren, dan informasi terbaru seputar akomodasi dan perjalanan.</p>
                
                <div class="articles-grid">
                    <?php
                    // Data artikel - akan diambil dari database nanti
                    $articles = [
                        ['title' => '5 Tips Memilih Kamar Hotel yang Tepat', 'excerpt' => 'Pelajari cara memilih kamar hotel yang sesuai dengan kebutuhan dan anggaran Anda untuk pengalaman menginap yang lebih nyaman.', 'icon' => 'bed'],
                        ['title' => 'Manfaat Check-in Online untuk Perjalanan Bisnis', 'excerpt' => 'Tingkatkan efisiensi perjalanan bisnis Anda dengan memanfaatkan fitur check-in online yang menghemat waktu dan tenaga.', 'icon' => 'calendar-alt'],
                        ['title' => 'Tren Reservasi Akomodasi 2023', 'excerpt' => 'Simak tren terbaru dalam industri reservasi akomodasi dan bagaimana teknologi mengubah cara kita memesan penginapan.', 'icon' => 'chart-bar']
                    ];
                    
                    foreach ($articles as $article):
                    ?>
                    <div class="article-card">
                        <div class="article-image">
                            <i class="fas fa-<?php echo htmlspecialchars($article['icon']); ?>"></i>
                        </div>
                        <div class="article-content">
                            <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                            <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <a href="#" class="btn btn-secondary btn-small">Baca Selengkapnya</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="../script.js"></script>
</body>
</html>

