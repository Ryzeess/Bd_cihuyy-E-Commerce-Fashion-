<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cihuy Store - Antigravity Database Studio (DBML, dbdiagram, SQL)</title>
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
                background: '#0f172a',
                primaryColor: '#3b82f6',
                primaryBorderColor: '#60a5fa',
                primaryTextColor: '#f8fafc',
                lineColor: '#38bdf8',
                secondaryColor: '#1e293b',
                tertiaryColor: '#0f172a'
            }
        });
    </script>
    <style>
        :root {
            --bg-base: #090d16;
            --bg-card: rgba(17, 24, 39, 0.75);
            --bg-card-hover: rgba(30, 41, 59, 0.85);
            --border-glow: rgba(56, 189, 248, 0.2);
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
                radial-gradient(at 10% 20%, rgba(56, 189, 248, 0.12) 0px, transparent 40%),
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
            background: rgba(9, 13, 22, 0.8);
            border-bottom: 1px solid var(--border-subtle);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0284c7, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
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
            gap: 0.5rem;
            background: rgba(15, 23, 42, 0.8);
            padding: 0.35rem;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-family: var(--font-sans);
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            gap: 0.75rem;
            align-items: center;
        }

        .btn-live-run {
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.3);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-live-run:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.5);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Stat Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.25s, border-color 0.25s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
        }

        .stat-info span {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .stat-info h3 {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            margin-top: 0.2rem;
        }

        .stat-icon {
            font-size: 1.75rem;
            padding: 0.75rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-subtle);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glass Panel */
        .glass-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(16px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            margin-bottom: 2rem;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-title h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
        }

        .panel-badge {
            background: rgba(56, 189, 248, 0.15);
            color: var(--primary);
            border: 1px solid rgba(56, 189, 248, 0.3);
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            font-family: var(--font-mono);
        }

        /* Code Block Styling */
        .code-box-wrapper {
            position: relative;
            background: #060911;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
        }

        .code-toolbar {
            background: #0d121f;
            padding: 0.6rem 1rem;
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
            gap: 0.4rem;
        }

        .btn-copy {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 0.35rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
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
            max-height: 520px;
            line-height: 1.6;
        }

        /* Table Schema Visual Cards */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .table-card {
            background: #0f172a;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.25s;
        }

        .table-card:hover {
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
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
            padding: 0.15rem 0.4rem;
            background: rgba(168, 85, 247, 0.2);
            color: #c084fc;
            border-radius: 4px;
            font-weight: 600;
        }

        .column-list {
            padding: 0.75rem 0;
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
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
        }

        .badge-fk {
            background: #0284c7;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
        }

        .badge-uk {
            background: #10b981;
            color: #000;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
        }

        .column-type {
            font-family: var(--font-mono);
            color: #64748b;
            font-size: 0.75rem;
        }

        /* Data Tables */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin-top: 1rem;
        }

        .modern-table th {
            background: #0d1322;
            color: #94a3b8;
            font-weight: 600;
            text-align: left;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-family: var(--font-sans);
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
        }

        .modern-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: #cbd5e1;
        }

        .modern-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
            color: #ffffff;
        }

        .status-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-paid {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-processing {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Guide Steps */
        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .step-card {
            background: #0c111d;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 1.5rem;
            position: relative;
        }

        .step-num {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #0284c7, #8b5cf6);
            color: white;
            font-weight: 800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .step-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .step-code {
            background: #05080e;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            margin-top: 0.75rem;
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: #38bdf8;
            border: 1px solid rgba(255, 255, 255, 0.05);
            word-break: break-all;
        }

        /* Mermaid diagram wrapper */
        .mermaid-wrapper {
            background: #070b13;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 2rem;
            display: flex;
            justify-content: center;
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="brand-badge">
            <div class="logo-icon">BD</div>
            <div class="brand-text">
                <h1>Cihuy Store Database Studio</h1>
                <p>Antigravity Interactive Demo &bull; DBML &bull; dbdiagram &bull; SQL</p>
            </div>
        </div>

        <nav class="nav-tabs">
            <button class="tab-btn active" onclick="switchTab('tab-overview', this)">
                <span>⚡</span> Overview & Demo
            </button>
            <button class="tab-btn" onclick="switchTab('tab-dbml', this)">
                <span>📄</span> 1. DBML
            </button>
            <button class="tab-btn" onclick="switchTab('tab-erd', this)">
                <span>📊</span> 2. dbdiagram (ERD)
            </button>
            <button class="tab-btn" onclick="switchTab('tab-sql', this)">
                <span>💻</span> 3. SQL & Live Query
            </button>
        </nav>

        <div class="header-actions">
            <a href="https://dbdiagram.io" target="_blank" class="btn-live-run">
                <span>↗</span> Buka dbdiagram.io
            </a>
        </div>
    </header>

    <div class="container">

        <!-- Stat Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <span>Total Tabel</span>
                    <h3>7 Tables</h3>
                </div>
                <div class="stat-icon">🗂️</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Jenis Relasi</span>
                    <h3>1:1 &bull; 1:N &bull; N:M</h3>
                </div>
                <div class="stat-icon">🔗</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Orders Live</span>
                    <h3>{{ $ordersCount }} Pesanan</h3>
                </div>
                <div class="stat-icon">📦</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Produk Tersedia</span>
                    <h3>{{ $productsCount }} Item</h3>
                </div>
                <div class="stat-icon">🛍️</div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 1: OVERVIEW & CARA MENJALANKAN DI ANTIGRAVITY -->
        <!-- ============================================== -->
        <div id="tab-overview" class="tab-content active">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>🚀 Cara Mendemokan Langsung di Dalam Antigravity</h2>
                        <span class="panel-badge">Interactive Workflow</span>
                    </div>
                </div>
                
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                    Anda dapat mendemokan seluruh alur basis data (DBML &rarr; dbdiagram ERD &rarr; SQL DDL/DML &rarr; Live Query) secara langsung dari dalam lingkungan Antigravity tanpa harus install tools tambahan:
                </p>

                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-num">1</div>
                        <h4 class="step-title">Demo DBML di Editor</h4>
                        <p class="step-desc">
                            Buka file <code>database/schema.dbml</code> di tab editor Antigravity. Anda dapat menjelaskan struktur tabel, tipe data enum, dan penulisan referensi relasi (<code>-</code>, <code>></code>, <code><</code>).
                        </p>
                        <div class="step-code">database/schema.dbml</div>
                    </div>

                    <div class="step-card">
                        <div class="step-num">2</div>
                        <h4 class="step-title">Demo dbdiagram (ERD)</h4>
                        <p class="step-desc">
                            Lihat tab <b>"2. dbdiagram (ERD)"</b> di dashboard ini untuk melihat diagram visual relasi antar-tabel secara real-time via Mermaid, atau klik tombol <i>Copy DBML</i> lalu tempel di <i>dbdiagram.io</i>.
                        </p>
                        <div class="step-code">Mermaid Live + dbdiagram Canvas</div>
                    </div>

                    <div class="step-card">
                        <div class="step-num">3</div>
                        <h4 class="step-title">Demo SQL di Terminal</h4>
                        <p class="step-desc">
                            Jalankan script eksekusi SQL langsung di Terminal Antigravity. Script ini akan mereset, membuat 7 tabel, seeding data, dan menampilkan hasil tabel join interaktif!
                        </p>
                        <div class="step-code">php database/demo.php</div>
                    </div>

                    <div class="step-card">
                        <div class="step-num">4</div>
                        <h4 class="step-title">Demo Live Dashboard Web</h4>
                        <p class="step-desc">
                            Cukup jalankan web server Laravel bawaan untuk membuka studio interaktif ini kapan saja di port 8000:
                        </p>
                        <div class="step-code">php artisan serve</div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Live Query Pesanan -->
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>⚡ Bukti Live Query: Data Pesanan &amp; Pembayaran Aktif</h2>
                        <span class="panel-badge">Real Database Data</span>
                    </div>
                </div>

                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Customer</th>
                            <th>Kota Tujuan</th>
                            <th>Total Belanja</th>
                            <th>Status Order</th>
                            <th>Metode Bayar</th>
                            <th>Status Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><strong style="color: #38bdf8;">{{ $order->order_code }}</strong></td>
                            <td>{{ $order->customer_name }} <br><span style="color: #64748b; font-size: 0.75rem;">{{ $order->customer_email }}</span></td>
                            <td>{{ $order->city ?? '-' }}</td>
                            <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="status-badge {{ $order->order_status == 'paid' ? 'status-paid' : 'status-processing' }}">
                                    {{ strtoupper($order->order_status) }}
                                </span>
                            </td>
                            <td><code>{{ strtoupper($order->payment_method ?? 'COD') }}</code></td>
                            <td>
                                <span class="status-badge status-paid">
                                    {{ strtoupper($order->payment_status ?? 'UNPAID') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: DBML (DATABASE MARKUP LANGUAGE) -->
        <!-- ============================================== -->
        <div id="tab-dbml" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>📄 DBML (Database Markup Language)</h2>
                        <span class="panel-badge">database/schema.dbml</span>
                    </div>
                    <button class="btn-copy" onclick="copyToClipboard('dbml-code', this)">📋 Salin Kode DBML</button>
                </div>

                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    DBML adalah format deklaratif yang dirancang oleh tim <b>dbdiagram.io</b> agar perancangan arsitektur basis data cepat dibaca, ditulis, dan dikolaborasikan. Simbol relasi yang digunakan:
                    <span style="color: #38bdf8;"><code>-</code> (1-to-1)</span>, 
                    <span style="color: #a855f7;"><code>&gt;</code> (Many-to-One)</span>, dan 
                    <span style="color: #10b981;"><code>&lt;</code> (One-to-Many)</span>.
                </p>

                <div class="code-box-wrapper">
                    <div class="code-toolbar">
                        <div class="file-label">📄 schema.dbml (Siap untuk dbdiagram.io)</div>
                        <span style="color: #64748b; font-size: 0.75rem;">DBML v2.0 Standard</span>
                    </div>
                    <pre><code id="dbml-code">{{ $dbmlContent }}</code></pre>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 3: DBDATABASE / ERD VISUAL -->
        <!-- ============================================== -->
        <div id="tab-erd" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>📊 Diagram Relasi ERD Interaktif (dbdiagram Preview)</h2>
                        <span class="panel-badge">Visual Schema</span>
                    </div>
                </div>

                <div class="mermaid-wrapper">
                    <div class="mermaid">
erDiagram
    USERS ||--o| USER_PROFILES : "1:1 profile"
    USERS ||--o{ ORDERS : "1:N places"
    CATEGORIES ||--o{ PRODUCTS : "1:N categorizes"
    ORDERS ||--|{ ORDER_ITEMS : "1:N contains"
    PRODUCTS ||--o{ ORDER_ITEMS : "1:N ordered_in"
    ORDERS ||--|| PAYMENTS : "1:1 paid_with"

    USERS {
        int id PK
        string name
        string email UK
        string role
        timestamp created_at
    }

    USER_PROFILES {
        int id PK
        int user_id FK
        string phone
        string address
        string city
    }

    CATEGORIES {
        int id PK
        string name
        string slug UK
        string description
    }

    PRODUCTS {
        int id PK
        int category_id FK
        string name
        decimal price
        int stock
        boolean is_active
    }

    ORDERS {
        int id PK
        string order_code UK
        int user_id FK
        decimal total_amount
        string status
    }

    ORDER_ITEMS {
        int id PK
        int order_id FK
        int product_id FK
        int quantity
        decimal unit_price
        decimal subtotal
    }

    PAYMENTS {
        int id PK
        int order_id FK
        string payment_method
        decimal amount
        string payment_status
    }
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 0.5rem; font-size: 1.1rem; color: #fff;">
                    Struktur Kolom per Entitas Tabel (Katalog Skema)
                </h3>

                <div class="tables-grid">
                    <!-- Card Users -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">users</span>
                            <span class="table-type">Master</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">name</span><span class="column-type">VARCHAR(100)</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> email</span><span class="column-type">VARCHAR(150)</span></div>
                            <div class="column-row"><span class="column-name">role</span><span class="column-type">ENUM('admin',...)</span></div>
                        </div>
                    </div>

                    <!-- Card Profiles -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">user_profiles</span>
                            <span class="table-type">1:1 Extension</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> user_id</span><span class="column-type">INT &bull; 1:1</span></div>
                            <div class="column-row"><span class="column-name">phone</span><span class="column-type">VARCHAR(20)</span></div>
                            <div class="column-row"><span class="column-name">city</span><span class="column-type">VARCHAR(100)</span></div>
                        </div>
                    </div>

                    <!-- Card Categories -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">categories</span>
                            <span class="table-type">Master</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">name</span><span class="column-type">VARCHAR(100)</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> slug</span><span class="column-type">VARCHAR(120)</span></div>
                        </div>
                    </div>

                    <!-- Card Products -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">products</span>
                            <span class="table-type">Katalog</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> category_id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">name</span><span class="column-type">VARCHAR(150)</span></div>
                            <div class="column-row"><span class="column-name">price</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">stock</span><span class="column-type">INT</span></div>
                        </div>
                    </div>

                    <!-- Card Orders -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">orders</span>
                            <span class="table-type">Transaksi</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-uk">UK</span> order_code</span><span class="column-type">VARCHAR(30)</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> user_id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">total_amount</span><span class="column-type">DECIMAL(12,2)</span></div>
                            <div class="column-row"><span class="column-name">status</span><span class="column-type">ENUM</span></div>
                        </div>
                    </div>

                    <!-- Card Order Items -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">order_items</span>
                            <span class="table-type">Pivot / Detail</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> order_id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> product_id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">quantity</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name">subtotal</span><span class="column-type">DECIMAL(12,2)</span></div>
                        </div>
                    </div>

                    <!-- Card Payments -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <span class="table-name">payments</span>
                            <span class="table-type">1:1 Payment</span>
                        </div>
                        <div class="column-list">
                            <div class="column-row"><span class="column-name"><span class="badge-pk">PK</span> id</span><span class="column-type">INT</span></div>
                            <div class="column-row"><span class="column-name"><span class="badge-fk">FK</span> order_id</span><span class="column-type">INT &bull; 1:1</span></div>
                            <div class="column-row"><span class="column-name">payment_method</span><span class="column-type">ENUM</span></div>
                            <div class="column-row"><span class="column-name">payment_status</span><span class="column-type">ENUM</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 4: SQL CODE & QUERY HASIL -->
        <!-- ============================================== -->
        <div id="tab-sql" class="tab-content">
            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>💻 SQL Script (DDL &amp; DML)</h2>
                        <span class="panel-badge">database/schema.sql</span>
                    </div>
                    <button class="btn-copy" onclick="copyToClipboard('sql-code', this)">📋 Salin Skrip SQL</button>
                </div>

                <div class="code-box-wrapper">
                    <div class="code-toolbar">
                        <div class="file-label">📄 schema.sql (DDL + DML Dummy Data)</div>
                        <span style="color: #64748b; font-size: 0.75rem;">ANSI SQL / MySQL / MariaDB</span>
                    </div>
                    <pre><code id="sql-code">{{ $sqlContent }}</code></pre>
                </div>
            </div>

            <div class="glass-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <h2>🔍 Hasil Query Rincian Item Keranjang (Order Items Join)</h2>
                        <span class="panel-badge">SELECT JOIN Result</span>
                    </div>
                </div>

                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Order Code</th>
                            <th>Kategori</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                        <tr>
                            <td><strong style="color: #38bdf8;">{{ $item->order_code }}</strong></td>
                            <td><span style="color: #a855f7;">{{ $item->category_name }}</span></td>
                            <td>{{ $item->product_name }}</td>
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
    </script>
</body>
</html>
