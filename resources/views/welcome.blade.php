<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="description" content="StockCashier - Platform kasir dan inventaris modern. Kelola toko Anda dengan lebih cepat, rapi, dan menyenangkan.">

    <title>{{ config('app.name', 'StockCashier') }} | Solusi Kasir & Inventaris Modern</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <style>
        /* Reset & Variables */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --secondary-400: #22d3ee;
            --secondary-500: #06b6d4;
            --accent-500: #6366f1;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --white: #ffffff;
            
            --radius-sm: 0.75rem;
            --radius-md: 1rem;
            --radius-lg: 1.5rem;
            --radius-xl: 2rem;
            --radius-2xl: 3rem;
            
            --shadow-sm: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            --shadow-glow: 0 0 15px rgba(14, 165, 233, 0.35);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, var(--white) 0%, var(--gray-50) 100%);
            color: var(--gray-800);
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary-300);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-500);
        }

        /* Typography */
        h1, h2, h3, .gradient-text {
            letter-spacing: -0.02em;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-500), var(--accent-500));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Layout */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
        
        /* Glassmorphism Base */
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-xl);
        }
        
        /* Navigation */
        .nav {
            position: fixed;
            top: 1.5rem;
            left: 0;
            right: 0;
            z-index: 50;
            padding: 0 1.5rem;
        }
        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid var(--primary-100);
            border-radius: 9999px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        .nav-container:hover {
            box-shadow: var(--shadow-md);
            background: rgba(255, 255, 255, 0.95);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--gray-900);
            text-decoration: none;
        }
        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
            border-radius: var(--radius-sm);
            color: white;
        }
        .nav-links {
            display: flex;
            gap: 0.5rem;
        }
        .nav-link {
            padding: 0.5rem 1rem;
            font-weight: 600;
            color: var(--gray-600);
            text-decoration: none;
            border-radius: 9999px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .nav-link:hover {
            background: var(--primary-50);
            color: var(--primary-700);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.34, 1.5, 0.64, 1);
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-glow);
        }
        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }
        .btn-secondary:hover {
            background: var(--gray-50);
            transform: translateY(-2px);
        }
        .btn-outline {
            background: transparent;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }
        .btn-outline:hover {
            background: var(--gray-100);
            border-color: var(--primary-300);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8rem 0 6rem;
            position: relative;
            overflow: hidden;
        }
        .hero-bg-blur {
            position: absolute;
            width: 50rem;
            height: 50rem;
            background: radial-gradient(circle, rgba(14,165,233,0.2) 0%, rgba(34,211,238,0) 70%);
            border-radius: 50%;
            filter: blur(60px);
            top: -20%;
            left: -20%;
            z-index: 0;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 1rem 0.25rem 0.5rem;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--primary-700);
            margin-bottom: 1.5rem;
        }
        .pulse-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--primary-500);
            box-shadow: 0 0 0 8px rgba(14, 165, 233, 0.12);
            animation: pulse 1.8s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 7px rgba(14, 165, 233, 0.12); }
            50% { transform: scale(0.88); box-shadow: 0 0 0 13px rgba(14, 165, 233, 0.05); }
        }
        .hero-title {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }
        .hero-copy {
            font-size: 1.125rem;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 2rem;
            max-width: 90%;
        }
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
        }
        .stat {
            text-align: left;
        }
        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--gray-900);
        }
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        /* Dashboard Mockup */
        .dashboard-mock {
            position: relative;
            perspective: 1000px;
        }
        .mock-container {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-100);
            overflow: hidden;
            transform: rotateY(5deg) rotateX(5deg);
            transition: transform 0.5s ease;
        }
        .mock-container:hover {
            transform: rotateY(0deg) rotateX(0deg);
        }
        .mock-header {
            background: var(--gray-50);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }
        .mock-dots {
            display: flex;
            gap: 0.5rem;
        }
        .mock-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--gray-300);
        }
        .mock-url {
            background: white;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
        }
        .mock-sidebar {
            width: 16rem;
            background: var(--gray-50);
            padding: 1.5rem;
            border-right: 1px solid var(--gray-200);
        }
        .mock-content {
            padding: 1.5rem;
        }
        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: var(--radius-md);
            padding: 1rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--primary-100);
            z-index: 10;
        }
        .card-1 { top: -1rem; right: -2rem; animation: float 5s ease-in-out infinite; }
        .card-2 { bottom: 0; left: -2rem; animation: float 6s ease-in-out infinite 1s; }
        .card-3 { bottom: 4rem; right: -1rem; animation: float 7s ease-in-out infinite 2s; }

        /* Features */
        .section {
            padding: 6rem 0;
        }
        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            text-align: center;
        }
        .section-subtitle {
            text-align: center;
            color: var(--gray-600);
            max-width: 48rem;
            margin: 0 auto 4rem;
            font-size: 1.125rem;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .feature-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid var(--gray-100);
            box-shadow: var(--shadow-sm);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary-200);
            box-shadow: var(--shadow-md);
        }
        .feature-icon {
            width: 3rem;
            height: 3rem;
            background: var(--primary-50);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary-600);
            font-weight: 800;
            font-size: 1.5rem;
        }
        .feature-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
        }
        .feature-card p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* Trust Section */
        .trust-bar {
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0;
            overflow: hidden;
        }
        .trust-track {
            display: flex;
            gap: 2rem;
            width: max-content;
            animation: marquee 20s linear infinite;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--gray-500);
            white-space: nowrap;
        }

        /* Story Section */
        .story {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 48px;
            align-items: start;
        }
        .story-sticky {
            position: sticky;
            top: 116px;
        }
        .phone-mock {
            max-width: 410px;
            margin-inline: auto;
            padding: 14px;
            border: 1px solid var(--primary-100);
            border-radius: 44px;
            background: rgba(255, 255, 255, 0.76);
            box-shadow: 0 34px 100px rgba(14, 165, 233, 0.18);
            backdrop-filter: blur(18px);
        }
        .phone-screen {
            overflow: hidden;
            min-height: 660px;
            border-radius: 34px;
            background: linear-gradient(180deg, #f8fdff, #eef9ff);
        }
        .phone-top {
            padding: 18px;
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-400));
            color: white;
        }
        .phone-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 16px;
        }
        .phone-tile {
            min-height: 118px;
            border-radius: 24px;
            background: white;
            padding: 16px;
            box-shadow: 0 12px 26px rgba(14, 165, 233, 0.08);
        }
        .story-steps {
            display: grid;
            gap: 24px;
        }
        .story-step {
            min-height: 360px;
            padding: 34px;
            border: 1px solid rgba(186, 230, 253, 0.82);
            border-radius: 36px;
            background: rgba(255, 255, 255, 0.74);
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(18px);
        }
        .step-number {
            color: var(--primary-600);
            font-size: 13px;
            font-weight: 950;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }
        .story-step h3 {
            margin: 16px 0 0;
            font-size: clamp(28px, 4vw, 44px);
            letter-spacing: -0.055em;
            line-height: 1.04;
        }
        .story-step p {
            margin: 16px 0 0;
            color: var(--gray-600);
            font-size: 17px;
            line-height: 1.8;
            font-weight: 500;
        }

        /* Roles Section */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 44px;
        }
        .role-card {
            position: relative;
            padding: 24px;
            border: 1px solid rgba(186, 230, 253, 0.86);
            border-radius: 32px;
            background: white;
            box-shadow: var(--shadow-md);
        }
        .role-card::after {
            content: "";
            position: absolute;
            inset: auto 24px 0 24px;
            height: 4px;
            border-radius: 999px 999px 0 0;
            background: linear-gradient(90deg, var(--primary-500), var(--secondary-400));
        }
        .role-avatar {
            display: grid;
            place-items: center;
            width: 58px;
            height: 58px;
            border-radius: 24px;
            color: white;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            font-weight: 950;
            font-size: 1.25rem;
        }
        .role-card h3 {
            margin: 18px 0 0;
            font-size: 19px;
        }
        .role-card p {
            margin: 10px 0 0;
            color: var(--gray-600);
            line-height: 1.7;
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--gray-900), var(--primary-700));
            border-radius: var(--radius-2xl);
            margin: 0 auto;
            padding: 4rem;
            text-align: center;
            color: white;
        }
        .cta-title {
            font-size: clamp(2rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .cta-section p {
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--gray-200);
            padding: 3rem 0;
            margin-top: 4rem;
            background: var(--white);
        }
        .footer-inner {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: all 0.6s ease-out;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 1040px) {
            .nav-links { display: none; }
            .hero-grid, .story { grid-template-columns: 1fr; }
            .hero-visual { min-height: 560px; }
            .dashboard-stage { max-width: 720px; margin-inline: auto; }
            .feature-grid, .roles-grid { grid-template-columns: repeat(2, 1fr); }
            .story-sticky { position: relative; top: auto; }
        }
        @media (max-width: 720px) {
            .nav { top: 10px; padding: 0 1rem; }
            .nav-container { border-radius: 22px; }
            .brand span:last-child { display: none; }
            .btn { min-height: 40px; padding: 0 13px; font-size: 13px; }
            .hero { padding-top: 114px; }
            .hero-grid { gap: 34px; }
            .hero-stats { grid-template-columns: 1fr; gap: 1rem; flex-direction: column; }
            .hero-visual { min-height: auto; }
            .mock-sidebar { display: none; }
            .floating-card { display: none; }
            .feature-grid, .roles-grid { grid-template-columns: 1fr; }
            .section { padding: 72px 0; }
            .story-step { min-height: auto; padding: 24px; }
            .phone-screen { min-height: 560px; }
            .cta-section { padding: 2rem; }
            .footer-inner { flex-direction: column; text-align: center; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
            .reveal { opacity: 1; transform: none; }
            html { scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>

    <nav class="nav">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="brand">
                <div class="brand-mark">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M4 7h16l-1.5 13h-13L4 7Z" />
                        <path d="M8 7a4 4 0 0 1 8 0" />
                    </svg>
                </div>
                <span>StockCashier</span>
            </a>
            <div class="nav-links">
                <a class="nav-link" href="#features">Features</a>
                <a class="nav-link" href="#workflow">Workflow</a>
                <a class="nav-link" href="#roles">Roles</a>
            </div>
            <div class="nav-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-bg-blur animate-pulse-slow"></div>
            <div class="container hero-grid">
                <div>
                    <div class="eyebrow reveal">
                        <span class="pulse-dot"></span> Inventory, POS, reports — in one beautiful system
                    </div>
                    <h1 class="hero-title reveal">
                        Retail control that feels <span class="gradient-text">alive.</span>
                    </h1>
                    <p class="hero-copy reveal">
                        StockCashier membantu toko mengelola stok, pembelian, kasir, refund, audit log, dan laporan bisnis dalam satu dashboard modern yang cepat dan mudah dipakai.
                    </p>
                    <div class="hero-actions reveal" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Open Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Start Managing</a>
                            @endauth
                        @endif
                        <a href="#features" class="btn btn-outline">Explore Features</a>
                    </div>
                    <div class="hero-stats reveal">
                        <div class="stat">
                            <div class="stat-value"><span data-count="4">0</span> Roles</div>
                            <div class="stat-label">Admin, Owner, Cashier, Warehouse</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value"><span data-count="7">0</span>+ Modules</div>
                            <div class="stat-label">POS, stock, purchase, reports</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value"><span data-count="100">0</span>% Trackable</div>
                            <div class="stat-label">Audit logs for key actions</div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-mock reveal">
                    <div class="mock-container">
                        <div class="mock-header">
                            <div class="mock-dots">
                                <div class="mock-dot"></div>
                                <div class="mock-dot"></div>
                                <div class="mock-dot"></div>
                            </div>
                            <div class="mock-url">stockcashier.app/dashboard</div>
                        </div>
                        <div style="display: flex;">
                            <div class="mock-sidebar">
                                <div class="mock-logo" style="width: 42px; height: 42px; border-radius: 16px; background: linear-gradient(135deg, var(--primary-500), var(--secondary-400)); margin-bottom: 2rem;"></div>
                                <div style="display: grid; gap: 10px;">
                                    <div style="height: 34px; border-radius: 14px; background: rgba(14, 165, 233, 0.1);"></div>
                                    <div style="height: 34px; border-radius: 14px; background: linear-gradient(135deg, var(--primary-500), var(--secondary-400));"></div>
                                    <div style="height: 34px; border-radius: 14px; background: rgba(14, 165, 233, 0.1);"></div>
                                    <div style="height: 34px; border-radius: 14px; background: rgba(14, 165, 233, 0.1);"></div>
                                </div>
                            </div>
                            <div class="mock-content">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                                    <div style="width: 180px; height: 24px; border-radius: 999px; background: var(--gray-200);"></div>
                                    <div style="width: 112px; height: 40px; border-radius: 16px; background: var(--primary-500);"></div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 1.5rem;">
                                    <div style="min-height: 114px; padding: 16px; border: 1px solid var(--primary-100); border-radius: 24px; background: white;">
                                        <div style="width: 52%; height: 12px; border-radius: 999px; background: var(--gray-200);"></div>
                                        <div style="width: 80%; height: 26px; margin-top: 16px; border-radius: 999px; background: linear-gradient(90deg, var(--primary-200), var(--secondary-400));"></div>
                                    </div>
                                    <div style="min-height: 114px; padding: 16px; border: 1px solid var(--primary-100); border-radius: 24px; background: white;">
                                        <div style="width: 52%; height: 12px; border-radius: 999px; background: var(--gray-200);"></div>
                                        <div style="width: 80%; height: 26px; margin-top: 16px; border-radius: 999px; background: linear-gradient(90deg, var(--primary-200), var(--secondary-400));"></div>
                                    </div>
                                    <div style="min-height: 114px; padding: 16px; border: 1px solid var(--primary-100); border-radius: 24px; background: white;">
                                        <div style="width: 52%; height: 12px; border-radius: 999px; background: var(--gray-200);"></div>
                                        <div style="width: 80%; height: 26px; margin-top: 16px; border-radius: 999px; background: linear-gradient(90deg, var(--primary-200), var(--secondary-400));"></div>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 16px;">
                                    <div style="border: 1px solid var(--primary-100); border-radius: 26px; background: white; padding: 18px;">
                                        <div style="display: flex; align-items: end; gap: 10px; height: 178px; padding-top: 24px;">
                                            <i style="flex: 1; border-radius: 999px 999px 10px 10px; background: linear-gradient(180deg, var(--primary-400), var(--primary-600)); height: 36%;"></i>
                                            <i style="flex: 1; border-radius: 999px 999px 10px 10px; background: linear-gradient(180deg, var(--primary-400), var(--primary-600)); height: 72%;"></i>
                                            <i style="flex: 1; border-radius: 999px 999px 10px 10px; background: linear-gradient(180deg, var(--primary-400), var(--primary-600)); height: 48%;"></i>
                                            <i style="flex: 1; border-radius: 999px 999px 10px 10px; background: linear-gradient(180deg, var(--primary-400), var(--primary-600)); height: 84%;"></i>
                                            <i style="flex: 1; border-radius: 999px 999px 10px 10px; background: linear-gradient(180deg, var(--primary-400), var(--primary-600)); height: 58%;"></i>
                                            <i style="flex: 1; border-radius: 999px 999px 10px 10px; background: linear-gradient(180deg, var(--primary-400), var(--primary-600)); height: 94%;"></i>
                                        </div>
                                    </div>
                                    <div style="border: 1px solid var(--primary-100); border-radius: 26px; background: white; padding: 18px;">
                                        <div style="display: grid; gap: 12px; margin-top: 10px;">
                                            <div style="display: grid; grid-template-columns: 36px 1fr 54px; gap: 10px; align-items: center;">
                                                <div style="width: 36px; height: 36px; border-radius: 12px; background: var(--primary-100);"></div>
                                                <div style="height: 10px; border-radius: 999px; background: var(--gray-200);"></div>
                                                <div style="height: 12px; border-radius: 999px; background: var(--primary-200);"></div>
                                            </div>
                                            <div style="display: grid; grid-template-columns: 36px 1fr 54px; gap: 10px; align-items: center;">
                                                <div style="width: 36px; height: 36px; border-radius: 12px; background: var(--primary-100);"></div>
                                                <div style="height: 10px; border-radius: 999px; background: var(--gray-200);"></div>
                                                <div style="height: 12px; border-radius: 999px; background: var(--primary-200);"></div>
                                            </div>
                                            <div style="display: grid; grid-template-columns: 36px 1fr 54px; gap: 10px; align-items: center;">
                                                <div style="width: 36px; height: 36px; border-radius: 12px; background: var(--primary-100);"></div>
                                                <div style="height: 10px; border-radius: 999px; background: var(--gray-200);"></div>
                                                <div style="height: 12px; border-radius: 999px; background: var(--primary-200);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Floating Cards -->
                    <div class="floating-card card-1">
                        <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 800;">Today Sales</div>
                        <div style="font-size: 1.5rem; font-weight: 950; letter-spacing: -0.05em;">Rp 8.4M</div>
                        <div style="width: 100%; height: 9px; background: var(--primary-100); border-radius: 999px; margin-top: 12px;"><div style="width: 76%; height: 100%; background: linear-gradient(90deg, var(--primary-500), var(--secondary-400)); border-radius: 999px;"></div></div>
                    </div>
                    <div class="floating-card card-2">
                        <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 800;">Low Stock Alert</div>
                        <div style="font-size: 1.5rem; font-weight: 950; letter-spacing: -0.05em;">12 Items</div>
                        <div style="width: 100%; height: 9px; background: var(--primary-100); border-radius: 999px; margin-top: 12px;"><div style="width: 44%; height: 100%; background: linear-gradient(90deg, var(--primary-500), var(--secondary-400)); border-radius: 999px;"></div></div>
                    </div>
                    <div class="floating-card card-3">
                        <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 800;">Gross Profit</div>
                        <div style="font-size: 1.5rem; font-weight: 950; letter-spacing: -0.05em;">+28.7%</div>
                        <div style="width: 100%; height: 9px; background: var(--primary-100); border-radius: 999px; margin-top: 12px;"><div style="width: 86%; height: 100%; background: linear-gradient(90deg, var(--primary-500), var(--secondary-400)); border-radius: 999px;"></div></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Bar -->
        <div class="trust-bar">
            <div class="trust-track">
                @for ($i = 0; $i < 2; $i++)
                    <span class="trust-item">✅ Barcode POS</span>
                    <span class="trust-item">📦 Multi Warehouse</span>
                    <span class="trust-item">🔄 Refund Tracking</span>
                    <span class="trust-item">📊 Activity Logs</span>
                    <span class="trust-item">📈 Owner Reports</span>
                    <span class="trust-item">📦 Stock Adjustment</span>
                    <span class="trust-item">🧾 Thermal Receipt</span>
                @endfor
            </div>
        </div>

        <!-- Features Section -->
        <section id="features" class="section">
            <div class="container">
                <div class="center reveal" style="text-align: center;">
                    <h2 class="section-title">A complete retail command center.</h2>
                    <p class="section-subtitle">
                        Dari barang masuk hingga struk kasir, semuanya dibuat terhubung. Setiap aksi penting bisa dilacak, setiap laporan bisa dibaca cepat.
                    </p>
                </div>
                <div class="feature-grid">
                    <div class="feature-card reveal">
                        <div class="feature-icon">POS</div>
                        <div class="feature-title">Lightning POS</div>
                        <p>Scan barcode, cari produk, hitung pembayaran, dan cetak struk dengan flow kasir yang cepat.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">STK</div>
                        <div class="feature-title">Inventory Control</div>
                        <p>Kelola stok per warehouse, pantau movement, dan cegah stok minus dengan validasi backend.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">REP</div>
                        <div class="feature-title">Owner Reports</div>
                        <p>Laporan sales, profit, stock, dan purchase dirancang untuk membantu owner mengambil keputusan.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">LOG</div>
                        <div class="feature-title">Audit Trail</div>
                        <p>Login, transaksi, adjustment, refund, void, dan backup tercatat dalam activity log.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Workflow Section -->
        <section id="workflow" class="section">
            <div class="container story">
                <div class="story-sticky reveal">
                    <div class="phone-mock">
                        <div class="phone-screen">
                            <div class="phone-top">
                                <div style="font-size:13px;font-weight:800;opacity:.82">Live Store Overview</div>
                                <div style="margin-top:10px;font-size:34px;font-weight:950;letter-spacing:-.06em">Rp 12.8M</div>
                                <div style="margin-top:6px;font-size:13px;opacity:.82">Net sales today</div>
                            </div>
                            <div class="phone-grid">
                                <div class="phone-tile"><span style="display:block;height:10px;border-radius:999px;background:var(--gray-200);"></span><b style="display:block;height:22px;width:70%;margin-top:14px;border-radius:999px;background:var(--primary-200);"></b></div>
                                <div class="phone-tile"><span style="display:block;height:10px;border-radius:999px;background:var(--gray-200);"></span><b style="display:block;height:22px;width:70%;margin-top:14px;border-radius:999px;background:var(--primary-200);"></b></div>
                                <div class="phone-tile"><span style="display:block;height:10px;border-radius:999px;background:var(--gray-200);"></span><b style="display:block;height:22px;width:70%;margin-top:14px;border-radius:999px;background:var(--primary-200);"></b></div>
                                <div class="phone-tile"><span style="display:block;height:10px;border-radius:999px;background:var(--gray-200);"></span><b style="display:block;height:22px;width:70%;margin-top:14px;border-radius:999px;background:var(--primary-200);"></b></div>
                                <div class="phone-tile" style="grid-column:span 2;min-height:160px"><span style="display:block;height:10px;border-radius:999px;background:var(--gray-200);"></span><b style="display:block;height:22px;width:88%;margin-top:14px;border-radius:999px;background:var(--primary-200);"></b><b style="display:block;height:22px;width:60%;margin-top:14px;border-radius:999px;background:var(--primary-200);"></b></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="story-steps">
                    <div class="story-step reveal">
                        <div class="step-number">Step 01</div>
                        <h3>Input product and supplier data once.</h3>
                        <p>Buat master product lengkap dengan SKU, barcode, harga modal, harga jual, supplier, category, unit, dan alert level.</p>
                    </div>
                    <div class="story-step reveal">
                        <div class="step-number">Step 02</div>
                        <h3>Move stock with confidence.</h3>
                        <p>Purchase dan stock adjustment otomatis menghasilkan stock movement sehingga alur barang masuk/keluar lebih transparan.</p>
                    </div>
                    <div class="story-step reveal">
                        <div class="step-number">Step 03</div>
                        <h3>Sell faster with barcode POS.</h3>
                        <p>Kasir cukup scan barcode atau SKU, pilih metode pembayaran, lalu StockCashier menjaga validasi stok dan invoice.</p>
                    </div>
                    <div class="story-step reveal">
                        <div class="step-number">Step 04</div>
                        <h3>Review reports without guessing.</h3>
                        <p>Owner mendapat laporan sales, profit, stock, dan purchase yang mudah dibaca untuk memantau performa toko.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Roles Section -->
        <section id="roles" class="section">
            <div class="container">
                <div class="center reveal" style="text-align: center;">
                    <h2 class="section-title">Built for every role in your store.</h2>
                    <p class="section-subtitle">Setiap role punya area kerja yang jelas, aman, dan tidak saling mengganggu.</p>
                </div>
                <div class="roles-grid">
                    <div class="role-card reveal"><div class="role-avatar">AD</div><h3>Admin</h3><p>Kelola users, master data, settings, backup, dan audit log.</p></div>
                    <div class="role-card reveal"><div class="role-avatar">OW</div><h3>Owner</h3><p>Pantau dashboard dan laporan untuk keputusan bisnis.</p></div>
                    <div class="role-card reveal"><div class="role-avatar">CS</div><h3>Cashier</h3><p>Gunakan POS, sales history, receipt, void, dan refund.</p></div>
                    <div class="role-card reveal"><div class="role-avatar">WH</div><h3>Warehouse</h3><p>Kelola stok, stock movement, purchase, dan adjustment.</p></div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section">
            <div class="container">
                <div class="cta-section reveal">
                    <h2 class="cta-title">Ready to run your store smarter?</h2>
                    <p>Masuk ke dashboard StockCashier dan rasakan pengalaman mengelola toko yang lebih cepat, rapi, dan menyenangkan.</p>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary" style="background: white; color: var(--primary-600);">Open Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary" style="background: white; color: var(--primary-600);">Login Now</a>
                            @endauth
                        @endif
                        <a href="#features" class="btn btn-outline" style="background: transparent; border-color: rgba(255,255,255,0.3); color: white;">See Features</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <div>© {{ date('Y') }} StockCashier. Built for modern retail.</div>
            <div>Inventory · POS · Reports · Audit</div>
        </div>
    </footer>

    <script>
        (function() {
            // ========== SMOOTH SCROLL FOR NAVIGATION LINKS (Setiap klik) ==========
            // Menangani semua link yang mengarah ke anchor di halaman yang sama
            const allHashLinks = document.querySelectorAll('a[href^="#"]');
            
            allHashLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    
                    // Skip jika hanya "#" atau kosong
                    if (targetId === '#' || targetId === '') return;
                    
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        e.preventDefault();
                        
                        const navHeight = 80; // Tinggi navbar + padding
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Update URL tanpa reload (opsional)
                        history.pushState(null, null, targetId);
                    }
                });
            });
            
            // ========== REVEAL ANIMATION ON SCROLL ==========
            const revealElements = document.querySelectorAll('.reveal');
            
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
            
            revealElements.forEach(el => revealObserver.observe(el));
            
            // ========== COUNTER ANIMATION ==========
            const counters = document.querySelectorAll('[data-count]');
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    const element = entry.target;
                    const target = Number(element.dataset.count || 0);
                    let current = 0;
                    const duration = 1100;
                    const step = target / (duration / 16);
                    
                    function updateCounter() {
                        current += step;
                        if (current < target) {
                            element.textContent = Math.floor(current);
                            requestAnimationFrame(updateCounter);
                        } else {
                            element.textContent = target;
                        }
                    }
                    
                    updateCounter();
                    counterObserver.unobserve(element);
                });
            }, { threshold: 0.5 });
            counters.forEach(counter => counterObserver.observe(counter));
            
            // ========== PARALLAX EFFECT FOR DASHBOARD MOCK ==========
            const mockContainer = document.querySelector('.mock-container');
            const dashboardMock = document.querySelector('.dashboard-mock');
            
            if (mockContainer && dashboardMock) {
                dashboardMock.addEventListener('mousemove', (e) => {
                    const rect = dashboardMock.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width - 0.5) * 10;
                    const y = ((e.clientY - rect.top) / rect.height - 0.5) * 10;
                    mockContainer.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
                });
                
                dashboardMock.addEventListener('mouseleave', () => {
                    mockContainer.style.transform = 'rotateY(5deg) rotateX(5deg)';
                });
            }
            
            // ========== MAGNETIC BUTTONS ==========
            const magneticBtns = document.querySelectorAll('.btn');
            magneticBtns.forEach(btn => {
                btn.addEventListener('mousemove', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    this.style.transform = `translate(${x * 0.1}px, ${y * 0.2}px)`;
                });
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
            
            // ========== FLOATING CARDS ANIMATION (untuk hero section) ==========
            const floatingCards = document.querySelectorAll('.floating-card');
            const heroSection = document.querySelector('.hero');
            
            if (heroSection && floatingCards.length > 0) {
                heroSection.addEventListener('mousemove', (e) => {
                    const rect = heroSection.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5;
                    const y = (e.clientY - rect.top) / rect.height - 0.5;
                    
                    floatingCards.forEach((card, index) => {
                        const speedX = 20 * (index + 1);
                        const speedY = 15 * (index + 1);
                        card.style.transform = `translate(${x * speedX}px, ${y * speedY}px)`;
                    });
                });
                
                heroSection.addEventListener('mouseleave', () => {
                    floatingCards.forEach(card => {
                        card.style.transform = '';
                    });
                });
            }
        })();
    </script>
</body>
</html>