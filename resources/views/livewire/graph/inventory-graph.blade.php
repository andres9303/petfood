<div>
    <h1 class="mb-5 text-gray-400 font-bold pt-4 pl-4">Inventario por categoría</h1>
    <div class="flex items-center p-4 rounded-lg shadow-md">        
        <canvas id="inventoryDoughnutChart" class="h-96"></canvas>
    </div>

    @push('scripts')
    <script>
        var categories = @json($categories);
        var inventoryValues = @json($inventoryValues);

        var ctx = document.getElementById('inventoryDoughnutChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: inventoryValues,
                    backgroundColor: [
                        'rgba(63, 111, 140, 0.7)',
                        'rgba(96, 163, 92, 0.7)',
                        'rgba(130, 75, 157, 0.7)',
                        'rgba(219, 121, 0, 0.7)',
                        'rgba(229, 89, 52, 0.7)',
                        'rgba(61, 165, 255, 0.7)',
                        'rgba(101, 203, 146, 0.7)',
                        'rgba(173, 118, 50, 0.7)',
                        'rgba(255, 136, 98, 0.7)',
                        'rgba(84, 170, 169, 0.7)',
                        'rgba(237, 187, 255, 0.7)',
                        'rgba(255, 214, 97, 0.7)',
                        'rgba(255, 156, 163, 0.7)',
                        'rgba(0, 175, 211, 0.7)',
                        'rgba(220, 112, 86, 0.7)',
                        'rgba(163, 211, 0, 0.7)',
                        'rgba(0, 98, 204, 0.7)',
                        'rgba(255, 172, 230, 0.7)',
                        'rgba(128, 107, 0, 0.7)',
                        'rgba(204, 153, 255, 0.7)',
                        'rgba(0, 148, 68, 0.7)',
                        'rgba(245, 150, 50, 0.7)',
                        'rgba(0, 166, 147, 0.7)',
                        'rgba(87, 108, 180, 0.7)',
                        'rgba(255, 51, 153, 0.7)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
    @endpush
</div>
