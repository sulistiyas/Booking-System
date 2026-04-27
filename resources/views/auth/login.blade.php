<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SaaSFlow - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed-variant": "#004395",
                        "on-secondary-container": "#54647a",
                        "on-error-container": "#93000a",
                        "background": "#f9f9ff",
                        "tertiary-container": "#b75b00",
                        "on-background": "#191b23",
                        "on-surface": "#191b23",
                        "on-primary-fixed": "#001a42",
                        "secondary-container": "#d0e1fb",
                        "secondary-fixed": "#d3e4fe",
                        "on-tertiary": "#ffffff",
                        "primary-fixed": "#d8e2ff",
                        "error": "#ba1a1a",
                        "surface-bright": "#f9f9ff",
                        "tertiary-fixed": "#ffdcc6",
                        "on-surface-variant": "#424754",
                        "secondary": "#505f76",
                        "inverse-primary": "#adc6ff",
                        "outline": "#727785",
                        "surface-tint": "#005ac2",
                        "surface-container": "#ecedf7",
                        "on-tertiary-container": "#fffbff",
                        "inverse-surface": "#2e3038",
                        "inverse-on-surface": "#eff0fa",
                        "on-primary-container": "#fefcff",
                        "primary-fixed-dim": "#adc6ff",
                        "on-secondary-fixed-variant": "#38485d",
                        "outline-variant": "#c2c6d6",
                        "surface-container-low": "#f2f3fd",
                        "surface": "#f9f9ff",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb786",
                        "surface-dim": "#d8d9e3",
                        "on-secondary-fixed": "#0b1c30",
                        "surface-variant": "#e1e2ec",
                        "surface-container-high": "#e6e7f2",
                        "primary-container": "#2170e4",
                        "surface-container-lowest": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed-variant": "#723600",
                        "secondary-fixed-dim": "#b7c8e1",
                        "on-tertiary-fixed": "#311400",
                        "primary": "#0058be",
                        "on-primary": "#ffffff",
                        "surface-container-highest": "#e1e2ec",
                        "tertiary": "#924700"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "base": "8px",
                        "sm": "8px",
                        "lg": "24px",
                        "xxl": "48px",
                        "xl": "32px",
                        "container-margin": "32px",
                        "xs": "4px",
                        "md": "16px"
                    },
                    "fontFamily": {
                        "label-md": ["Manrope"],
                        "h2": ["Manrope"],
                        "h1": ["Manrope"],
                        "h3": ["Manrope"],
                        "body-sm": ["Manrope"],
                        "body-lg": ["Manrope"],
                        "body-md": ["Manrope"],
                        "label-sm": ["Manrope"]
                    },
                    "fontSize": {
                        "label-md": ["14px", { "lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "h2": ["30px", { "lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "h1": ["36px", { "lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "h3": ["24px", { "lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-sm": ["14px", { "lineHeight": "1.5", "letterSpacing": "0", "fontWeight": "400" }],
                        "body-lg": ["18px", { "lineHeight": "1.5", "letterSpacing": "0", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "1.5", "letterSpacing": "0", "fontWeight": "400" }],
                        "label-sm": ["12px", { "lineHeight": "1", "letterSpacing": "0.04em", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
<style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-gutter">
    <main class="w-full max-w-md">
        <div class="bg-surface-container-lowest rounded-xl p-xl shadow-[4px_0_24px_rgba(30,58,138,0.04)] border border-secondary-container flex flex-col gap-lg relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-inverse-primary"></div>
                <div class="text-center flex flex-col items-center gap-sm">
                    <div class="h-12 w-12 bg-primary-fixed rounded-lg flex items-center justify-center text-primary mb-sm">
                        <span class="material-symbols-outlined text-[32px]">dashboard</span>
                    </div>
                    <h1 class="font-h2 text-h2 text-on-surface">SaaSFlow</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">Sign in to Admin Console</p>
                </div>
                <form class="flex flex-col gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface" for="email">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                        <input class="w-full bg-surface-container-lowest border border-secondary-fixed rounded-lg pl-10 pr-3 py-2 font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary focus:border-primary transition-colors outline-none" id="email" name="email" placeholder="admin@example.com" required="" type="email"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface" for="password">Password</label>
                            <a class="font-label-sm text-label-sm text-primary hover:text-primary-container transition-colors" href="#">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <input class="w-full bg-surface-container-lowest border border-secondary-fixed rounded-lg pl-10 pr-3 py-2 font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary focus:border-primary transition-colors outline-none" id="password" name="password" placeholder="••••••••" required="" type="password"/>
                        </div>
                    </div>
                    <div class="pt-sm">
                        <button class="w-full bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md py-3 rounded-lg flex items-center justify-center gap-xs transition-colors shadow-sm active:scale-[0.98]" type="submit">
                                                Sign In
                                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </form>
                <div class="text-center pt-xs">
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                                        Need access? <a class="text-primary font-label-md hover:underline" href="#">Contact Administrator</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>