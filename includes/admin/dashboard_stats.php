<?php
// Bagian Statistik Cards
?>
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-value"><?php echo number_format($stats['total_reservations']); ?></div>
        <div class="stat-label">Total Reservasi</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo number_format($stats['active_reservations']); ?></div>
        <div class="stat-label">Reservasi Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo $occupancyRate; ?>%</div>
        <div class="stat-label">Tingkat Okupansi</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo $cancellationRate; ?>%</div>
        <div class="stat-label">Tingkat Pembatalan</div>
    </div>
</div>

