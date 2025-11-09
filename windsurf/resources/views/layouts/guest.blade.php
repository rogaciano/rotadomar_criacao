<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes oceanWave {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
                33% { transform: translateY(-30px) translateX(15px) rotate(5deg); }
                66% { transform: translateY(15px) translateX(-15px) rotate(-5deg); }
            }
            
            @keyframes wave {
                0%, 100% { transform: translateX(0) translateY(0); }
                25% { transform: translateX(-25px) translateY(10px); }
                50% { transform: translateX(-50px) translateY(0); }
                75% { transform: translateX(-25px) translateY(-10px); }
            }
            
            .ocean-background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(-45deg, 
                    #0077be 0%,
                    #1e90ff 15%,
                    #4169e1 30%,
                    #6495ed 45%,
                    #00bfff 60%,
                    #1e90ff 75%,
                    #0077be 100%
                );
                background-size: 400% 400%;
                animation: oceanWave 20s ease infinite;
            }
            
            .wave-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: 
                    radial-gradient(ellipse at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 70%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                    radial-gradient(ellipse at 50% 50%, rgba(30, 144, 255, 0.3) 0%, transparent 80%);
                z-index: 1;
                pointer-events: none;
            }
            
            .floating-shape {
                animation: float 25s ease-in-out infinite;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.05) 100%);
                border: 2px solid rgba(255, 255, 255, 0.3);
                z-index: 2;
                pointer-events: none;
                box-shadow: 0 4px 20px rgba(255, 255, 255, 0.2);
            }
            
            .wave-shape {
                animation: wave 15s ease-in-out infinite;
                z-index: 2;
                pointer-events: none;
            }
            
            .login-card {
                backdrop-filter: blur(20px) saturate(180%);
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            }
            
            /* Efeito de marca d'água sutil */
            .watermark {
                position: fixed;
                bottom: 30px;
                right: 30px;
                font-size: 1rem;
                color: rgba(255, 255, 255, 0.8);
                font-weight: 700;
                z-index: 100;
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
                letter-spacing: 2px;
                pointer-events: none;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Ocean animated background -->
        <div class="ocean-background"></div>
        
        <!-- Wave overlay -->
        <div class="wave-overlay"></div>
        
        <!-- Watermark -->
        <div class="watermark">ROTA DO MAR</div>
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative floating shapes (bolhas no mar) -->
            <div class="absolute top-10 left-10 w-32 h-32 rounded-full floating-shape opacity-20" style="animation-delay: 0s;"></div>
            <div class="absolute bottom-20 right-20 w-48 h-48 rounded-full floating-shape opacity-15" style="animation-delay: 3s;"></div>
            <div class="absolute top-1/3 right-1/4 w-24 h-24 rounded-full floating-shape opacity-20" style="animation-delay: 6s;"></div>
            <div class="absolute bottom-1/3 left-1/4 w-40 h-40 rounded-full floating-shape opacity-12" style="animation-delay: 9s;"></div>
            <div class="absolute top-2/3 left-1/3 w-28 h-28 rounded-full floating-shape opacity-18" style="animation-delay: 12s;"></div>
            
            <!-- Ondas decorativas -->
            <div class="absolute bottom-0 left-0 w-full h-32 wave-shape opacity-10" style="background: linear-gradient(to top, rgba(255,255,255,0.2), transparent);"></div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 login-card shadow-2xl overflow-hidden sm:rounded-2xl relative z-10 transform transition-all duration-300 hover:shadow-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
