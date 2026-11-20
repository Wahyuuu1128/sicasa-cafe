@extends('layouts.owner')
@section('title', 'Dashboard Owner')

@section('content')
    <!-- HEADER -->
    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <h2 class="font-heading text-xl md:text-2xl font-bold text-ink mb-0.5">Performa <span class="text-brand-blue">{{ $labelWaktu }}</span></h2>
            <p class="text-[11px] md:text-sm text-text-secondary">Pantauan ringkasan operasional Sicasa.</p>
        </div>
        
        <!-- Filter Pintar (Pill) -->
        <div class="bg-gray-100 p-1 rounded-xl flex text-xs font-medium w-full md:w-auto">
            <a href="?filter=today" class="flex-1 text-center px-3 py-2 rounded-lg transition-all {{ $filter === 'today' ? 'bg-white shadow-sm font-bold text-brand-blue' : 'text-gray-500 hover:text-ink' }}">Hari Ini</a>
            <a href="?filter=this_week" class="flex-1 text-center px-3 py-2 rounded-lg transition-all {{ $filter === 'this_week' ? 'bg-white shadow-sm font-bold text-brand-blue' : 'text-gray-500 hover:text-ink' }}">Minggu Ini</a>
            <a href="?filter=this_month" class="flex-1 text-center px-3 py-2 rounded-lg transition-all {{ $filter === 'this_month' ? 'bg-white shadow-sm font-bold text-brand-blue' : 'text-gray-500 hover:text-ink' }}">Bulan Ini</a>
        </div>
    </div>

    <!-- KPI CARDS (Compact Mobile Grid) -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 mb-6">
        
        <div class="col-span-2 md:col-span-1 bg-surface border border-gray-200 p-4 md:p-5 rounded-2xl shadow-sm flex flex-col justify-center">
            <p class="text-[10px] md:text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1">Total Omzet</p>
            <h3 class="font-heading text-2xl md:text-3xl font-bold text-brand-blue truncate">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-surface border border-gray-200 p-4 md:p-5 rounded-2xl shadow-sm">
            <p class="text-[10px] md:text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1">Transaksi</p>
            <h3 class="font-heading text-xl md:text-2xl font-bold text-ink">{{ $totalTransaksi }} <span class="text-xs font-normal text-gray-400">Order</span></h3>
        </div>

        <div class="bg-surface border border-gray-200 p-4 md:p-5 rounded-2xl shadow-sm flex flex-col justify-center gap-2 md:gap-3">
            <div>
                <div class="flex justify-between text-[10px] font-bold mb-1"><span class="text-gray-400">QRIS</span><span class="text-brand-blue">{{ $totalOmzet > 0 ? round(($omzetQris/$totalOmzet)*100) : 0 }}%</span></div>
                <div class="w-full bg-gray-100 rounded-full h-1"><div class="bg-brand-blue h-1 rounded-full" style="width: {{ $totalOmzet > 0 ? ($omzetQris/$totalOmzet)*100 : 0 }}%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-[10px] font-bold mb-1"><span class="text-gray-400">TUNAI</span><span class="text-ink">{{ $totalOmzet > 0 ? round(($omzetTunai/$totalOmzet)*100) : 0 }}%</span></div>
                <div class="w-full bg-gray-100 rounded-full h-1"><div class="bg-ink h-1 rounded-full" style="width: {{ $totalOmzet > 0 ? ($omzetTunai/$totalOmzet)*100 : 0 }}%"></div></div>
            </div>
        </div>
    </div>

    <!-- AREA CHART & BEST SELLER -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        
        <!-- Chart.js Area -->
        <div class="lg:col-span-2 bg-surface border border-gray-200 p-4 rounded-2xl shadow-sm">
            <h3 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-4">Grafik Penjualan</h3>
            <div class="relative h-[200px] md:h-[250px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Best Seller -->
        <div class="bg-surface border border-gray-200 p-4 md:p-5 rounded-2xl shadow-sm">
            <h3 class="font-heading font-bold text-sm md:text-base text-ink mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-500"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Top 5 Best Seller
            </h3>
            <div class="space-y-2">
                @forelse($bestSellers as $index => $item)
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xs font-bold {{ $index == 0 ? 'text-amber-500' : 'text-gray-400' }}">#{{ $index + 1 }}</span>
                            <div>
                                <p class="text-xs font-bold text-ink truncate w-24 md:w-32">{{ $item->menu->nama_menu ?? 'Menu Terhapus' }}</p>
                                <p class="text-[9px] text-text-secondary">{{ $item->total_terjual }} porsi</p>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-brand-blue">Rp{{ number_format($item->total_terjual * ($item->menu->harga ?? 0), 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-xs text-center text-gray-400 py-4">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#2563EB', // brand-blue
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563EB',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4 // Melengkung mulus
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' }, ticks: { font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    </script>
@endsection