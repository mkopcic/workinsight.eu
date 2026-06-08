<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WorkInsight — Platforma za dostavu hrane</title>
    <style>
        :root {
            --bg: #0b0f17;
            --text: #e6edf6;
            --muted: #93a1b5;
            --accent: #f97316;
            --accent-soft: rgba(249, 115, 22, .12);
            --border: #243044;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            background: radial-gradient(900px 500px at 80% -10%, #16202f 0%, var(--bg) 55%);
            color: var(--text);
            font: 16px/1.6 -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            display: flex; flex-direction: column;
        }
        .top {
            display: flex; align-items: center; justify-content: space-between;
            max-width: 1100px; width: 100%; margin: 0 auto; padding: 22px 24px;
        }
        .brand { font-weight: 700; letter-spacing: -.3px; }
        .brand span { color: var(--accent); }
        .nav a {
            color: var(--text); text-decoration: none; font-size: 14px; font-weight: 600;
            padding: 9px 16px; border-radius: 9px; border: 1px solid transparent;
        }
        .nav a.ghost { border-color: var(--border); color: var(--muted); }
        .nav a.ghost:hover { color: var(--text); border-color: #34425c; }
        .nav a.solid { background: var(--accent); color: #1a1206; }
        .nav a.solid:hover { filter: brightness(1.07); }

        main {
            flex: 1; display: grid; place-items: center; padding: 40px 24px;
        }
        .hero { max-width: 720px; text-align: center; }
        .badge {
            display: inline-block; font-size: 12px; font-weight: 600; letter-spacing: .5px;
            color: var(--accent); background: var(--accent-soft);
            padding: 5px 12px; border-radius: 999px; margin-bottom: 22px; text-transform: uppercase;
        }
        .hero h1 { font-size: 44px; line-height: 1.1; letter-spacing: -1px; margin: 0 0 18px; }
        .hero p { color: var(--muted); font-size: 18px; margin: 0 auto 30px; max-width: 56ch; }
        .cta { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .cta a {
            text-decoration: none; font-weight: 600; font-size: 15px; padding: 13px 26px; border-radius: 11px;
        }
        .cta a.primary { background: var(--accent); color: #1a1206; }
        .cta a.primary:hover { filter: brightness(1.07); }
        .cta a.secondary { border: 1px solid var(--border); color: var(--text); }
        .cta a.secondary:hover { border-color: #34425c; }

        footer { color: var(--muted); font-size: 13px; text-align: center; padding: 22px; }
    </style>
</head>
<body>
    <div class="top">
        <div class="brand">Work<span>Insight</span></div>
        <nav class="nav">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="solid">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="ghost">Prijava</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="solid">Registracija</a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>

    <main>
        <div class="hero">
            <span class="badge">Dostava hrane · B2B i B2C</span>
            <h1>Upravljanje dostavom hrane na jednom mjestu</h1>
            <p>Tjedni meniji, narudžbe za fizičke i pravne osobe, planiranje ruta, praćenje vozača uživo i mjesečna naplata — sve u jednoj platformi.</p>
            <div class="cta">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="primary">Otvori dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="primary">Prijava</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="secondary">Otvori račun</a>
                        @endif
                    @endauth
                @else
                    <a href="#" class="primary">Uskoro</a>
                @endif
            </div>
        </div>
    </main>

    <footer>© {{ now()->year }} WorkInsight · Platforma za upravljanje dostavom hrane</footer>
</body>
</html>
