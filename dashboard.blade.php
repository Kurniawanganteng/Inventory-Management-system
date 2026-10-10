@extends('layouts.app')
@section('title','Dashboard') @section('heading','Dashboard')
@section('content')
<section class="welcome"><div><h2>Selamat datang, {{ auth()->user()->name }} 👋</h2><p>Pantau persediaan dan aktivitas gudang dari satu tempat.</p></div><div class="welcome-tag">✦ Inventory overview</div></section>
<div class="stats">
<div class="stat-card"><div class="stat-top">Total Produk <span class="stat-icon">▤</span></div><div class="stat-number">{{ number_format($totalProducts) }}</div><div class="stat-foot">Produk terdaftar</div></div>
<div class="stat-card"><div class="stat-top">Kategori <span class="stat-icon">▧</span></div><div class="stat-number">{{ number_format($totalCategories) }}</div><div class="stat-foot">Kategori aktif</div></div>
<div class="stat-card"><div class="stat-top">Nilai Inventory <span class="stat-icon">◇</span></div><div class="stat-number" style="font-size:23px">Rp {{ number_format($inventoryValue,0,',','.') }}</div><div class="stat-foot">Total stok × harga</div></div>
<div class="stat-card"><div class="stat-top">Stok Rendah <span class="stat-icon">⚑</span></div><div class="stat-number">{{ number_format($lowStock) }}</div><div class="stat-foot">Produk perlu diperiksa</div></div></div>
<div class="grid-2"><section class="panel"><div class="panel-head"><div><h2>Produk Stok Rendah</h2><p>Produk pada atau di bawah batas minimum</p></div></div>@forelse($lowProducts as $product)<div style="display:flex;justify-content:space-between;padding:13px 0;border-bottom:1px solid #f0f2f7"><div><b>{{ $product->name }}</b><div class="muted">{{ $product->sku }} · {{ $product->category->name }}</div></div><span class="badge orange">{{ $product->stock }} unit</span></div>@empty<div class="empty">Semua stok aman.</div>@endforelse</section>
<section class="panel"><div class="panel-head"><div><h2>Aktivitas Terbaru</h2><p>Histori pergerakan stok</p></div></div>@forelse($recent as $movement)<div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f0f2f7"><div><b>{{ $movement->product->name }}</b><div class="muted">{{ $movement->created_at->format('d M Y H:i') }}</div></div><span class="badge {{ $movement->type==='IN'?'green':'purple' }}">{{ $movement->type==='IN'?'+':'−' }}{{ $movement->quantity }}</span></div>@empty<div class="empty">Belum ada transaksi.</div>@endforelse</section></div>
@endsection
