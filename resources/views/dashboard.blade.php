<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WorkInsight — Platforma za dostavu hrane · Dokumentacija</title>
    <style>
        :root {
            --bg: #0b0f17;
            --panel: #131a26;
            --panel-2: #1a2434;
            --border: #243044;
            --text: #e6edf6;
            --muted: #93a1b5;
            --accent: #f97316;
            --accent-soft: rgba(249, 115, 22, .12);
            --green: #34d399;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: radial-gradient(1200px 600px at 80% -10%, #16202f 0%, var(--bg) 55%);
            color: var(--text);
            font: 15px/1.6 -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 980px; margin: 0 auto; padding: 48px 20px 80px; }

        header.hero { margin-bottom: 32px; }
        .hero h1 { font-size: 30px; margin: 0 0 6px; letter-spacing: -.5px; }
        .hero p { color: var(--muted); margin: 0; max-width: 70ch; }
        .badge {
            display: inline-block; font-size: 12px; font-weight: 600; letter-spacing: .4px;
            color: var(--accent); background: var(--accent-soft);
            padding: 4px 10px; border-radius: 999px; margin-bottom: 14px; text-transform: uppercase;
        }

        section.overview { margin: 0 0 36px; }
        section.overview h2 { font-size: 16px; color: var(--muted); font-weight: 600; margin: 0 0 14px; text-transform: uppercase; letter-spacing: .5px; }
        .ctx-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; }
        .ctx {
            background: var(--panel); border: 1px solid var(--border); border-radius: 12px; padding: 14px 16px;
        }
        .ctx .n { font-size: 11px; color: var(--accent); font-weight: 700; }
        .ctx .t { font-weight: 600; margin: 2px 0 8px; }
        .chips { display: flex; flex-wrap: wrap; gap: 6px; }
        .chip {
            font-size: 11.5px; color: var(--muted); background: var(--panel-2);
            border: 1px solid var(--border); border-radius: 6px; padding: 2px 7px; font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        }

        .docs > h2 { font-size: 16px; color: var(--muted); font-weight: 600; margin: 0 0 14px; text-transform: uppercase; letter-spacing: .5px; }

        details.card {
            background: var(--panel); border: 1px solid var(--border); border-radius: 14px;
            margin-bottom: 14px; overflow: hidden;
        }
        details.card[open] { border-color: #2f3e57; }
        details.card > summary {
            list-style: none; cursor: pointer; padding: 18px 20px;
            display: flex; align-items: center; gap: 12px; user-select: none;
        }
        details.card > summary::-webkit-details-marker { display: none; }
        .summary-icon {
            width: 26px; height: 26px; flex: none; border-radius: 7px; background: var(--accent-soft);
            color: var(--accent); display: grid; place-items: center; font-size: 14px; transition: transform .18s ease;
        }
        details.card[open] .summary-icon { transform: rotate(90deg); }
        .summary-title { font-weight: 600; font-size: 16px; }
        .summary-file { margin-left: auto; font-size: 12px; color: var(--muted); font-family: ui-monospace, monospace; }

        .card-body { padding: 4px 26px 24px; border-top: 1px solid var(--border); }

        /* rendered markdown */
        .md { color: var(--text); }
        .md h1 { font-size: 24px; margin: 24px 0 10px; }
        .md h2 { font-size: 19px; margin: 26px 0 10px; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
        .md h3 { font-size: 16px; margin: 20px 0 8px; color: #cdd9e8; }
        .md h4 { font-size: 14px; margin: 16px 0 6px; color: var(--muted); }
        .md p { margin: 10px 0; }
        .md ul, .md ol { padding-left: 22px; margin: 10px 0; }
        .md li { margin: 4px 0; }
        .md code {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 12.5px;
            background: var(--panel-2); border: 1px solid var(--border); border-radius: 5px; padding: 1px 5px;
        }
        .md pre { background: #0d141f; border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; overflow-x: auto; }
        .md pre code { background: none; border: none; padding: 0; }
        .md blockquote {
            margin: 12px 0; padding: 8px 16px; border-left: 3px solid var(--accent);
            background: var(--accent-soft); color: #d7e0ec; border-radius: 0 8px 8px 0;
        }
        .md table { border-collapse: collapse; width: 100%; margin: 14px 0; font-size: 13px; display: block; overflow-x: auto; }
        .md th, .md td { border: 1px solid var(--border); padding: 7px 10px; text-align: left; vertical-align: top; }
        .md th { background: var(--panel-2); color: #cdd9e8; }
        .md tr:nth-child(even) td { background: rgba(255,255,255,.015); }
        .md hr { border: none; border-top: 1px solid var(--border); margin: 24px 0; }
        .md a { word-break: break-word; }

        pre.mermaid { background: #f8fafc; border-radius: 10px; padding: 18px; text-align: center; overflow-x: auto; }

        footer { margin-top: 40px; color: var(--muted); font-size: 12.5px; text-align: center; }
    </style>
</head>
<body>
    <div class="wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 16px;margin-bottom:24px;background:var(--panel);border:1px solid var(--border);border-radius:12px;">
            <div style="font-size:13.5px;color:var(--muted);">
                Prijavljeni kao <strong style="color:var(--text);">{{ auth()->user()->name }}</strong>
                @if (auth()->user()->getRoleNames()->isNotEmpty())
                    · <span style="color:var(--accent);font-weight:600;">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" style="cursor:pointer;font:inherit;font-size:13.5px;font-weight:600;color:var(--text);background:var(--panel-2);border:1px solid var(--border);border-radius:9px;padding:8px 16px;">Odjava</button>
            </form>
        </div>
        <header class="hero">
            <span class="badge">WorkInsight · Laravel {{ app()->version() }}</span>
            <h1>Platforma za upravljanje dostavom hrane</h1>
            <p>Interna razvojna dokumentacija i pregled domenske sheme. Modularni Laravel monolit (MariaDB, Filament + Livewire/Tabler, Redis/Horizon, Reverb GPS).</p>
        </header>

        <section class="overview">
            <h2>Pregled domene — bounded contexti</h2>
            <div class="ctx-grid">
                <div class="ctx">
                    <div class="n">01 · IDENTITET</div>
                    <div class="t">Identitet i pristup</div>
                    <div class="chips"><span class="chip">users</span><span class="chip">roles</span><span class="chip">permissions</span><span class="chip">staff_profiles</span></div>
                </div>
                <div class="ctx">
                    <div class="n">02 · PROFILI</div>
                    <div class="t">Profili i adrese</div>
                    <div class="chips"><span class="chip">customers</span><span class="chip">companies</span><span class="chip">company_contacts</span><span class="chip">pensioners</span><span class="chip">addresses</span></div>
                </div>
                <div class="ctx">
                    <div class="n">03 · MENIJI</div>
                    <div class="t">Meniji</div>
                    <div class="chips"><span class="chip">meal_categories</span><span class="chip">meals</span><span class="chip">menus</span><span class="chip">menu_items</span></div>
                </div>
                <div class="ctx">
                    <div class="n">04 · NARUDŽBE</div>
                    <div class="t">Narudžbe i pretplate</div>
                    <div class="chips"><span class="chip">subscriptions</span><span class="chip">orders</span><span class="chip">order_lines</span></div>
                </div>
                <div class="ctx">
                    <div class="n">05 · DOSTAVA</div>
                    <div class="t">Dostava</div>
                    <div class="chips"><span class="chip">delivery_lines</span><span class="chip">line_assignments</span><span class="chip">deliveries</span><span class="chip">delivery_items</span><span class="chip">delivery_status_logs</span><span class="chip">driver_locations</span></div>
                </div>
                <div class="ctx">
                    <div class="n">06 · UGOVORI</div>
                    <div class="t">Ugovori</div>
                    <div class="chips"><span class="chip">contracts</span></div>
                </div>
                <div class="ctx">
                    <div class="n">07 · NAPLATA</div>
                    <div class="t">Naplata i izvještaji</div>
                    <div class="chips"><span class="chip">invoice_exports</span><span class="chip">invoice_export_lines</span><span class="chip">monthly_reports</span><span class="chip">delivery_daily_summaries</span><span class="chip">billing_monthly_summaries</span></div>
                </div>
                <div class="ctx">
                    <div class="n">08 · INTEGRACIJE</div>
                    <div class="t">Integracije, postavke, logovi</div>
                    <div class="chips"><span class="chip">para_sync_runs</span><span class="chip">settings</span><span class="chip">sms_logs</span><span class="chip">mail_logs</span></div>
                </div>
            </div>
        </section>

        <section class="docs">
            <h2>Dokumentacija</h2>

            @if ($mermaid)
                <details class="card" open>
                    <summary>
                        <span class="summary-icon">▸</span>
                        <span class="summary-title">ERD dijagram (vizualni)</span>
                        <span class="summary-file">ERD_dijagram.mermaid</span>
                    </summary>
                    <div class="card-body">
                        <pre class="mermaid">{{ $mermaid }}</pre>
                    </div>
                </details>
            @endif

            @foreach ($docs as $doc)
                <details class="card">
                    <summary>
                        <span class="summary-icon">▸</span>
                        <span class="summary-title">{{ $doc['title'] }}</span>
                        <span class="summary-file">{{ $doc['file'] }}</span>
                    </summary>
                    <div class="card-body">
                        <div class="md">{!! $doc['html'] !!}</div>
                    </div>
                </details>
            @endforeach

            @if (empty($docs) && ! $mermaid)
                <div class="ctx">Nema dokumentacije u <code>docs/</code> folderu.</div>
            @endif
        </section>

        <footer>
            WorkInsight · interni pregled dokumentacije · {{ now()->format('d.m.Y.') }}
        </footer>
    </div>

    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: true, theme: 'neutral' });
    </script>
</body>
</html>
