<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings->nama_app ?? 'LaundryHub') - {{ $settings->nama_app ?? 'LaundryHub' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        try {
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "surface": "#f7f9fb", "surface-dim": "#d8dadc", "surface-bright": "#f7f9fb",
                            "surface-container-lowest": "#ffffff", "surface-container-low": "#f2f4f6",
                            "surface-container": "#eceef0", "surface-container-high": "#e6e8ea",
                            "surface-container-highest": "#e0e3e5", "on-surface": "#191c1e",
                            "on-surface-variant": "#434655", "inverse-surface": "#2d3133",
                            "inverse-on-surface": "#eff1f3", "inverse-primary": "#b4c5ff",
                            "background": "#f7f9fb", "on-background": "#191c1e",
                            "primary": "#004ac6", "primary-container": "#2563eb",
                            "primary-fixed": "#dbe1ff", "primary-fixed-dim": "#b4c5ff",
                            "on-primary": "#ffffff", "on-primary-container": "#eeefff",
                            "on-primary-fixed": "#00174b", "on-primary-fixed-variant": "#003ea8",
                            "secondary": "#00687a", "secondary-container": "#57dffe",
                            "secondary-fixed": "#acedff", "secondary-fixed-dim": "#4cd7f6",
                            "on-secondary": "#ffffff", "on-secondary-container": "#006172",
                            "on-secondary-fixed": "#001f26", "on-secondary-fixed-variant": "#004e5c",
                            "tertiary": "#006242", "tertiary-container": "#007d55",
                            "tertiary-fixed": "#6ffbbe", "tertiary-fixed-dim": "#4edea3",
                            "on-tertiary": "#ffffff", "on-tertiary-container": "#bdffdb",
                            "on-tertiary-fixed": "#002113", "on-tertiary-fixed-variant": "#005236",
                            "error": "#ba1a1a", "error-container": "#ffdad6",
                            "on-error": "#ffffff", "on-error-container": "#93000a",
                            "outline": "#737686", "outline-variant": "#c3c6d7", "surface-tint": "#0053db",
                            "surface-variant": "#e0e3e5"
                        },
                        borderRadius: {
                            DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", "2xl": "1rem", "3xl": "1.5rem", full: "9999px"
                        },
                        spacing: {
                            xs: "4px", sm: "12px", base: "8px", md: "24px", lg: "40px", xl: "64px",
                            gutter: "24px", "margin-desktop": "48px", "margin-mobile": "16px"
                        },
                        fontFamily: {
                            "label-lg": ["Plus Jakarta Sans"], "label-sm": ["Plus Jakarta Sans"],
                            "body-lg": ["Plus Jakarta Sans"], "headline-sm": ["Plus Jakarta Sans"],
                            "body-md": ["Plus Jakarta Sans"], "headline-lg": ["Plus Jakarta Sans"],
                            "body-sm": ["Plus Jakarta Sans"], "headline-md": ["Plus Jakarta Sans"],
                            "headline-lg-mobile": ["Plus Jakarta Sans"]
                        },
                        fontSize: {
                            "label-lg": ["14px", { lineHeight: "20px", letterSpacing: "0.1px", fontWeight: "600" }],
                            "headline-sm": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                            "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                            "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
                            "headline-md": ["28px", { lineHeight: "36px", fontWeight: "600" }],
                            "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.5px", fontWeight: "500" }],
                            "headline-lg": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "700" }],
                            "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                            "headline-lg-mobile": ["32px", { lineHeight: "38px", letterSpacing: "-0.01em", fontWeight: "700" }]
                        }
                    },
                },
            }
        } catch (_e) { }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .timeline-line { position: absolute; left: 20px; top: 0; bottom: 0; width: 2px; background: #c3c6d7; }
        .bento-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .bento-grid > :nth-child(1) { grid-row: span 2; aspect-ratio: auto; }
        @media (min-width: 1024px) {
            .bento-grid { grid-template-columns: repeat(4, 1fr); }
            .bento-grid > :nth-child(1) { grid-row: span 2; grid-column: span 2; aspect-ratio: auto; }
            .desktop-wrapper { max-width: 1200px; margin-left: auto; margin-right: auto; }
            .mobile-only { display: none !important; }
            .desktop-only { display: flex !important; }
        }
        @media (max-width: 1023px) {
            .desktop-only { display: none !important; }
        }
        .soft-shadow { box-shadow: 0 20px 40px -15px rgba(0,74,198,0.08); }
    </style>
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="overflow-x-hidden bg-background text-on-background">
    @yield('content')

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
            t.fire({ icon: 'success', title: '{{ session('success') }}' });
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
            t.fire({ icon: 'error', title: '{{ session('error') }}' });
        });
    </script>
    @endif
    @stack('scripts')
</body>
</html>
