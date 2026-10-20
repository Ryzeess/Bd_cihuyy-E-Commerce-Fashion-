<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store - Antigravity Database Studio (12 Entitas ERD & dbdiagram)</title>
    <!-- Google Fonts & CDN Icon & Mermaid -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({
            startOnLoad: true,
            theme: 'dark',
            themeVariables: {
                darkMode: true,
                background: '#0a0f1d',
                primaryColor: '#0284c7',
                primaryBorderColor: '#38bdf8',
                primaryTextColor: '#f8fafc',
                lineColor: '#38bdf8',
                secondaryColor: '#1e293b',
                tertiaryColor: '#0f172a'
            }
        });
    </script>
    <style>
        :root {
            --bg-base: #070b14;
            --bg-card: rgba(15, 23, 42, 0.75);
            --bg-card-hover: rgba(30, 41, 59, 0.85);
            --border-glow: rgba(56, 189, 248, 0.25);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --primary: #38bdf8;
            --primary-glow: rgba(56, 189, 248, 0.4);
            --accent-purple: #a855f7;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-base);
            background-image: 
                radial-gradient(at 10% 15%, rgba(56, 189, 248, 0.12) 0px, transparent 40%),
                radial-gradient(at 90% 10%, rgba(168, 85, 247, 0.12) 0px, transparent 45%),
                radial-gradient(at 50% 80%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            font-family: var(--font-sans);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Glassmorphism Header */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(16px);
            background: rgba(7, 11, 20, 0.85);
            border-bottom: 1px solid var(--border-subtle);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0284c7, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: white;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.35);
        }

        .brand-text h1 {
            font-size: 1.25rem;
            font-weight: 800;
            background: linear-gradient(to right, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: var(--primary);
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Header Navigation Tabs */
        .nav-tabs {
            display: flex;
            gap: 0.4rem;
            background: rgba(15, 23, 42, 0.85);
            padding: 0.35rem;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-family: var(--font-sans);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .tab-btn:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.2), rgba(168, 85, 247, 0.2));
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.15);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-live-run {
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: white;
            padding: 0.55rem 1.2rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
            transition: all 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-live-run:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.5);
        }

        /* Main Container */
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.25s ease;
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            border-color: var(--border-glow);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .stat-info span {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .stat-info h3 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            margin-top: 0.25rem;
        }

        .stat-icon {
            font-size: 2rem;
            padding: 0.6rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Tab Content Control */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease forwards;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glass Panel */
        .glass-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            padding: 2rem;
            backdrop-filter: blur(16px);
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-title h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
        }

        .panel-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.25);
        }

        /* Steps Container */
        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-top: 1.5rem;
        }

        .step-card {
            background: rgba(11, 17, 32, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 1.25rem;
            position: relative;
            transition: all 0.25s;
        }

        .step-card:hover {
            border-color: rgba(56, 189, 248, 0.3);
            background: rgba(15, 23, 42, 0.9);
        }

        .step-num {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #0284c7, #6366f1);
            color: white;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 0.4rem;
        }

        .step-desc {
            font-size: 0.825rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .step-code {
            background: #05080e;
            padding: 0.45rem 0.75rem;
            border-radius: 6px;
            margin-top: 0.85rem;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: #38bdf8;
            border: 1px solid rgba(255, 255, 255, 0.05);
            word-break: break-all;
        }

        /* Modern Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            text-align: left;
            background: #0b1120;
        }

        .modern-table th {
            background: rgba(30, 41, 59, 0.7);
            color: #94a3b8;
            font-weight: 700;
            padding: 0.85rem 1.25rem;
            text-transform: uppercase;
            font-size: 0.725rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-subtle);
        }

        .modern-table td {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: #cbd5e1;
        }

        .modern-table tr:hover td {
            background: rgba(56, 189, 248, 0.04);
            color: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.725rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-paid, .status-dikirim, .status-selesai {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-processing, .status-diproses {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-pending {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        /* Code Viewers */
        .code-box-wrapper {
            background: #090e18;
            border-radius: 14px;
            border: 1px solid var(--border-subtle);
            overflow: hidden;
            margin-top: 1rem;
        }

        .code-toolbar {
            background: #0c1322;
            padding: 0.7rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .file-label {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-copy {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #cbd5e1;
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
            font-size: 0.775rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: var(--font-sans);
            font-weight: 600;
        }

        .btn-copy:hover {
            background: rgba(56, 189, 248, 0.2);
            border-color: var(--primary);
            color: white;
        }

        pre code {
            display: block;
            padding: 1.25rem;
            font-family: var(--font-mono);
            font-size: 0.85rem;
            color: #e2e8f0;
            overflow-x: auto;
            max-height: 540px;
            line-height: 1.6;
        }

        /* Mermaid Wrapper */
        .mermaid-wrapper {
            background: #090e18;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 2rem;
            display: flex;
            justify-content: center;
            overflow-x: auto;
            margin-bottom: 2rem;
        }

        /* SVG Image Frame */
        .svg-frame {
            background: #060912;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.25rem;
            overflow: auto;
            max-height: 820px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            position: relative;
        }

        #svg-wrapper {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top left;
            display: inline-block;
        }

        #svg-wrapper svg {
            width: 1780px;
            height: 1140px;
            display: block;
            border-radius: 10px;
        }

        #svg-wrapper.is-fit-screen {
            width: 100% !important;
            transform: none !important;
            display: block;
        }

        #svg-wrapper.is-fit-screen svg {
            width: 100% !important;
            height: auto !important;
        }

        /* Table Schema Visual Cards Grid (12 Entities) */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.25rem;
            margin-top: 1.5rem;
        }

        .table-card {
            background: #0b1120;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.25s;
        }

        .table-card:hover {
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            transform: translateY(-2px);
        }

        .table-card-header {
            background: rgba(30, 41, 59, 0.6);
            padding: 0.8rem 1.2rem;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-name {
            font-family: var(--font-mono);
            font-weight: 700;
            color: #38bdf8;
            font-size: 0.95rem;
        }

        .table-type {
            font-size: 0.7rem;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            font-weight: 700;
        }

        .type-master {
            background: rgba(2, 132, 199, 0.2);
            color: #38bdf8;
        }

        .type-variant {
            background: rgba(37, 99, 235, 0.2);
            color: #93c5fd;
        }

        .type-trans {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        .type-user {
            background: rgba(168, 85, 247, 0.2);
            color: #c084fc;
        }

        .type-logistik {
            background: rgba(245, 158, 11, 0.2);
            color: #fde68a;
        }

        .column-list {
            padding: 0.6rem 0;
            font-size: 0.825rem;
        }

        .column-row {
            padding: 0.4rem 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        }

        .column-row:last-child {
            border-bottom: none;
        }

        .column-name {
            font-family: var(--font-mono);
            color: #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .badge-pk {
            background: #eab308;
            color: #000;
            font-size: 0.625rem;
            font-weight: 800;
            padding: 0.1rem 0.35rem;
            border-radius: 3px;
        }

        .badge-fk {
            background: #0284c7;
            color: #fff;
            font-size: 0.625rem;
            font-weight: 700;
            padding: 0.1rem 0.35rem;
            border-radius: 3px;
        }

        .badge-uk {
            background: #10b981;
            color: #000;
            font-size: 0.625rem;
            font-weight: 800;
            padding: 0.1rem 0.35rem;
            border-radius: 3px;
        }

        .column-type {
            font-family: var(--font-mono);
            color: #64748b;
            font-size: 0.775rem;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="brand-badge">
            <div class="logo-icon">BD</div>
            <div class="brand-text">
                <h1>Fashion Store Database Studio</h1>
                <p>12 Entitas BRD &bull; DBML &bull; dbdiagram.io &bull; SQL DDL/DML</p>
            </div>
        </div>

        <nav class="nav-tabs">
            <button class="tab-btn active" onclick="switchTab('tab-overview', this)">
                <span>⚡</span> Overview &amp; Demo
            </button>
            <button class="tab-btn" onclick="switchTab('tab-dbml', this)">
                <span>📄</span> 1. DBML (dbdiagram.io)
            </button>
            <button class="tab-btn" onclick="switchTab('tab-erd', this)">
                <span>📊</span> 2. ERD &amp; Visual Schema
            </button>
            <button class="tab-btn" onclick="switchTab('tab-entities', this)">
                <span>📋</span> 3. 12 Entitas &amp; Atribut
            </button>
            <button class="tab-btn" onclick="switchTab('tab-sql', this)">
                <span>💻</span> 4. SQL DDL &amp; Query
            </button>
        </nav>

        <div class="header-actions">
            <a href="https://dbdiagram.io" target="_blank" class="btn-live-run">
                <span>↗</span> Buka di dbdiagram.io
            </a>
        </div>
    </header>

    <div class="container">

        <!-- Stat Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <span>Total Entitas BRD</span>
                    <h3>12 Tabel</h3>
                </div>
                <div class="stat-icon">🗂️</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Katalog Produk</span>
                    <h3>{{ $produkCount }} Model</h3>
                </div>
                <div class="stat-icon">🛍️</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Varian SKU</span>
                    <h3>{{ $varianCount }} Variasi</h3>
                </div>
                <div class="stat-icon">🏷️</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Pesanan Berhasil</span>
                    <h3>{{ $pesananCount }} Transaksi</h3>
                </div>
                <div class="stat-icon">📦</div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 1: OVERVIEW & DEMO -->
        <!-- ============================================== -->
        <div id="tab-overview" class="tab-content active">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>🚀 Panduan Mendemokan ERD 12 Entitas di Antigravity</h2>
                        <span class="panel-badge">12 Entities Verified</span>
                    </div>
                </div>
                
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                    Seluruh 12 entitas sesuai kebutuhan dokumen BRD telah dirancang lengkap dengan kardinalitas relasi, kunci primer (PK), kunci tamu (FK), dan tipe data yang presisi:
                </p>

                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-num">1</div>
                        <h4 class="step-title">1. DBML di Editor</h4>
                        <p class="step-desc">
                            Buka file <code>database/schema.dbml</code>. Format ini langsung siap Anda copy-paste ke <b>https://dbdiagram.io</b> untuk melihat ERD diagram interaktif secara instan!
                        </p>
                        <div class="step-code">database/schema.dbml</div>
                    </div>

                    <div class="step-card">
                        <div class="step-num">2</div>
                        <h4 class="step-title">2. ERD Visual &amp; Mermaid</h4>
                        <p class="step-desc">
                            Klik tab <b>"2. ERD &amp; Visual Schema"</b> untuk melihat diagram visual relasi 12 entitas beserta gambar SVG resolusi tinggi dan Mermaid diagram relasi.
                        </p>
                        <div class="step-code">Mermaid + dbdiagram.svg</div>
                    </div>

                    <div class="step-card">
                        <div class="step-num">3</div>
                        <h4 class="step-title">3. Eksekusi SQL di Terminal</h4>
                        <p class="step-desc">
                            Jalankan perintah berikut di terminal Antigravity. Script akan mereset, membuat 12 tabel, mengisi sampel data, dan menjalankan pengujian multi-join query:
                        </p>
                        <div class="step-code">php database/demo.php</div>
                    </div>

                    <div class="step-card">
                        <div class="step-num">4</div>
                        <h4 class="step-title">4. Server Web Dashboard</h4>
                        <p class="step-desc">
                            Aplikasi web dashboard studio ini dapat diakses kapan saja menggunakan Laravel dev server bawaan:
                        </p>
                        <div class="step-code">php artisan serve</div>
                    </div>
                </div>
            </div>

            <!-- Bukti Live Query Pesanan -->
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>⚡ Data Transaksi Live (Multi-JOIN 6 Entitas)</h2>
                        <span class="panel-badge">Pesanan &bull; Pelanggan &bull; Voucher &bull; Pembayaran &bull; Pengiriman &bull; Kurir</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>No Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Voucher</th>
                                <th>Total Bayar</th>
                                <th>Status Order</th>
                                <th>Metode Bayar</th>
                                <th>Status Bayar</th>
                                <th>Ekspedisi &amp; Resi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td><strong style="color: #38bdf8;">{{ $order->nomor_pesanan }}</strong></td>
                                <td>
                                    <strong>{{ $order->customer_name }}</strong><br>
                                    <span style="color: #64748b; font-size: 0.75rem;">{{ $order->customer_email }}</span>
                                </td>
                                <td>
                                    @if($order->kode_voucher)
                                        <span style="color: #a855f7; font-weight: 700; font-family: var(--font-mono);">🎟️ {{ $order->kode_voucher }}</span>
                                    @else
                                        <span style="color: #64748b;">-</span>
                                    @endif
                                </td>
                                <td><strong>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($order->status_pesanan) }}">
                                        {{ str_replace('_', ' ', strtoupper($order->status_pesanan)) }}
                                    </span>
                                </td>
                                <td><code>{{ strtoupper($order->metode_pembayaran ?? 'COD') }}</code></td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($order->status_pembayaran) }}">
                                        {{ strtoupper($order->status_pembayaran ?? 'MENUNGGU') }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color: #f59e0b; font-weight: 600;">{{ $order->nama_kurir }}</span><br>
                                    <code style="font-size: 0.75rem; color: #94a3b8;">{{ $order->nomor_resi ?? 'Belum ada resi' }}</code>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: DBML UNTUK DBDATAGRAM.IO -->
        <!-- ============================================== -->
        <div id="tab-dbml" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>📄 DBML (Database Markup Language) Siap Pakai di dbdiagram.io</h2>
                        <span class="panel-badge">database/schema.dbml</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button class="btn-copy" onclick="copyToClipboard('dbml-code', this)">📋 Salin Kode DBML</button>
                        <a href="https://dbdiagram.io" target="_blank" class="btn-copy" style="text-decoration: none; color: #38bdf8;">↗ Buka dbdiagram.io</a>
                    </div>
                </div>

                <div style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 10px; padding: 1rem; margin-bottom: 1.25rem; font-size: 0.85rem;">
                    💡 <b>Cara Melihat Diagram di dbdiagram.io:</b> Klik tombol <b>"Salin Kode DBML"</b> di atas, lalu buka <b>https://dbdiagram.io</b>, buat diagram baru, dan paste kode di kolom editor sebelah kiri. Diagram visual ERD beserta seluruh relasi akan langsung muncul!
                </div>

                <div class="code-box-wrapper">
                    <div class="code-toolbar">
                        <div class="file-label">📄 schema.dbml (12 Entitas, Relasi FK, &amp; TableGroup)</div>
                        <span style="color: #64748b; font-size: 0.75rem;">DBML v2.0 Standard</span>
                    </div>
                    <pre><code id="dbml-code">{{ $dbmlContent }}</code></pre>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 3: ERD DIAGRAM & VISUAL SCHEMA -->
        <!-- ============================================== -->
        <div id="tab-erd" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <button type="button" class="btn-copy" onclick="zoomSvg(1.2)">➕ Zoom In</button>
                        <button type="button" class="btn-copy" onclick="zoomSvg(0.8)">➖ Zoom Out</button>
                        <button type="button" class="btn-copy" onclick="resetSvgZoom()">🔄 100%</button>
                        <button type="button" class="btn-copy" onclick="toggleFitScreen(this)">↔️ Fit / Full Size</button>
                        <a href="/erd-svg" target="_blank" class="btn-copy" style="text-decoration: none; color: #38bdf8; font-weight: 700;">🔍 Buka SVG di Tab Baru</a>
                    </div>
                </div>

                <div style="background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 10px; padding: 0.75rem 1.25rem; margin-bottom: 1rem; font-size: 0.825rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                    <span>💡 <b>Tips Tampilan:</b> Gunakan tombol <b>Zoom In (+)</b> untuk melihat tulisan lebih besar dan jelas, atau klik <b>"Buka SVG di Tab Baru"</b> untuk melihat file vektor asli full-screen.</span>
                    <span id="zoom-level-text" style="color: #38bdf8; font-family: var(--font-mono); font-weight: 700;">Zoom: 100%</span>
                </div>

                <div class="svg-frame" id="svg-frame-container">
                    <div id="svg-wrapper">
                        {!! $svgContent !!}
                    </div>
                </div>
            </div>

            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>🔗 Diagram Relasi Entitas Interaktif (Mermaid)</h2>
                        <span class="panel-badge">Live Mermaid Renderer</span>
                    </div>
                </div>

                <div class="mermaid-wrapper">
                    <div class="mermaid">
erDiagram
    KATEGORI ||--o{ PRODUK : "1:N mengelompokkan"
    PRODUK ||--o{ VARIAN_PRODUK : "1:N variasi"
    WARNA ||--o{ VARIAN_PRODUK : "1:N opsi_warna"
    UKURAN ||--o{ VARIAN_PRODUK : "1:N opsi_ukuran"
    PELANGGAN ||--o{ PESANAN : "1:N membuat"
    VOUCHER ||--o{ PESANAN : "1:N diterapkan"
    PESANAN ||--|{ DETAIL_PESANAN : "1:N memuat"
    VARIAN_PRODUK ||--o{ DETAIL_PESANAN : "1:N dipesan"
    PESANAN ||--|| PEMBAYARAN : "1:1 dibayar"
    PESANAN ||--|| PENGIRIMAN : "1:1 dikirim"
    KURIR ||--o{ PENGIRIMAN : "1:N mengantar"

    PELANGGAN {
        int id_pelanggan PK
        string nama
        string email UK
        string password
        string no_telepon
        text alamat
        datetime tanggal_daftar
    }

    KATEGORI {
        int id_kategori PK
        string nama_kategori
        text deskripsi
    }

    WARNA {
        int id_warna PK
        string nama_warna
        string kode_warna
    }

    UKURAN {
        int id_ukuran PK
        string nama_ukuran
        string keterangan
    }

    PRODUK {
        int id_produk PK
        int id_kategori FK
        string nama_produk
        decimal harga
        text deskripsi
        string bahan
        string gaya
        int berat
    }

    VARIAN_PRODUK {
        int id_varian PK
        int id_produk FK
        int id_warna FK
        int id_ukuran FK
        string kode_barang UK
        int stok
    }

    VOUCHER {
        int id_voucher PK
        string kode_voucher UK
        string tipe_diskon
        decimal nilai_diskon
        decimal minimal_belanja
        string status
    }

    PESANAN {
        int id_pesanan PK
        string nomor_pesanan UK
        int id_pelanggan FK
        int id_voucher FK
        datetime tanggal_pesanan
        decimal total_bayar
        string status_pesanan
    }

    DETAIL_PESANAN {
        int id_detail PK
        int id_pesanan FK
        int id_varian FK
        int jumlah
        decimal harga_satuan
        decimal subtotal
    }

    PEMBAYARAN {
        int id_pembayaran PK
        int id_pesanan FK
        string metode_pembayaran
        decimal jumlah_bayar
        string status_pembayaran
    }

    KURIR {
        int id_kurir PK
        string nama_kurir
        string kode_kurir UK
        string layanan
        decimal tarif_per_kg
    }

    PENGIRIMAN {
        int id_pengiriman PK
        int id_pesanan FK
        int id_kurir FK
        string nomor_resi UK
        string status_pengiriman
        decimal biaya_ongkir
    }
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 4: KATALOG 12 ENTITAS & ATRIBUT (BRD) -->
        <!-- ============================================== -->
        <div id="tab-entities" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>📋 Rincian Atribut &amp; Tipe Data 12 Entitas</h2>
                        <span class="panel-badge">Sesuai Dokumen BRD</span>
                    </div>
                </div>

                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    Daftar lengkap 12 entitas beserta nama kolom, primary key (PK), foreign key (FK), unique key (UK), dan tipe data masing-masing:
                </p>

                <div class="tables-grid">
                    <!-- 1. Pelanggan -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">1. pelanggan</span>
                            <span class="table-type type-user">Customer</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_pelanggan</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">nama</span><span class="column-type">VARCHAR(100)</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> email</span><span class="column-type">VARCHAR(100)</span></div>
                            <div class="column-row"><span class="column-name">password</span><span class="column-type">VARCHAR(255)</span></div>
                            <div class="column-row"><span class="column-name">no_telepon</span><span class="column-type">VARCHAR(20)</span></div>
                            <div class="column-row"><span class="column-name">alamat</span><span class="column-type">TEXT</span></div>
                            <div class="column-row"><span class="column-name">tanggal_daftar</span><span class="column-type">DATETIME</span></div>
                        </div>
                    </div>

                    <!-- 2. Kategori -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">2. kategori</span>
                            <span class="table-type type-master">Master</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_kategori</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">nama_kategori</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">deskripsi</span><span class="column-type">TEXT</span></div>
                        </div>
                    </div>

                    <!-- 3. Warna -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">3. warna</span>
                            <span class="table-type type-master">Master</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_warna</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">nama_warna</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">kode_warna</span><span class="column-type">VARCHAR(10)</span></div>
                        </div>
                    </div>

                    <!-- 4. Ukuran -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">4. ukuran</span>
                            <span class="table-type type-master">Master</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_ukuran</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">nama_ukuran</span><span class="column-type">VARCHAR(20)</span></div>
                            <div class="column-row"><span class="column-name">keterangan</span><span class="column-type">VARCHAR(100)</span></div>
                        </div>
                    </div>

                    <!-- 5. Produk -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">5. produk</span>
                            <span class="table-type type-master">Katalog</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_produk</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_kategori</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">nama_produk</span><span class="column-type">VARCHAR(150)</span></div>
                            <div class="column-row"><span class="column-name">harga</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">deskripsi</span><span class="column-type">TEXT</span></div>
                            <div class="column-row"><span class="column-name">bahan</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">gaya</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">berat</span><span class="column-type">INT (gram)</span></div>
                        </div>
                    </div>

                    <!-- 6. Varian Produk -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">6. varian_produk</span>
                            <span class="table-type type-variant">SKU Varian</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_varian</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_produk</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_warna</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_ukuran</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> kode_barang</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">stok</span><span class="column-type">INT</span></div>
                        </div>
                    </div>

                    <!-- 7. Voucher -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">7. voucher</span>
                            <span class="table-type type-user">Promo</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_voucher</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> kode_voucher</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">tipe_diskon</span><span class="column-type">ENUM('persen',..)</span></div>
                            <div class="column-row"><span class="column-name">nilai_diskon</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">minimal_belanja</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">status</span><span class="column-type">VARCHAR(20)</span></div>
                        </div>
                    </div>

                    <!-- 8. Pesanan -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">8. pesanan</span>
                            <span class="table-type type-trans">Transaksi</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_pesanan</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> nomor_pesanan</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_pelanggan</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_voucher</span><span class="column-type">INT (opt)</span></div>
                            <div class="column-row"><span class="column-name">tanggal_pesanan</span><span class="column-type">DATETIME</span></div>
                            <div class="column-row"><span class="column-name">total_harga</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">potongan_diskon</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">total_bayar</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">status_pesanan</span><span class="column-type">ENUM</span></div>
                        </div>
                    </div>

                    <!-- 9. Detail Pesanan -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">9. detail_pesanan</span>
                            <span class="table-type type-trans">Item Rincian</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_detail</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_pesanan</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_varian</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">jumlah</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">harga_satuan</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">subtotal</span><span class="column-type">DECIMAL(12,2)</span></div>
                        </div>
                    </div>

                    <!-- 10. Pembayaran -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">10. pembayaran</span>
                            <span class="table-type type-trans">1:1 Transaksi</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_pembayaran</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_pesanan</span><span class="column-type">INT &bull; 1:1</span></div>
                            <div class="column-row"><span class="column-name">metode_pembayaran</span><span class="column-type">ENUM</span></div>
                            <div class="column-row"><span class="column-name">jumlah_bayar</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">waktu_bayar</span><span class="column-type">DATETIME</span></div>
                            <div class="column-row"><span class="column-name">status_pembayaran</span><span class="column-type">ENUM</span></div>
                        </div>
                    </div>

                    <!-- 11. Kurir -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">11. kurir</span>
                            <span class="table-type type-logistik">Master Ekspedisi</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_kurir</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">nama_kurir</span><span class="column-type">VARCHAR(100)</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> kode_kurir</span><span class="column-type">VARCHAR(20)</span></div>
                            <div class="column-row"><span class="column-name">layanan</span><span class="column-type">VARCHAR(50)</span></div>
                            <div class="column-row"><span class="column-name">tarif_per_kg</span><span class="column-type">DECIMAL(12,2)</span></div>
                        </div>
                    </div>

                    <!-- 12. Pengiriman -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">12. pengiriman</span>
                            <span class="table-type type-logistik">1:1 Logistik</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id_pengiriman</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_pesanan</span><span class="column-type">INT &bull; 1:1</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> id_kurir</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> nomor_resi</span><span class="column-type">VARCHAR(100)</span></div>
                            <div class="column-row"><span class="column-name">status_pengiriman</span><span class="column-type">ENUM</span></div>
                            <div class="column-row"><span class="column-name">biaya_ongkir</span><span class="column-type">DECIMAL(12,2)</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 5: SQL CODE & QUERY HASIL -->
        <!-- ============================================== -->
        <div id="tab-sql" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>💻 SQL Script (DDL &amp; DML 12 Entitas)</h2>
                        <span class="panel-badge">database/schema.sql</span>
                    </div>
                    <button class="btn-copy" onclick="copyToClipboard('sql-code', this)">📋 Salin Skrip SQL</button>
                </div>

                <div class="code-box-wrapper">
                    <div class="code-toolbar">
                        <div class="file-label">📄 schema.sql (12 Tabel DDL + DML Dummy Data + Multi-Join)</div>
                        <span style="color: #64748b; font-size: 0.75rem;">MySQL / MariaDB / SQLite Standard</span>
                    </div>
                    <pre><code id="sql-code">{{ $sqlContent }}</code></pre>
                </div>
            </div>

            <!-- Query Join Rincian Item -->
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>🔍 Hasil Multi-JOIN: Detail Pesanan &rarr; Varian &rarr; Warna &rarr; Ukuran &rarr; Produk &rarr; Kategori</h2>
                        <span class="panel-badge">6 Tables JOIN</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>No Pesanan</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Warna</th>
                                <th>Ukuran</th>
                                <th>SKU</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderItems as $item)
                            <tr>
                                <td><strong style="color: #38bdf8;">{{ $item->nomor_pesanan }}</strong></td>
                                <td><span style="color: #a855f7; font-weight: 600;">{{ $item->category_name }}</span></td>
                                <td><strong>{{ $item->product_name }}</strong></td>
                                <td><span style="background: rgba(255,255,255,0.06); padding: 0.2rem 0.5rem; border-radius: 4px;">{{ $item->color_name }}</span></td>
                                <td><span style="background: rgba(56,189,248,0.1); color: #38bdf8; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">{{ $item->size_name }}</span></td>
                                <td><code>{{ $item->sku }}</code></td>
                                <td><strong>{{ $item->quantity }}x</strong></td>
                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td><strong style="color: #10b981;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function switchTab(tabId, el) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            el.classList.add('active');
        }

        function copyToClipboard(elementId, btn) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerText;
                btn.innerText = '✅ Tersalin!';
                btn.style.borderColor = '#10b981';
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.borderColor = '';
                }, 2000);
            });
        }

        // SVG Interactive Zoom & Fit Controller
        let currentZoom = 1.0;
        let isFit = false;

        function updateZoomUI() {
            const wrapper = document.getElementById('svg-wrapper');
            const zoomText = document.getElementById('zoom-level-text');
            if (!wrapper) return;
            
            if (isFit) {
                wrapper.classList.add('is-fit-screen');
                wrapper.style.transform = '';
                if (zoomText) zoomText.innerText = 'Mode: Fit Screen (Otomatis)';
            } else {
                wrapper.classList.remove('is-fit-screen');
                wrapper.style.transform = `scale(${currentZoom})`;
                if (zoomText) zoomText.innerText = `Zoom: ${Math.round(currentZoom * 100)}%`;
            }
        }

        function zoomSvg(factor) {
            isFit = false;
            currentZoom = Math.max(0.4, Math.min(2.5, currentZoom * factor));
            updateZoomUI();
        }

        function resetSvgZoom() {
            isFit = false;
            currentZoom = 1.0;
            updateZoomUI();
        }

        function toggleFitScreen(btn) {
            
       
        }
    </script>
</body>
</html>
