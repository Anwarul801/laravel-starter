@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('backend/fonts/flaticon/flaticon.css') }}">

<style>
    *{box-sizing:border-box;margin:0;padding:0}
    :root{
        --accent:#835eff;
        --accent-light:#f0ebff;
        --accent-mid:#6a45e0;
        --accent-soft:#ede8ff;
    }
    .d{padding:20px;background:#f5f6fa;min-height:100vh;font-family:inherit}

    /* ── Topbar ── */
    .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;background:#fff;border:1px solid #e8eaf0;border-top:3px solid var(--accent);border-radius:12px;padding:16px 20px;gap:12px;flex-wrap:wrap}
    .t-left h1{font-size:18px;font-weight:700;color:#0f1117}
    .t-left p{font-size:13px;color:#374151;margin-top:3px;font-weight:500}
    .t-right{display:flex;align-items:center;gap:14px;flex-shrink:0}
    .clk-txt{text-align:right}
    .clk-time{font-size:18px;font-weight:700;color:var(--accent);font-variant-numeric:tabular-nums;line-height:1.2}
    .clk-date{font-size:11px;color:#374151;margin-top:2px;font-weight:500}
    .sep{width:1px;height:34px;background:#e8eaf0;flex-shrink:0}

    /* ── Section heading ── */
    .sec-hd{font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px;display:flex;align-items:center;gap:6px}
    .sec-hd::before{content:'';display:inline-block;width:3px;height:14px;background:var(--accent);border-radius:2px;flex-shrink:0}

    /* ── Stat grid ── */
    .sg{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:10px}
    .sc{background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 14px;display:flex;align-items:center;gap:12px;transition:border-color .2s,box-shadow .2s}
    .sc:hover{border-color:var(--accent);box-shadow:0 4px 16px rgba(131,94,255,.1)}
    .si{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:17px;background:var(--accent-light);color:var(--accent)}
    .s-label{font-size:11px;color:#374151;margin-bottom:2px;font-weight:500}
    .s-val{font-size:22px;font-weight:700;color:#0f1117;line-height:1.2}
    .s-trend{font-size:11px;color:#6b7280;margin-top:2px}

    /* ── Info row ── */
    .rg{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:20px}
    .rc{background:#fff;border:1px solid #e8eaf0;border-radius:12px;padding:16px 14px;transition:border-color .2s,box-shadow .2s}
    .rc:hover{border-color:var(--accent);box-shadow:0 4px 16px rgba(131,94,255,.1)}
    .rc-wide{grid-column:span 2;background:var(--accent);border-color:var(--accent)}
    .rc-wide .rc-lbl{color:rgba(255,255,255,.8)}
    .rc-wide .rc-val{color:#fff}
    .rc-wide .rc-sub{color:rgba(255,255,255,.65)}
    .rc-wide i{color:rgba(255,255,255,.9) !important}
    .rc-wide:hover{border-color:var(--accent-mid);box-shadow:0 4px 20px rgba(131,94,255,.3)}
    .rc-head{display:flex;align-items:center;gap:8px;margin-bottom:8px}
    .rc-lbl{font-size:12px;color:#374151;font-weight:500}
    .rc-val{font-size:20px;font-weight:700;color:#0f1117}
    .rc-sub{font-size:11px;color:#6b7280;margin-top:3px}

    /* ── Table card ── */
    .tc{background:#fff;border:1px solid #e8eaf0;border-radius:12px;overflow:hidden}
    .tc-head{padding:14px 18px;border-bottom:1px solid #e8eaf0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
    .tc-head-left{display:flex;align-items:center;gap:8px}
    .tc-head-left span{font-size:14px;font-weight:700;color:#0f1117}
    .tc-head-left i{font-size:14px;color:var(--accent)}
    .tc-badge{font-size:11px;font-weight:600;background:var(--accent-light);color:var(--accent);padding:3px 10px;border-radius:20px}

    /* Desktop table */
    .table-wrap{overflow-x:auto}
    .dt{width:100%;border-collapse:collapse;font-size:13px}
    .dt thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#374151;letter-spacing:.5px;text-transform:uppercase;background:#f9fafb;border-bottom:2px solid var(--accent-soft)}
    .dt thead th:first-child{border-left:3px solid var(--accent)}
    .dt tbody td{padding:13px 16px;color:#111827;border-bottom:1px solid #f3f4f6}
    .dt tbody tr:last-child td{border-bottom:none}
    .dt tbody tr:hover td{background:var(--accent-light)}
    .dt tbody tr:hover td:first-child{border-left:3px solid var(--accent)}
    .dt tbody td:first-child{border-left:3px solid transparent;transition:border-color .15s}
    .mono{font-family:monospace;font-size:12px;color:#374151;font-weight:500}
    .fw{font-weight:700;color:#0f1117}
    .muted{color:#4b5563}
    .amt{font-weight:700;color:var(--accent)}

    /* Mobile card list (hidden on desktop) */
    .mobile-list{display:none;flex-direction:column;gap:1px}
    .m-card{padding:14px 16px;border-bottom:1px solid #f3f4f6;display:flex;flex-direction:column;gap:8px}
    .m-card:last-child{border-bottom:none}
    .m-card-top{display:flex;align-items:center;justify-content:space-between;gap:8px}
    .m-card-name{font-size:14px;font-weight:700;color:#0f1117}
    .m-card-amt{font-size:15px;font-weight:700;color:var(--accent)}
    .m-card-meta{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
    .m-meta-pill{font-size:11px;color:#374151;background:#f3f4f6;padding:2px 8px;border-radius:20px;font-weight:500}
    .m-card-txn{font-family:monospace;font-size:11px;color:#6b7280}

    /* Badges */
    .bd{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
    .bd-s{background:#EAF3DE;color:#27500A}
    .bd-w{background:#FAEEDA;color:#633806}
    .bd-d{background:#FCEBEB;color:#791F1F}
    .bd-i{background:var(--accent-light);color:var(--accent)}

    .empty{text-align:center;padding:40px 0;color:#6b7280;font-size:14px}

    /* ════ BREAKPOINTS ════ */

    /* Tablet: 2 col stats */
    @media(max-width:991px){
        .sg{grid-template-columns:repeat(2,minmax(0,1fr))}
        .rg{grid-template-columns:repeat(2,minmax(0,1fr))}
        .rc-wide{grid-column:span 2}
    }

    /* Large mobile: keep 2 col */
    @media(max-width:767px){
        .d{padding:14px}
        .s-val{font-size:20px}
        .rc-val{font-size:18px}
    }

    /* Small mobile: 1 col stats, hide table show cards */
    @media(max-width:575px){
        .d{padding:12px}
        .topbar{padding:14px 16px}
        .t-left h1{font-size:16px}
        .t-left p{font-size:12px}
        .clk-time{font-size:16px}
        .clk-date{font-size:10px}
        .sep{display:none}
        #clockCanvas{display:none !important}

        .sg{grid-template-columns:repeat(2,minmax(0,1fr))}
        .sc{padding:14px 12px;gap:10px}
        .si{width:36px;height:36px;font-size:15px}
        .s-val{font-size:20px}
        .s-label{font-size:11px}

        .rg{grid-template-columns:1fr 1fr}
        .rc-wide{grid-column:span 2}
        .rc-val{font-size:18px}

        /* Hide desktop table, show mobile cards */
        .table-wrap{display:none}
        .mobile-list{display:flex}
    }

    /* Very small: full 1 col */
    @media(max-width:400px){
        .sg{grid-template-columns:1fr 1fr}
        .rg{grid-template-columns:1fr}
        .rc-wide{grid-column:span 1}
    }
</style>

<div class="d">

    {{-- Top Bar --}}
    <div class="topbar">
        <div class="t-left">
            <h1>{{ __('Welcome back') }}</h1>
            <p>{{ __("Here's what's happening with your platform today") }}</p>
        </div>
        <div class="t-right">
            <div class="clk-txt">
                <div class="clk-time" id="current-time">{{ now()->format('h:i:s A') }}</div>
                <div class="clk-date" id="current-date">{{ now()->format('l, F j, Y') }}</div>
            </div>
            <div class="sep"></div>
            <canvas id="clockCanvas" width="52" height="52"
                style="border-radius:50%;border:2px solid var(--accent);background:#fff;display:block;flex-shrink:0"></canvas>
        </div>
    </div>

    {{-- Stats --}}
    <div class="sec-hd">{{ __('Overview') }}</div>
    <div class="sg">

        <div class="sc">
            <div class="si"><i class="flaticon-users"></i></div>
            <div>
                <div class="s-label">{{ __('Total Dealar') }}</div>
                <div class="s-val">0</div>
                <div class="s-trend">{{ __('Registered') }}</div>
            </div>
        </div>
         <div class="sc">
            <div class="si"><i class="flaticon-users"></i></div>
            <div>
                <div class="s-label">{{ __('Pending Dealar') }}</div>
                <div class="s-val">0</div>
                <div class="s-trend">{{ __('Registered') }}</div>
            </div>
        </div>
        <div class="sc">
            <div class="si"><i class="flaticon-price-tag"></i></div>
            <div>
                <div class="s-label">{{ __('Total Sales') }}</div>
                <div class="s-val">0</div>
                <div class="s-trend">{{ __('All time') }}</div>
            </div>
        </div>

         <div class="sc">
            <div class="si"><i class="flaticon-price-tag"></i></div>
            <div>
                <div class="s-label">{{ __('Today Sales') }}</div>
                <div class="s-val">0</div>
                <div class="s-trend">{{ __('Today') }}</div>
            </div>
        </div>

        <div class="sc">
            <div class="si"><i class="flaticon-list"></i></div>
            <div>
                <div class="s-label">{{ __('Total Products') }}</div>
                <div class="s-val">0</div>
                <div class="s-trend">{{ __('Published') }}</div>
            </div>
        </div>

         <div class="sc">
        <div class="si"><i class="flaticon-share"></i></div>
            <div>
                <div class="s-label">{{ __('Total Purchases') }}</div>
                <div class="s-val">{{ 0 }}</div>
                <div class="s-trend">{{ __('All time') }}</div>
            </div>
        </div>
         <div class="sc">
            <div class="si"><i class="flaticon-cart"></i></div>
            <div>
                <div class="s-label">{{ __('Today Purchases') }}</div>
                <div class="s-val">0</div>
                <div class="s-trend">—</div>
            </div>
        </div>
    </div>

    {{-- Info Row --}}
    <div class="rg">

    </div>

    {{-- Recent Transactions --}}
    <div class="sec-hd">{{ __('Recent transactions') }}</div>
    <div class="tc">
        <div class="tc-head">
            <div class="tc-head-left">
                <i class="flaticon-cart"></i>
                <span>{{ __('Transaction history') }}</span>
            </div>

                <span class="tc-badge"> {{ __('records') }}</span>

        </div>



            {{-- Desktop Table --}}
            <div class="table-wrap">
                <table class="dt">
                    <thead>
                        <tr>
                            <th>{{ __('Dealar Name') }}</th>
                            <th>{{ __('For') }}</th>
                            <th>{{ __('Transaction ID') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($recentTransaction as $data)
                        <tr>
                            <td class="fw">{{ $data->user->name ?? '—' }}</td>
                            <td class="muted">{{ $data->payment_for }}</td>
                            <td class="mono">{{ $data->transaction_id }}</td>
                            <td class="muted">{{ $data->payment_method }}</td>
                            <td class="amt">৳{{ $data->amount }}</td>
                            <td>
                                @if($data->status == 'Pending')
                                    <span class="bd bd-w">{{ $data->status }}</span>
                                @elseif($data->status == 'Delivered')
                                    <span class="bd bd-s">{{ $data->status }}</span>
                                @elseif($data->status == 'Canceled')
                                    <span class="bd bd-d">{{ $data->status }}</span>
                                @else
                                    <span class="bd bd-i">{{ $data->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List --}}
            <div class="mobile-list">
                {{-- @foreach($recentTransaction as $data)
                <div class="m-card">
                    <div class="m-card-top">
                        <span class="m-card-name">{{ $data->user->name ?? '—' }}</span>
                        <span class="m-card-amt">৳{{ $data->price }}</span>
                    </div>
                    <div class="m-card-meta">
                        <span class="m-meta-pill">{{ $data->payment_for }}</span>
                        <span class="m-meta-pill">{{ $data->payment_method }}</span>
                        @if($data->status == 'Pending')
                            <span class="bd bd-w">{{ $data->status }}</span>
                        @elseif($data->status == 'Delivered')
                            <span class="bd bd-s">{{ $data->status }}</span>
                        @elseif($data->status == 'Canceled')
                            <span class="bd bd-d">{{ $data->status }}</span>
                        @else
                            <span class="bd bd-i">{{ $data->status }}</span>
                        @endif
                    </div>
                    <div class="m-card-txn">{{ $data->transaction_id }}</div>
                </div>
                @endforeach --}}
            </div>

        {{-- @else --}}
            <div class="empty">
                <i class="flaticon-cart" style="font-size:28px;display:block;margin-bottom:8px;opacity:.25;color:var(--accent)"></i>
                {{ __('No recent transactions found') }}
            </div>
        {{-- @endif --}}
    </div>

</div>

<script>
    function updateClock() {
        const n = new Date();
        const pad = x => String(x).padStart(2, '0');
        const h = n.getHours(), m = n.getMinutes(), s = n.getSeconds();
        const ampm = h >= 12 ? 'PM' : 'AM', h12 = h % 12 || 12;
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        const te = document.getElementById('current-time');
        const de = document.getElementById('current-date');
        if (te) te.textContent = pad(h12) + ':' + pad(m) + ':' + pad(s) + ' ' + ampm;
        if (de) de.textContent = days[n.getDay()] + ', ' + months[n.getMonth()] + ' ' + n.getDate() + ', ' + n.getFullYear();

        const canvas = document.getElementById('clockCanvas');
        if (!canvas || canvas.style.display === 'none') return;
        const ctx = canvas.getContext('2d');
        const cx = 26, cy = 26, r = 22;

        ctx.clearRect(0, 0, 52, 52);

        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, Math.PI * 2);
        ctx.strokeStyle = '#ede8ff';
        ctx.lineWidth = 1;
        ctx.stroke();

        for (let i = 0; i < 12; i++) {
            const a = i * Math.PI / 6, len = i % 3 === 0 ? 4 : 2;
            ctx.beginPath();
            ctx.moveTo(cx + (r - len) * Math.sin(a), cy - (r - len) * Math.cos(a));
            ctx.lineTo(cx + r * Math.sin(a), cy - r * Math.cos(a));
            ctx.strokeStyle = '#c4b5fd';
            ctx.lineWidth = i % 3 === 0 ? 1.2 : 0.6;
            ctx.stroke();
        }

        const sA = (s / 60) * Math.PI * 2;
        const mA = ((m + s / 60) / 60) * Math.PI * 2;
        const hA = ((h % 12 + m / 60) / 12) * Math.PI * 2;

        ctx.lineCap = 'round';
        ctx.beginPath(); ctx.moveTo(cx, cy); ctx.lineTo(cx + Math.sin(hA) * 12, cy - Math.cos(hA) * 12);
        ctx.strokeStyle = '#0f1117'; ctx.lineWidth = 2; ctx.stroke();

        ctx.beginPath(); ctx.moveTo(cx, cy); ctx.lineTo(cx + Math.sin(mA) * 17, cy - Math.cos(mA) * 17);
        ctx.strokeStyle = '#374151'; ctx.lineWidth = 1.2; ctx.stroke();

        ctx.beginPath(); ctx.moveTo(cx, cy); ctx.lineTo(cx + Math.sin(sA) * 20, cy - Math.cos(sA) * 20);
        ctx.strokeStyle = '#835eff'; ctx.lineWidth = 1; ctx.stroke();

        ctx.beginPath(); ctx.arc(cx, cy, 2.5, 0, Math.PI * 2);
        ctx.fillStyle = '#835eff'; ctx.fill();
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection
