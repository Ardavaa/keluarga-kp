<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'Laporan')</title>
        <style>
            @page { margin: 28px 32px; }
            body { font-family: 'Helvetica', Arial, sans-serif; color: #222222; font-size: 11px; }
            .header { border-bottom: 3px solid #9F1521; padding-bottom: 10px; margin-bottom: 18px; }
            .header .brand { color: #9F1521; font-size: 10px; font-weight: bold; letter-spacing: 0.05em; text-transform: uppercase; }
            .header h1 { margin: 4px 0 2px; font-size: 18px; color: #222222; }
            .header .subtitle { color: #666666; font-size: 10px; }
            .header .meta { margin-top: 6px; color: #666666; font-size: 9px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
            th { background-color: #F5F5F5; color: #444444; text-align: left; padding: 6px 8px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.03em; border-bottom: 1px solid #D0D6DD; }
            td { padding: 6px 8px; border-bottom: 1px solid #D9D6D6; vertical-align: top; }
            tr:nth-child(even) td { background-color: #F9F9F9; }
            .section-title { color: #9F1521; font-size: 12px; font-weight: bold; margin: 16px 0 6px; text-transform: uppercase; letter-spacing: 0.03em; }
            .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; background-color: #F5F5F5; color: #444444; }
            .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #D0D6DD; color: #666666; font-size: 8px; }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="brand">Satgas AI FIF &middot; Fakultas Informatika, Telkom University</div>
            <h1>@yield('title', 'Laporan')</h1>
            @hasSection('subtitle')
                <div class="subtitle">@yield('subtitle')</div>
            @endif
            <div class="meta">Diunduh: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>

        @yield('content')

        <div class="footer">
            Dashboard Riset Dosen FIF &mdash; KP Kelompok 1, Fakultas Informatika, Telkom University
        </div>
    </body>
</html>
