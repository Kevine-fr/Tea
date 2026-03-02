@extends('layouts.admin')
@section('title','Dashboard — Administration')

@section('page_styles')
.dash-grid{display:grid;grid-template-columns:1fr 260px;gap:1.5rem;align-items:start}
.chart-card{background:var(--white);border-radius:var(--r);box-shadow:var(--sh-sm);padding:1.8rem}
.chart-title{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;text-align:center;margin-bottom:1.4rem}
.alerts-card{background:var(--white);border-radius:var(--r);box-shadow:var(--sh-sm);overflow:hidden}
.alerts-header{background:var(--green);color:var(--white);padding:.8rem 1.2rem;font-weight:700;font-size:.9rem;text-align:center;border-radius:10px;margin:1rem 1rem .8rem}
.alerts-body{padding:.5rem 1rem 1rem}
.alert-item{display:flex;align-items:center;gap:.6rem;background:#fff9ec;border-radius:10px;padding:.7rem .9rem;margin-bottom:.5rem;font-size:.83rem;font-weight:500;color:var(--txt-m)}
.alert-item-icon{font-size:1.1rem;flex-shrink:0}
.donut-wrap{display:flex;align-items:center;gap:2rem;justify-content:center}
.chart-legend{list-style:none;font-size:.83rem}
.chart-legend li{display:flex;align-items:center;gap:.5rem;margin-bottom:.55rem;font-weight:500}
.legend-dot{width:14px;height:14px;border-radius:50%;flex-shrink:0}
@endsection

@section('content')
<div style="max-width:900px">
    <div class="dash-grid">
        {{-- Charts column --}}
        <div>
            {{-- Donut Chart --}}
            <div class="chart-card fade-up" style="margin-bottom:1.5rem">
                <div class="chart-title">Répartition des gains</div>
                <div class="donut-wrap">
                    <canvas id="donutChart" width="220" height="220"></canvas>
                    <ul class="chart-legend">
                        <li><span class="legend-dot" style="background:#1e3d1a"></span> Lot 1 – Infuseur à thé</li>
                        <li><span class="legend-dot" style="background:#ede5d5;border:1.5px solid #ccc"></span> Lot 2 – Thé ou infusion (100 g)</li>
                        <li><span class="legend-dot" style="background:#d94f1e"></span> Lot 3 – Coffret découverte</li>
                    </ul>
                </div>
            </div>

            {{-- Bar Chart --}}
            <div class="chart-card fade-up s1">
                <div class="chart-title">Courbes : Tickets utilisés par jour</div>
                <canvas id="barChart" height="180"></canvas>
            </div>
        </div>

        {{-- Alerts column --}}
        <div class="alerts-card fade-up s2">
            <div class="alerts-header">Alertes</div>
            <div class="alerts-body">
                @forelse($alerts ?? [] as $alert)
                <div class="alert-item">
                    <span class="alert-item-icon">⚠️</span>
                    <span>{{ $alert }}</span>
                </div>
                @empty
                @foreach($lowStockPrizes ?? [] as $prize)
                <div class="alert-item">
                    <span class="alert-item-icon">⚠️</span>
                    <span>Stock {{ $prize->name }} faible</span>
                </div>
                @endforeach
                @if(($lowStockPrizes ?? collect())->isEmpty())
                <div style="text-align:center;padding:1.5rem;color:var(--txt-l);font-size:.84rem">
                    ✅ Aucune alerte
                </div>
                @endif
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
@push('scripts')
<script>
// Donut
const prizeData = @json($prizeDistribution ?? [['name'=>'Infuseur à thé','count'=>50],['name'=>'Thé 100g','count'=>30],['name'=>'Coffret découverte','count'=>20]]);
const donutCtx = document.getElementById('donutChart').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: prizeData.map(p => p.name),
        datasets: [{
            data: prizeData.map(p => p.count),
            backgroundColor: ['#1e3d1a','#ede5d5','#d94f1e'],
            borderColor: ['#fff','#ccc','#fff'],
            borderWidth: 2,
            hoverOffset: 8,
        }]
    },
    options: {
        cutout: '65%',
        responsive: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                        const pct = Math.round(ctx.raw/total*100);
                        return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                    }
                }
            }
        },
        animation: { animateScale: true, animateRotate: true, duration: 900, easing: 'easeInOutQuart' }
    }
});

// Bar
const dailyData = @json($dailyTickets ?? [['date'=>'01/04','count'=>30],['date'=>'02/04','count'=>50],['date'=>'03/04','count'=>46],['date'=>'04/04','count'=>58]]);
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: dailyData.map(d => d.date),
        datasets: [{
            label: 'Tickets utilisés',
            data: dailyData.map(d => d.count),
            backgroundColor: '#1e3d1a',
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                title: { display: true, text: 'MOIS', font: { size: 10, weight: 600 }, color: '#8a8a8a' },
                grid: { display: false },
                ticks: { font: { size: 11 } }
            },
            y: {
                title: { display: true, text: 'NOMBRE DE TICKET', font: { size: 10, weight: 600 }, color: '#8a8a8a' },
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,.06)' },
                ticks: { font: { size: 11 } }
            }
        },
        animation: { duration: 900, easing: 'easeInOutQuart' }
    }
});
</script>
@endpush
@endsection