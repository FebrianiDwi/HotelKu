<?php
// JavaScript untuk Chart dan CRUD operations
?>
<script>
// Data untuk chart dari PHP
const monthlyChartData = {
    labels: <?php echo json_encode($monthLabels); ?>,
    data: <?php echo json_encode($monthlyCounts); ?>
};

const roomTypeChartData = {
    labels: <?php echo json_encode($roomTypeLabels); ?>,
    data: <?php echo json_encode($roomTypeData); ?>,
    colors: <?php echo json_encode(array_slice($roomTypeColors, 0, count($roomTypeLabels))); ?>
};

// Inisialisasi Aplikasi
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi komponen
    initModals();
    
    // Inisialisasi Chart
    initializeCharts();
});

// Function to initialize charts
function initializeCharts() {
    // Chart 1: Reservasi per Bulan (Line Chart)
    const monthlyCtx = document.getElementById('monthlyReservationsChart');
    if (monthlyCtx && typeof Chart !== 'undefined') {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyChartData.labels,
                datasets: [{
                    label: 'Jumlah Reservasi',
                    data: monthlyChartData.data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
    
    // Chart 2: Distribusi Tipe Kamar (Doughnut Chart)
    const roomTypeCtx = document.getElementById('roomTypeDistributionChart');
    if (roomTypeCtx && typeof Chart !== 'undefined') {
        new Chart(roomTypeCtx, {
            type: 'doughnut',
            data: {
                labels: roomTypeChartData.labels,
                datasets: [{
                    label: 'Jumlah Reservasi',
                    data: roomTypeChartData.data,
                    backgroundColor: roomTypeChartData.colors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                label += context.parsed + ' reservasi (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
}

