<div>
    <h1 class="mb-5 text-gray-400 font-bold pt-4 pl-4">Balance últimos 6 meses</h1>
    <div class="flex items-center p-4 rounded-lg shadow-md">        
        <canvas id="balanceBarChart"  class="h-96"></canvas>
    </div>

    @push('scripts')
    <script>
        var months = @json($labels);
        var costs = @json($costs);
        var bills = @json($bills);
        var sales = @json($sales);
        var inventory = @json($inventory);
    
        var ctx = document.getElementById('balanceBarChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Costos',
                        data: costs,
                        backgroundColor: 'rgba(63, 111, 140, 0.7)',
                        stack: 'stack1'
                    },
                    {
                        label: 'Gastos',
                        data: bills,
                        backgroundColor: 'rgba(63, 111, 140, 0.5)',
                        stack: 'stack1'
                    },
                    {
                        label: 'Ventas',
                        data: sales,
                        backgroundColor: 'rgba(96, 163, 92, 0.7)',
                        stack: 'stack2'
                    },
                    {
                        label: 'Inventario',
                        data: inventory,
                        backgroundColor: 'rgba(96, 163, 92, 0.5)',
                        stack: 'stack2'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</div>
