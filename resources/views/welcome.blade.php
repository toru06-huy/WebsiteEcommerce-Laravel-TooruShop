<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VELOUR — Thời Trang Cao Cấp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:      #0e0c0a;
            --sand:     #f5f0e8;
            --cream:    #faf8f4;
            --gold:     #b8955a;
            --gold-lt:  #d4b07a;
            --muted:    #8a8278;
            --border:   #e0d9ce;
            --white:    #ffffff;
            --nav-h:    44px;
            --top-h:    36px;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--ink);
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        img { display: block; width: 100%; }

        /* ═══════════════════════════════════════
           TOP BAR
        ═══════════════════════════════════════ */
        .top-bar {
            background: var(--ink);
            height: var(--top-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .top-bar-left a {
            font-size: 11.5px;
            color: rgba(245,240,232,.65);
            letter-spacing: .04em;
            transition: color .2s;
        }
        .top-bar-left a:hover { color: var(--gold-lt); }

        .top-bar-center {
            font-size: 11.5px;
            color: var(--gold-lt);
            letter-spacing: .1em;
            font-weight: 500;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .top-bar-right a {
            font-size: 11.5px;
            color: rgba(245,240,232,.65);
            letter-spacing: .04em;
            transition: color .2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .top-bar-right a:hover { color: var(--gold-lt); }

        .top-bar-right svg { flex-shrink: 0; }

        /* ═══════════════════════════════════════
           HEADER (STICKY BELOW TOP BAR)
        ═══════════════════════════════════════ */
        .header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: var(--top-h);
            z-index: 999;
        }

        /* ── Main header row ── */
        .header-main {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            padding: 0 32px;
            height: 70px;
        }

        /* Logo */
        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            justify-self: center;
        }

        .logo-icon {
            width: 32px; height: 32px;
            border: 1.5px solid var(--gold);
            display: grid; place-items: center;
            transform: rotate(45deg);
        }
        .logo-icon span {
            display: block;
            width: 12px; height: 12px;
            background: var(--gold);
            transform: rotate(-45deg) scale(.7);
        }

        .logo-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px;
            font-weight: 300;
            letter-spacing: .45em;
            text-transform: uppercase;
            color: var(--ink);
            line-height: 1;
        }

        .logo-sub {
            font-size: 9px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 400;
        }

        /* Header right icons */
        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
            justify-self: end;
        }

        .header-icon-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            cursor: pointer;
            color: var(--ink);
            background: none;
            border: none;
            transition: color .2s;
            position: relative;
        }
        .header-icon-btn:hover { color: var(--gold); }

        .header-icon-btn span {
            font-size: 10px;
            letter-spacing: .05em;
            color: var(--muted);
        }

        .cart-badge {
            position: absolute;
            top: -4px; right: -6px;
            background: var(--gold);
            color: #fff;
            font-size: 9px;
            font-weight: 600;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: grid; place-items: center;
        }

        /* Search bar in header left */
        .header-search {
            display: flex;
            align-items: center;
            justify-self: start;
        }

        .search-input-wrap {
            display: flex;
            align-items: center;
            border: 1px solid var(--border);
            border-radius: 2px;
            overflow: hidden;
            width: 220px;
        }

        .search-input-wrap input {
            padding: 8px 12px;
            border: none;
            outline: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--ink);
            background: transparent;
            width: 100%;
        }

        .search-input-wrap input::placeholder { color: #c0b9b0; }

        .search-input-wrap button {
            background: none;
            border: none;
            border-left: 1px solid var(--border);
            padding: 8px 10px;
            cursor: pointer;
            color: var(--muted);
            display: flex;
        }
        .search-input-wrap button:hover { color: var(--gold); }

        /* ── NAV ROW ── */
        .nav-row {
            border-top: 1px solid var(--border);
            display: flex;
            height: var(--nav-h);
            position: relative;
        }

        /* LEFT NAV — categories with dropdowns */
        .nav-left {
            display: flex;
            align-items: stretch;
            flex: 1;
            justify-content: flex-start;
            padding-left: 32px;
        }

        .nav-item {
            position: relative;
            display: flex;
            align-items: center;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 16px;
            height: 100%;
            font-size: 12.5px;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ink);
            white-space: nowrap;
            transition: color .2s;
            cursor: pointer;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 16px; right: 16px;
            height: 2px;
            background: var(--gold);
            transform: scaleX(0);
            transition: transform .25s ease;
        }

        .nav-item:hover > .nav-link { color: var(--gold); }
        .nav-item:hover > .nav-link::after { transform: scaleX(1); }

        .nav-link svg { transition: transform .2s; }
        .nav-item:hover > .nav-link svg { transform: rotate(180deg); }

        /* DROPDOWN */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--white);
            border: 1px solid var(--border);
            border-top: 2px solid var(--gold);
            min-width: 220px;
            box-shadow: 0 8px 32px rgba(14,12,10,.12);
            opacity: 0;
            pointer-events: none;
            transform: translateY(8px);
            transition: opacity .2s, transform .2s;
            z-index: 200;
        }

        .nav-item:hover > .dropdown {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 12px 20px 8px;
            font-size: 10px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        .dropdown-item {
            position: relative;
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            font-size: 13px;
            color: var(--ink);
            transition: background .15s, color .15s, padding-left .15s;
        }

        .dropdown-link:hover {
            background: var(--sand);
            color: var(--gold);
            padding-left: 24px;
        }

        /* Sub-dropdown (level 3) */
        .sub-dropdown {
            position: absolute;
            left: 100%;
            top: 0;
            background: var(--white);
            border: 1px solid var(--border);
            border-top: 2px solid var(--gold);
            min-width: 200px;
            box-shadow: 0 8px 32px rgba(14,12,10,.12);
            opacity: 0;
            pointer-events: none;
            transform: translateX(8px);
            transition: opacity .2s, transform .2s;
            z-index: 201;
        }

        .dropdown-item:hover > .sub-dropdown {
            opacity: 1;
            pointer-events: all;
            transform: translateX(0);
        }

        .sub-dropdown-link {
            display: block;
            padding: 9px 20px;
            font-size: 12.5px;
            color: var(--ink);
            transition: background .15s, color .15s, padding-left .15s;
        }

        .sub-dropdown-link:hover {
            background: var(--sand);
            color: var(--gold);
            padding-left: 26px;
        }

        /* RIGHT NAV — utility links (stay fixed) */
        .nav-right {
            display: flex;
            align-items: stretch;
            padding-right: 32px;
        }

        .nav-right .nav-link {
            font-size: 12px;
            color: var(--muted);
        }

        .nav-right .nav-item:hover > .nav-link { color: var(--gold); }

        /* ═══════════════════════════════════════
           HERO BANNER
        ═══════════════════════════════════════ */
        .hero {
            position: relative;
            height: calc(100vh - var(--top-h) - 70px - var(--nav-h));
            min-height: 520px;
            max-height: 780px;
            overflow: hidden;
        }

        .hero-slides {
            display: flex;
            height: 100%;
            transition: transform .8s cubic-bezier(.25,.46,.45,.94);
        }

        .hero-slide {
            min-width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .hero-slide-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 8s ease;
        }

        .hero-slide:hover .hero-slide-bg { transform: scale(1.04); }

        .slide-1-bg {
            background:
                linear-gradient(120deg, rgba(14,12,10,.65) 0%, rgba(14,12,10,.2) 60%),
                linear-gradient(160deg, #1c1206 0%, #3d2a10 40%, #8a6a3a 100%);
        }

        .slide-2-bg {
            background:
                linear-gradient(120deg, rgba(14,12,10,.3) 0%, rgba(14,12,10,.55) 100%),
                linear-gradient(200deg, #2a1f14 0%, #5c3d1e 50%, #c8a060 100%);
        }

        .slide-3-bg {
            background:
                linear-gradient(80deg, rgba(14,12,10,.6) 0%, rgba(14,12,10,.1) 70%),
                linear-gradient(140deg, #0a0806 0%, #241a0e 50%, #6b4f2a 100%);
        }

        /* Fabric texture overlay */
        .hero-slide-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            opacity: .3;
        }

        /* Decorative grid lines */
        .hero-slide-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(90deg, transparent, transparent 119px, rgba(184,149,90,.04) 120px),
                repeating-linear-gradient(0deg,  transparent, transparent 119px, rgba(184,149,90,.04) 120px);
        }

        .hero-content {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            padding: 0 80px;
        }

        .hero-text {
            max-width: 560px;
        }

        .hero-eyebrow {
            font-size: 11px;
            letter-spacing: .3em;
            text-transform: uppercase;
            color: var(--gold-lt);
            font-weight: 500;
            margin-bottom: 18px;
            opacity: 0;
            animation: heroFadeUp .7s .2s ease both;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(52px, 6vw, 88px);
            font-weight: 300;
            line-height: 1.02;
            color: var(--sand);
            margin-bottom: 22px;
            opacity: 0;
            animation: heroFadeUp .7s .35s ease both;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold-lt);
        }

        .hero-desc {
            font-size: 15px;
            color: rgba(245,240,232,.6);
            line-height: 1.75;
            font-weight: 300;
            margin-bottom: 36px;
            max-width: 400px;
            opacity: 0;
            animation: heroFadeUp .7s .5s ease both;
        }

        .hero-cta {
            display: flex;
            gap: 16px;
            opacity: 0;
            animation: heroFadeUp .7s .65s ease both;
        }

        .btn-primary {
            padding: 14px 36px;
            background: var(--gold);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            transition: background .2s, transform .15s;
            border-radius: 1px;
        }
        .btn-primary:hover { background: var(--gold-lt); transform: translateY(-1px); }

        .btn-outline {
            padding: 14px 36px;
            background: transparent;
            color: var(--sand);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .15em;
            text-transform: uppercase;
            cursor: pointer;
            border: 1px solid rgba(245,240,232,.35);
            transition: border-color .2s, color .2s, transform .15s;
            border-radius: 1px;
        }
        .btn-outline:hover { border-color: var(--gold-lt); color: var(--gold-lt); transform: translateY(-1px); }

        /* Slide controls */
        .hero-dots {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .hero-dot {
            width: 28px; height: 2px;
            background: rgba(245,240,232,.3);
            cursor: pointer;
            transition: background .3s, width .3s;
            border: none;
        }

        .hero-dot.active {
            background: var(--gold);
            width: 48px;
        }

        .hero-arrows {
            position: absolute;
            bottom: 24px;
            right: 48px;
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .hero-arrow {
            width: 40px; height: 40px;
            border: 1px solid rgba(245,240,232,.25);
            background: rgba(14,12,10,.3);
            color: var(--sand);
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            backdrop-filter: blur(4px);
        }
        .hero-arrow:hover { border-color: var(--gold); background: rgba(184,149,90,.2); }

        /* Decorative corner */
        .hero-corner {
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .hero-corner-line {
            width: 1px;
            height: 80px;
            background: linear-gradient(to bottom, transparent, rgba(184,149,90,.5), transparent);
        }

        .hero-corner-tag {
            writing-mode: vertical-rl;
            font-size: 10px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: rgba(184,149,90,.6);
            font-weight: 500;
        }

        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════
           MARQUEE STRIP
        ═══════════════════════════════════════ */
        .marquee-strip {
            background: var(--ink);
            height: 40px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .marquee-track {
            display: flex;
            gap: 0;
            animation: marquee 30s linear infinite;
            white-space: nowrap;
        }

        .marquee-item {
            display: flex;
            align-items: center;
            gap: 32px;
            padding: 0 32px;
            font-size: 11px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: rgba(184,149,90,.7);
            font-weight: 500;
        }

        .marquee-dot {
            width: 3px; height: 3px;
            border-radius: 50%;
            background: var(--gold);
            opacity: .5;
        }

        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        /* ═══════════════════════════════════════
           SECTION COMMONS
        ═══════════════════════════════════════ */
        .section {
            padding: 72px 32px;
        }

        .section-alt {
            background: var(--cream);
        }

        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-eyebrow {
            font-size: 11px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 500;
            margin-bottom: 12px;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 38px;
            font-weight: 400;
            color: var(--ink);
            margin-bottom: 12px;
        }

        .section-title em { font-style: italic; color: var(--gold); }

        .section-desc {
            font-size: 14px;
            color: var(--muted);
            max-width: 420px;
            margin: 0 auto;
            line-height: 1.7;
            font-weight: 300;
        }

        /* ═══════════════════════════════════════
           CATEGORY GRID (Like IVY Moda top section)
        ═══════════════════════════════════════ */
        .cat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .cat-card {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            aspect-ratio: 3/4;
            border-radius: 2px;
        }

        .cat-card-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform .6s cubic-bezier(.25,.46,.45,.94);
        }

        .cat-card:hover .cat-card-bg { transform: scale(1.06); }

        .cat-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(14,12,10,.75) 0%, rgba(14,12,10,.1) 50%, transparent 100%);
            transition: background .3s;
        }

        .cat-card:hover .cat-card-overlay {
            background: linear-gradient(to top, rgba(14,12,10,.85) 0%, rgba(14,12,10,.2) 50%, rgba(14,12,10,.05) 100%);
        }

        .cat-card-body {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 24px 20px;
        }

        .cat-card-tag {
            font-size: 10px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--gold-lt);
            font-weight: 500;
            margin-bottom: 6px;
        }

        .cat-card-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px;
            font-weight: 400;
            color: var(--sand);
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .cat-card-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--gold-lt);
            font-weight: 500;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .3s, transform .3s;
        }

        .cat-card:hover .cat-card-cta { opacity: 1; transform: translateY(0); }

        /* Category color fills */
        .cat-bg-1 { background: linear-gradient(160deg, #1a1208 0%, #3d2810 40%, #7a5530 100%); }
        .cat-bg-2 { background: linear-gradient(160deg, #0f1318 0%, #1e2840 40%, #3d4f70 100%); }
        .cat-bg-3 { background: linear-gradient(160deg, #0e1008 0%, #1c2813 40%, #3a4f2a 100%); }
        .cat-bg-4 { background: linear-gradient(160deg, #180e0e 0%, #3a1818 40%, #6a2f2f 100%); }

        /* ═══════════════════════════════════════
           NEW ARRIVALS — PRODUCT GRID
        ═══════════════════════════════════════ */
        .product-tabs {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
        }

        .product-tab {
            padding: 10px 28px;
            font-size: 12px;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 500;
            color: var(--muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color .2s, border-color .2s;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
        }

        .product-tab.active, .product-tab:hover {
            color: var(--ink);
            border-bottom-color: var(--gold);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .product-card {
            cursor: pointer;
        }

        .product-img-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 3/4;
            border-radius: 2px;
            background: var(--sand);
            margin-bottom: 14px;
        }

        .product-img-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform .5s cubic-bezier(.25,.46,.45,.94);
        }

        .product-card:hover .product-img-bg { transform: scale(1.04); }

        .product-badge {
            position: absolute;
            top: 10px; left: 10px;
            background: var(--gold);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            padding: 3px 8px;
            text-transform: uppercase;
        }

        .product-badge.sale { background: #c0392b; }
        .product-badge.new { background: var(--ink); }

        .product-actions {
            position: absolute;
            bottom: 10px; right: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            opacity: 0;
            transform: translateX(6px);
            transition: opacity .25s, transform .25s;
        }

        .product-card:hover .product-actions { opacity: 1; transform: translateX(0); }

        .product-action-btn {
            width: 34px; height: 34px;
            background: var(--white);
            border: none;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: var(--ink);
            transition: background .2s, color .2s;
            box-shadow: 0 2px 8px rgba(0,0,0,.12);
        }
        .product-action-btn:hover { background: var(--gold); color: #fff; }

        .product-name {
            font-size: 13.5px;
            font-weight: 400;
            color: var(--ink);
            margin-bottom: 5px;
            line-height: 1.4;
            transition: color .2s;
        }

        .product-card:hover .product-name { color: var(--gold); }

        .product-price-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-price {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
        }

        .product-price-old {
            font-size: 13px;
            color: var(--muted);
            text-decoration: line-through;
        }

        .product-colors {
            display: flex;
            gap: 5px;
            margin-top: 8px;
        }

        .color-swatch {
            width: 14px; height: 14px;
            border-radius: 50%;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: transform .15s;
        }

        .color-swatch:hover { transform: scale(1.25); }

        /* Load more */
        .load-more-wrap {
            text-align: center;
            margin-top: 48px;
        }

        .btn-load-more {
            padding: 13px 48px;
            background: transparent;
            color: var(--ink);
            border: 1px solid var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .15em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
            border-radius: 1px;
        }
        .btn-load-more:hover { background: var(--ink); color: var(--sand); }

        /* ═══════════════════════════════════════
           LOOKBOOK / EDITORIAL BANNER
        ═══════════════════════════════════════ */
        .lookbook {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 520px;
        }

        .lookbook-left {
            background: linear-gradient(135deg, #1c1508 0%, #3d2a10 50%, #6b4f2a 100%);
            display: flex;
            align-items: center;
            padding: 80px;
            position: relative;
            overflow: hidden;
        }

        .lookbook-left::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 59px, rgba(184,149,90,.04) 60px);
        }

        .lookbook-deco-v {
            position: absolute;
            top: 50%; right: -20px;
            transform: translateY(-50%);
            font-family: 'Cormorant Garamond', serif;
            font-size: 220px;
            font-weight: 300;
            color: transparent;
            -webkit-text-stroke: 1px rgba(184,149,90,.1);
            pointer-events: none;
            user-select: none;
        }

        .lookbook-text { position: relative; z-index: 1; }

        .lookbook-text .section-eyebrow { margin-bottom: 16px; }

        .lookbook-text h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 52px;
            font-weight: 300;
            line-height: 1.05;
            color: var(--sand);
            margin-bottom: 20px;
        }

        .lookbook-text h2 em { font-style: italic; color: var(--gold-lt); }

        .lookbook-text p {
            font-size: 14px;
            color: rgba(245,240,232,.5);
            line-height: 1.75;
            max-width: 360px;
            font-weight: 300;
            margin-bottom: 32px;
        }

        .lookbook-right {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
        }

        .lookbook-img {
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .lookbook-img::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(14,12,10,.15);
            transition: background .3s;
        }
        .lookbook-img:hover::after { background: rgba(14,12,10,.05); }

        .lb-1 { background: linear-gradient(135deg, #2a1f0e, #5c3d1a, #8a6030); }
        .lb-2 { background: linear-gradient(135deg, #0f1318, #1e2840, #3d5070); }
        .lb-3 { background: linear-gradient(135deg, #180f0f, #3a1818, #6a3030); }
        .lb-4 { background: linear-gradient(135deg, #0e1008, #1c2813, #3a4f28); }

        /* ═══════════════════════════════════════
           FEATURES / USP STRIP
        ═══════════════════════════════════════ */
        .features {
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .feature {
            padding: 36px 32px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
            border-right: 1px solid var(--border);
            transition: background .2s;
        }
        .feature:last-child { border-right: none; }
        .feature:hover { background: var(--sand); }

        .feature-icon {
            width: 42px; height: 42px;
            border: 1px solid var(--gold);
            display: grid;
            place-items: center;
            color: var(--gold);
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .05em;
            color: var(--ink);
            margin-bottom: 5px;
        }

        .feature-text p {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.6;
            font-weight: 300;
        }

        /* ═══════════════════════════════════════
           TESTIMONIALS
        ═══════════════════════════════════════ */
        .testimonials {
            background: var(--ink);
        }

        .testimonials .section-eyebrow { color: var(--gold-lt); }

        .testimonials .section-title {
            color: var(--sand);
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .testimonial-card {
            background: rgba(245,240,232,.05);
            border: 1px solid rgba(184,149,90,.2);
            padding: 32px;
            transition: border-color .2s, background .2s;
        }
        .testimonial-card:hover {
            border-color: rgba(184,149,90,.5);
            background: rgba(245,240,232,.08);
        }

        .testimonial-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 18px;
        }

        .star { color: var(--gold); font-size: 14px; }

        .testimonial-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            font-style: italic;
            color: var(--sand);
            line-height: 1.55;
            margin-bottom: 22px;
            font-weight: 300;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-lt));
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            flex-shrink: 0;
        }

        .testimonial-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--sand);
        }

        .testimonial-since {
            font-size: 11.5px;
            color: var(--muted);
        }

        /* ═══════════════════════════════════════
           NEWSLETTER
        ═══════════════════════════════════════ */
        .newsletter {
            background: var(--sand);
            text-align: center;
            padding: 72px 32px;
        }

        .newsletter .section-title { margin-bottom: 10px; }

        .newsletter-desc {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 36px;
            font-weight: 300;
        }

        .newsletter-form {
            display: flex;
            justify-content: center;
            gap: 0;
            max-width: 480px;
            margin: 0 auto;
        }

        .newsletter-input {
            flex: 1;
            padding: 14px 20px;
            border: 1px solid var(--border);
            border-right: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--ink);
            background: var(--white);
            outline: none;
        }
        .newsletter-input:focus { border-color: var(--gold); }

        .newsletter-btn {
            padding: 14px 28px;
            background: var(--ink);
            color: var(--sand);
            border: 1px solid var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .2s;
        }
        .newsletter-btn:hover { background: var(--gold); border-color: var(--gold); }

        /* ═══════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════ */
        .footer {
            background: var(--ink);
            padding: 64px 32px 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 280px repeat(3, 1fr);
            gap: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid rgba(245,240,232,.08);
        }

        .footer-brand .logo {
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .footer-brand .logo-text { color: var(--sand); }
        .footer-brand .logo-sub { color: rgba(245,240,232,.3); }

        .footer-brand p {
            font-size: 13px;
            color: rgba(245,240,232,.4);
            line-height: 1.75;
            font-weight: 300;
            margin-bottom: 24px;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
        }

        .footer-social {
            width: 34px; height: 34px;
            border: 1px solid rgba(245,240,232,.15);
            display: grid;
            place-items: center;
            color: rgba(245,240,232,.5);
            cursor: pointer;
            transition: border-color .2s, color .2s, background .2s;
        }
        .footer-social:hover { border-color: var(--gold); color: var(--gold); background: rgba(184,149,90,.1); }

        .footer-col h5 {
            font-size: 11px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--sand);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(184,149,90,.25);
        }

        .footer-col ul { display: flex; flex-direction: column; gap: 10px; }

        .footer-col a {
            font-size: 13px;
            color: rgba(245,240,232,.4);
            transition: color .2s;
            font-weight: 300;
        }
        .footer-col a:hover { color: var(--gold-lt); }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }

        .footer-contact-item svg { flex-shrink: 0; margin-top: 1px; }

        .footer-contact-item span {
            font-size: 13px;
            color: rgba(245,240,232,.4);
            line-height: 1.55;
            font-weight: 300;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }

        .footer-bottom p {
            font-size: 12px;
            color: rgba(245,240,232,.25);
        }

        .footer-payments {
            display: flex;
            gap: 8px;
        }

        .payment-tag {
            padding: 4px 10px;
            border: 1px solid rgba(245,240,232,.12);
            font-size: 10.5px;
            letter-spacing: .06em;
            color: rgba(245,240,232,.3);
        }

        /* ═══════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════ */
        @media (max-width: 1100px) {
            .product-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 900px) {
            .cat-grid { grid-template-columns: repeat(2, 1fr); }
            .product-grid { grid-template-columns: repeat(3, 1fr); }
            .lookbook { grid-template-columns: 1fr; }
            .features { grid-template-columns: repeat(2, 1fr); }
            .testimonial-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .header-main { height: 60px; }
        }

        @media (max-width: 640px) {
            .top-bar-center, .top-bar-left { display: none; }
            .cat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .product-grid { grid-template-columns: repeat(2, 1fr); }
            .features { grid-template-columns: 1fr; }
            .testimonial-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .hero-content { padding: 0 28px; }
            .hero-corner { display: none; }
            .section { padding: 48px 20px; }
        }

        /* ── Scroll to top ── */
        .scroll-top {
            position: fixed;
            bottom: 28px; right: 28px;
            width: 42px; height: 42px;
            background: var(--ink);
            border: 1px solid var(--gold);
            color: var(--gold);
            display: grid;
            place-items: center;
            cursor: pointer;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s, background .2s;
            z-index: 500;
        }
        .scroll-top.visible { opacity: 1; pointer-events: all; }
        .scroll-top:hover { background: var(--gold); color: var(--ink); }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════════════
     TOP BAR
════════════════════════════════════════════════ -->
<div class="top-bar">
    <div class="top-bar-left">
        <a href="#">Giới thiệu</a>
        <a href="#">Hệ thống cửa hàng</a>
        <a href="#">Blog thời trang</a>
    </div>
    <div class="top-bar-center">✦ MIỄN PHÍ VẬN CHUYỂN CHO ĐƠN HÀNG TRÊN 500.000₫ ✦</div>
    <div class="top-bar-right">
        <a href="#">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 0130 1.18a2 2 0 012 1.72 12 12 0 01.01 2.18"/></svg>
            1800 600 338
        </a>
        <a href="#">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            08:00 – 22:00
        </a>
        <a href="#">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
            Đăng nhập
        </a>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════ -->
<header class="header">
    <div class="header-main">

        <!-- Search (left) -->
        <div class="header-search">
            <div class="search-input-wrap">
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                <button>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Logo (center) -->
        <a href="/" class="logo">
            <span class="logo-text">Tooru</span>
        </a>

        <!-- Icons (right) -->
        <div class="header-icons">
            <button class="header-icon-btn">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                </svg>
                <span>Yêu thích</span>
            </button>
            <button class="header-icon-btn" style="position:relative">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/>
                </svg>
                    <span class="cart-badge">3</span>
                    <span>Giỏ hàng</span>
            </button>
            <a href="{{ route('admin.login')}}">
                   <button class="header-icon-btn">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    <span>Tài khoản</span>
                </button>
            </a>
        </div>
    </div>

    <!-- Navigation Row -->
    <nav class="nav-row">

        <!-- LEFT: Category navigation with multi-level dropdowns -->
        <div class="nav-left">

            <!-- Nữ -->
            <div class="nav-item">
                <span class="nav-link">
                    Nữ
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <div class="dropdown">
                    <div class="dropdown-header">Thời trang nữ</div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">
                            Áo
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <div class="sub-dropdown">
                            <a href="#" class="sub-dropdown-link">Áo sơ mi</a>
                            <a href="#" class="sub-dropdown-link">Áo blouse</a>
                            <a href="#" class="sub-dropdown-link">Áo thun</a>
                            <a href="#" class="sub-dropdown-link">Áo len</a>
                            <a href="#" class="sub-dropdown-link">Áo khoác</a>
                        </div>
                    </div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">
                            Quần
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <div class="sub-dropdown">
                            <a href="#" class="sub-dropdown-link">Quần âu</a>
                            <a href="#" class="sub-dropdown-link">Quần jeans</a>
                            <a href="#" class="sub-dropdown-link">Quần culottes</a>
                            <a href="#" class="sub-dropdown-link">Quần shorts</a>
                        </div>
                    </div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">
                            Váy & Đầm
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <div class="sub-dropdown">
                            <a href="#" class="sub-dropdown-link">Đầm dự tiệc</a>
                            <a href="#" class="sub-dropdown-link">Đầm công sở</a>
                            <a href="#" class="sub-dropdown-link">Váy midi</a>
                            <a href="#" class="sub-dropdown-link">Váy mini</a>
                        </div>
                    </div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">Bộ suit nữ</a>
                    </div>
                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">Set đồ nữ</a>
                    </div>
                </div>
            </div>

            <!-- Nam -->
            <div class="nav-item">
                <span class="nav-link">
                    Nam
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <div class="dropdown">
                    <div class="dropdown-header">Thời trang nam</div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">
                            Áo
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <div class="sub-dropdown">
                            <a href="#" class="sub-dropdown-link">Áo sơ mi nam</a>
                            <a href="#" class="sub-dropdown-link">Áo polo</a>
                            <a href="#" class="sub-dropdown-link">Áo thun nam</a>
                            <a href="#" class="sub-dropdown-link">Áo len nam</a>
                        </div>
                    </div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">
                            Quần nam
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <div class="sub-dropdown">
                            <a href="#" class="sub-dropdown-link">Quần âu nam</a>
                            <a href="#" class="sub-dropdown-link">Quần jeans nam</a>
                            <a href="#" class="sub-dropdown-link">Quần shorts nam</a>
                        </div>
                    </div>

                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">Vest & Blazer</a>
                    </div>
                    <div class="dropdown-item">
                        <a href="#" class="dropdown-link">Bộ suit nam</a>
                    </div>
                </div>
            </div>

            <!-- Bộ sưu tập -->
            <div class="nav-item">
                <span class="nav-link">
                    Bộ sưu tập
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <div class="dropdown">
                    <div class="dropdown-header">Collections</div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Spring / Summer 2025</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Fall / Winter 2025</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Capsule Collection</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Limited Edition</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Lookbook 2025</a></div>
                </div>
            </div>

            <!-- Phụ kiện -->
            <div class="nav-item">
                <span class="nav-link">
                    Phụ kiện
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <div class="dropdown">
                    <div class="dropdown-header">Accessories</div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Túi xách</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Khăn lụa</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Thắt lưng</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Mũ & Nón</a></div>
                    <div class="dropdown-item"><a href="#" class="dropdown-link">Trang sức</a></div>
                </div>
            </div>

            <!-- Sale -->
            <div class="nav-item">
                <span class="nav-link" style="color:#c0392b">
                    SALE
                </span>
            </div>
        </div>

        <!-- RIGHT: Utility links (IVY Moda style — stays fixed) -->
        <div class="nav-right">
            <div class="nav-item">
                <a href="#" class="nav-link">Hướng dẫn size</a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">Ưu đãi</a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">Liên hệ</a>
            </div>
        </div>

    </nav>
</header>

<!-- ═══════════════════════════════════════════════
     HERO BANNER
════════════════════════════════════════════════ -->
<section class="hero" id="hero">
    <div class="hero-slides" id="heroSlides">

        <!-- Slide 1 -->
        <div class="hero-slide">
            <div class="hero-slide-bg slide-1-bg"></div>
            <div class="hero-content">
                <div class="hero-text">
                    <p class="hero-eyebrow">Bộ Sưu Tập Mới · SS 2025</p>
                    <h1 class="hero-title">
                        Thanh lịch<br>trong từng<br><em>chi tiết</em>
                    </h1>
                    <p class="hero-desc">Khám phá ngôn ngữ thời trang tinh tế — nơi chất liệu cao cấp gặp gỡ đường cắt may hoàn hảo.</p>
                    <div class="hero-cta">
                        <a href="#products" class="btn-primary">Khám phá ngay</a>
                        <a href="#lookbook" class="btn-outline">Xem Lookbook</a>
                    </div>
                </div>
            </div>
            <div class="hero-corner">
                <div class="hero-corner-line"></div>
                <span class="hero-corner-tag">SS 2025</span>
                <div class="hero-corner-line"></div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide">
            <div class="hero-slide-bg slide-2-bg"></div>
            <div class="hero-content">
                <div class="hero-text">
                    <p class="hero-eyebrow">Thời Trang Nam · Capsule</p>
                    <h1 class="hero-title">
                        Sức mạnh<br>của sự<br><em>tối giản</em>
                    </h1>
                    <p class="hero-desc">Bộ sưu tập nam giới — đường nét hiện đại, chất lượng vượt thời gian.</p>
                    <div class="hero-cta">
                        <a href="#" class="btn-primary">Khám phá ngay</a>
                        <a href="#" class="btn-outline">Xem thêm</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide">
            <div class="hero-slide-bg slide-3-bg"></div>
            <div class="hero-content">
                <div class="hero-text">
                    <p class="hero-eyebrow">Limited Edition · 2025</p>
                    <h1 class="hero-title">
                        Nghệ thuật<br>trên từng<br><em>thước vải</em>
                    </h1>
                    <p class="hero-desc">Phiên bản giới hạn — khi thời trang trở thành tác phẩm nghệ thuật đích thực.</p>
                    <div class="hero-cta">
                        <a href="#" class="btn-primary">Mua ngay</a>
                        <a href="#" class="btn-outline">Xem bộ sưu tập</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Dots -->
    <div class="hero-dots">
        <button class="hero-dot active" onclick="goToSlide(0)"></button>
        <button class="hero-dot" onclick="goToSlide(1)"></button>
        <button class="hero-dot" onclick="goToSlide(2)"></button>
    </div>

    <!-- Arrows -->
    <div class="hero-arrows">
        <button class="hero-arrow" onclick="prevSlide()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button class="hero-arrow" onclick="nextSlide()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </button>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MARQUEE
════════════════════════════════════════════════ -->
<div class="marquee-strip">
    <div class="marquee-track">
        <!-- Repeat twice for seamless loop -->
        <span class="marquee-item">Chất liệu cao cấp <span class="marquee-dot"></span></span>
        <span class="marquee-item">Thiết kế độc quyền <span class="marquee-dot"></span></span>
        <span class="marquee-item">Giao hàng toàn quốc <span class="marquee-dot"></span></span>
        <span class="marquee-item">Đổi trả 30 ngày <span class="marquee-dot"></span></span>
        <span class="marquee-item">Thành viên VIP giảm 15% <span class="marquee-dot"></span></span>
        <span class="marquee-item">Chất liệu cao cấp <span class="marquee-dot"></span></span>
        <span class="marquee-item">Thiết kế độc quyền <span class="marquee-dot"></span></span>
        <span class="marquee-item">Giao hàng toàn quốc <span class="marquee-dot"></span></span>
        <span class="marquee-item">Đổi trả 30 ngày <span class="marquee-dot"></span></span>
        <span class="marquee-item">Thành viên VIP giảm 15% <span class="marquee-dot"></span></span>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     CATEGORY SECTION
════════════════════════════════════════════════ -->
<section class="section section-alt">
    <div class="section-header">
        <p class="section-eyebrow">Khám phá danh mục</p>
        <h2 class="section-title">Phong cách cho <em>mọi dịp</em></h2>
    </div>
    <div class="cat-grid">
        <div class="cat-card">
            <div class="cat-card-bg cat-bg-1"></div>
            <div class="cat-card-overlay"></div>
            <div class="cat-card-body">
                <p class="cat-card-tag">Bộ sưu tập</p>
                <h3 class="cat-card-name">Thời trang<br>Nữ</h3>
                <span class="cat-card-cta">
                    Xem ngay
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-card-bg cat-bg-2"></div>
            <div class="cat-card-overlay"></div>
            <div class="cat-card-body">
                <p class="cat-card-tag">Phong cách</p>
                <h3 class="cat-card-name">Thời trang<br>Nam</h3>
                <span class="cat-card-cta">
                    Xem ngay
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-card-bg cat-bg-3"></div>
            <div class="cat-card-overlay"></div>
            <div class="cat-card-body">
                <p class="cat-card-tag">New Season</p>
                <h3 class="cat-card-name">Bộ sưu tập<br>SS 2025</h3>
                <span class="cat-card-cta">
                    Xem ngay
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-card-bg cat-bg-4"></div>
            <div class="cat-card-overlay"></div>
            <div class="cat-card-body">
                <p class="cat-card-tag">Accessories</p>
                <h3 class="cat-card-name">Phụ kiện<br>Cao cấp</h3>
                <span class="cat-card-cta">
                    Xem ngay
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     PRODUCTS SECTION
════════════════════════════════════════════════ -->
<section class="section" id="products">
    <div class="section-header">
        <p class="section-eyebrow">Hàng mới về</p>
        <h2 class="section-title">Sản phẩm <em>nổi bật</em></h2>
        <p class="section-desc">Những thiết kế được yêu thích nhất — cập nhật liên tục mỗi tuần.</p>
    </div>

    <!-- Tabs -->
    <div class="product-tabs">
        <button class="product-tab active" onclick="switchTab(this)">Tất cả</button>
        <button class="product-tab" onclick="switchTab(this)">Hàng mới</button>
        <button class="product-tab" onclick="switchTab(this)">Bán chạy</button>
        <button class="product-tab" onclick="switchTab(this)">Đang sale</button>
        <button class="product-tab" onclick="switchTab(this)">Nữ</button>
        <button class="product-tab" onclick="switchTab(this)">Nam</button>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">

        <!-- Product 1 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#e8ddd0,#c8b89a);"></div>
                <span class="product-badge new">Mới</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Áo Blouse Tơ Lụa Cổ V</p>
            <div class="product-price-row">
                <span class="product-price">890.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#e8ddd0; border-color:#c8b89a"></span>
                <span class="color-swatch" style="background:#2c3e50"></span>
                <span class="color-swatch" style="background:#8b6f47"></span>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#d0d8e0,#9aadbe);"></div>
                <span class="product-badge sale">-30%</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Đầm Midi Cổ Điển Hoa Nhỏ</p>
            <div class="product-price-row">
                <span class="product-price" style="color:#c0392b">980.000₫</span>
                <span class="product-price-old">1.400.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#d0d8e0"></span>
                <span class="color-swatch" style="background:#f5f0e8"></span>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#1a1208,#3d2a10,#6b4f2a);"></div>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Blazer Dạ Tweed Nữ Cao Cấp</p>
            <div class="product-price-row">
                <span class="product-price">2.200.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#1a1208"></span>
                <span class="color-swatch" style="background:#6b4f2a"></span>
                <span class="color-swatch" style="background:#3d4f70"></span>
            </div>
        </div>

        <!-- Product 4 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#c8d8c0,#98b890);"></div>
                <span class="product-badge new">Mới</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Quần Culottes Linen Mùa Hè</p>
            <div class="product-price-row">
                <span class="product-price">750.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#c8d8c0"></span>
                <span class="color-swatch" style="background:#e8ddd0"></span>
                <span class="color-swatch" style="background:#f0e8d0"></span>
            </div>
        </div>

        <!-- Product 5 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#e8d8d0,#c0988a);"></div>
                <span class="product-badge sale">-20%</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Váy Xếp Ly Dáng Dài Elegant</p>
            <div class="product-price-row">
                <span class="product-price" style="color:#c0392b">1.120.000₫</span>
                <span class="product-price-old">1.400.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#e8d8d0"></span>
                <span class="color-swatch" style="background:#d0d8e0"></span>
            </div>
        </div>

        <!-- Product 6 (Nam) -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#1e2840,#3d5070,#6080a0);"></div>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Áo Sơ Mi Oxford Nam Slim Fit</p>
            <div class="product-price-row">
                <span class="product-price">690.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#1e2840"></span>
                <span class="color-swatch" style="background:#f5f0e8; border-color:#c8b89a"></span>
                <span class="color-swatch" style="background:#8b9070"></span>
            </div>
        </div>

        <!-- Product 7 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#e0e8d0,#b0c090);"></div>
                <span class="product-badge new">Mới</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Set Áo + Quần Linen Hè 2025</p>
            <div class="product-price-row">
                <span class="product-price">1.350.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#e0e8d0"></span>
                <span class="color-swatch" style="background:#e8d8c0"></span>
            </div>
        </div>

        <!-- Product 8 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#282020,#503030,#8a5050);"></div>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Đầm Dạ Hội Velvet Cổ Thuyền</p>
            <div class="product-price-row">
                <span class="product-price">3.200.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#282020"></span>
                <span class="color-swatch" style="background:#1e2840"></span>
                <span class="color-swatch" style="background:#502040"></span>
            </div>
        </div>

        <!-- Product 9 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#d8d0c0,#b09880);"></div>
                <span class="product-badge sale">-25%</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Quần Âu Nam Wool Slim Fit</p>
            <div class="product-price-row">
                <span class="product-price" style="color:#c0392b">975.000₫</span>
                <span class="product-price-old">1.300.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#d8d0c0"></span>
                <span class="color-swatch" style="background:#282828"></span>
                <span class="color-swatch" style="background:#3d4050"></span>
            </div>
        </div>

        <!-- Product 10 -->
        <div class="product-card">
            <div class="product-img-wrap">
                <div class="product-img-bg" style="background: linear-gradient(135deg,#c8d0e0,#8898b0);"></div>
                <span class="product-badge new">Mới</span>
                <div class="product-actions">
                    <button class="product-action-btn" title="Yêu thích">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </button>
                    <button class="product-action-btn" title="Thêm vào giỏ">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                    </button>
                </div>
            </div>
            <p class="product-name">Khăn Lụa In Hoa Mùa Hè</p>
            <div class="product-price-row">
                <span class="product-price">420.000₫</span>
            </div>
            <div class="product-colors">
                <span class="color-swatch" style="background:#c8d0e0"></span>
                <span class="color-swatch" style="background:#e8d8c0"></span>
                <span class="color-swatch" style="background:#d0c8e0"></span>
            </div>
        </div>

    </div>

    <div class="load-more-wrap">
        <button class="btn-load-more">Xem thêm sản phẩm</button>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     LOOKBOOK / EDITORIAL
════════════════════════════════════════════════ -->
<div class="lookbook" id="lookbook">
    <div class="lookbook-left">
        <div class="lookbook-deco-v">V</div>
        <div class="lookbook-text">
            <p class="section-eyebrow">Editorial 2025</p>
            <h2>Nghệ thuật<br>của sự <em>tinh tế</em></h2>
            <p>Bộ ảnh lookbook mùa xuân hè — một hành trình thị giác qua những thước vải sang trọng và đường may hoàn hảo.</p>
            <a href="#" class="btn-primary" style="display:inline-block; padding:14px 36px;">Xem Lookbook đầy đủ</a>
        </div>
    </div>
    <div class="lookbook-right">
        <div class="lookbook-img lb-1"></div>
        <div class="lookbook-img lb-2"></div>
        <div class="lookbook-img lb-3"></div>
        <div class="lookbook-img lb-4"></div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     USP FEATURES
════════════════════════════════════════════════ -->
<div class="features">
    <div class="feature">
        <div class="feature-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8zM1 16v2h2a2 2 0 004 0H1zM15 18h2a2 2 0 004 0h2v-2h-8v2z"/>
            </svg>
        </div>
        <div class="feature-text">
            <h4>Miễn phí vận chuyển</h4>
            <p>Đơn hàng từ 500.000₫ trở lên được miễn phí giao hàng toàn quốc.</p>
        </div>
    </div>
    <div class="feature">
        <div class="feature-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9M17 13l2 9M9 21a1 1 0 100-2 1 1 0 000 2zM20 21a1 1 0 100-2 1 1 0 000 2z"/>
            </svg>
        </div>
        <div class="feature-text">
            <h4>Đổi trả 30 ngày</h4>
            <p>Chính sách đổi trả linh hoạt trong 30 ngày nếu sản phẩm có lỗi.</p>
        </div>
    </div>
    <div class="feature">
        <div class="feature-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="feature-text">
            <h4>Bảo đảm chính hãng</h4>
            <p>100% sản phẩm chính hãng VELOUR, cam kết chất lượng cao cấp.</p>
        </div>
    </div>
    <div class="feature">
        <div class="feature-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.96a16 16 0 006.29 6.29l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
            </svg>
        </div>
        <div class="feature-text">
            <h4>Hỗ trợ 24/7</h4>
            <p>Đội ngũ tư vấn viên sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi.</p>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TESTIMONIALS
════════════════════════════════════════════════ -->
<section class="section testimonials">
    <div class="section-header">
        <p class="section-eyebrow">Khách hàng nói gì</p>
        <h2 class="section-title">Được tin yêu bởi <em style="color:var(--gold-lt)">hàng nghìn</em> khách hàng</h2>
    </div>
    <div class="testimonial-grid">
        <div class="testimonial-card">
            <div class="testimonial-stars">
                <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
            </div>
            <p class="testimonial-text">"Chất lượng vải thực sự vượt mong đợi. Chiếc đầm tôi mua có đường may rất tinh tế, mặc lên người rất thoải mái và sang trọng."</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">L</div>
                <div>
                    <p class="testimonial-name">Linh Nguyễn</p>
                    <p class="testimonial-since">Khách hàng thân thiết · TP.HCM</p>
                </div>
            </div>
        </div>
        <div class="testimonial-card">
            <div class="testimonial-stars">
                <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
            </div>
            <p class="testimonial-text">"VELOUR là thương hiệu duy nhất tôi tin tưởng cho trang phục công sở. Thiết kế tinh tế, phù hợp với nhiều dịp khác nhau."</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">M</div>
                <div>
                    <p class="testimonial-name">Minh Trần</p>
                    <p class="testimonial-since">Thành viên VIP · Hà Nội</p>
                </div>
            </div>
        </div>
        <div class="testimonial-card">
            <div class="testimonial-stars">
                <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
            </div>
            <p class="testimonial-text">"Dịch vụ giao hàng nhanh, đóng gói rất đẹp và chuyên nghiệp. Sản phẩm đúng như mô tả, thậm chí còn đẹp hơn ngoài thực tế."</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">H</div>
                <div>
                    <p class="testimonial-name">Hương Lê</p>
                    <p class="testimonial-since">Khách hàng · Đà Nẵng</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     NEWSLETTER
════════════════════════════════════════════════ -->
<section class="newsletter">
    <p class="section-eyebrow">Đăng ký nhận tin</p>
    <h2 class="section-title" style="font-family:'Cormorant Garamond',serif; font-size:38px; font-weight:400;">Nhận ưu đãi <em>độc quyền</em></h2>
    <p class="newsletter-desc">Đăng ký để nhận thông tin bộ sưu tập mới nhất và ưu đãi dành riêng cho thành viên.</p>
    <div class="newsletter-form">
        <input class="newsletter-input" type="email" placeholder="Nhập email của bạn...">
        <button class="newsletter-btn">Đăng ký</button>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════ -->
<footer class="footer">
    <div class="footer-grid">

        <!-- Brand -->
        <div class="footer-brand">
            <a href="/" class="logo" style="flex-direction:row; gap:10px; margin-bottom:16px;">
                <div class="logo-icon"><span></span></div>
                <div>
                    <span class="logo-text" style="font-size:20px; letter-spacing:.4em; color:var(--sand);">Velour</span><br>
                    <span class="logo-sub" style="color:rgba(245,240,232,.3);">Fashion House</span>
                </div>
            </a>
            <p>Thương hiệu thời trang cao cấp Việt Nam — nơi phong cách gặp gỡ chất lượng. Mỗi thiết kế là một tuyên ngôn về sự tinh tế.</p>
            <div class="footer-socials">
                <a href="#" class="footer-social">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                <a href="#" class="footer-social">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                <a href="#" class="footer-social">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.53V6.78a4.85 4.85 0 01-1.02-.09z"/></svg>
                </a>
                <a href="#" class="footer-social">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                </a>
            </div>
        </div>

        <!-- Links 1 -->
        <div class="footer-col">
            <h5>Danh mục</h5>
            <ul>
                <li><a href="#">Thời trang nữ</a></li>
                <li><a href="#">Thời trang nam</a></li>
                <li><a href="#">Bộ sưu tập mới</a></li>
                <li><a href="#">Phụ kiện</a></li>
                <li><a href="#">Sale & Khuyến mãi</a></li>
                <li><a href="#">Lookbook 2025</a></li>
            </ul>
        </div>

        <!-- Links 2 -->
        <div class="footer-col">
            <h5>Hỗ trợ khách hàng</h5>
            <ul>
                <li><a href="#">Hướng dẫn mua hàng</a></li>
                <li><a href="#">Chính sách đổi trả</a></li>
                <li><a href="#">Bảng size chuẩn</a></li>
                <li><a href="#">Chương trình thành viên</a></li>
                <li><a href="#">Theo dõi đơn hàng</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="footer-col">
            <h5>Liên hệ</h5>
            <div class="footer-contact-item">
                <svg width="14" height="14" fill="none" stroke="rgba(245,240,232,.4)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>123 Đường Lê Lợi, Quận 1,<br>TP. Hồ Chí Minh</span>
            </div>
            <div class="footer-contact-item">
                <svg width="14" height="14" fill="none" stroke="rgba(245,240,232,.4)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.96a16 16 0 006.29 6.29l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                <span>1800 600 338<br>(08:00 – 22:00 hằng ngày)</span>
            </div>
            <div class="footer-contact-item">
                <svg width="14" height="14" fill="none" stroke="rgba(245,240,232,.4)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span>hello@velour.vn</span>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© {{ date('Y') }} VELOUR Fashion House. Bảo lưu mọi quyền.</p>
        <div class="footer-payments">
            <span class="payment-tag">VISA</span>
            <span class="payment-tag">Mastercard</span>
            <span class="payment-tag">MoMo</span>
            <span class="payment-tag">ZaloPay</span>
            <span class="payment-tag">COD</span>
        </div>
    </div>
</footer>

<!-- Scroll to top -->
<button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<script>
    /* ── Hero Slider ── */
    let current = 0;
    const total = 3;
    let autoTimer;

    function goToSlide(n) {
        current = n;
        document.getElementById('heroSlides').style.transform = `translateX(-${n * 100}%)`;
        document.querySelectorAll('.hero-dot').forEach((d, i) => d.classList.toggle('active', i === n));
    }

    function nextSlide() { goToSlide((current + 1) % total); resetAuto(); }
    function prevSlide() { goToSlide((current - 1 + total) % total); resetAuto(); }

    function resetAuto() {
        clearInterval(autoTimer);
        autoTimer = setInterval(nextSlide, 5500);
    }

    autoTimer = setInterval(nextSlide, 5500);

    /* ── Product tabs ── */
    function switchTab(el) {
        document.querySelectorAll('.product-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    /* ── Scroll to top ── */
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        scrollTopBtn.classList.toggle('visible', window.scrollY > 300);
    }, { passive: true });
</script>
</body>
</html>