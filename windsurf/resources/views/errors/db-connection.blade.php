<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Serviço Indisponível - Grupo Rota do Mar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="mb-8 flex justify-center">
            <div class="bg-red-500/20 p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold mb-4">Serviço Temporariamente Indisponível</h1>

        <div class="bg-gray-800 rounded-xl p-6 mb-8 border border-gray-700 shadow-xl text-left">
            <p class="text-gray-300 mb-4 leading-relaxed">
                Ops! O sistema não conseguiu se conectar ao banco de dados. Isso geralmente acontece quando os serviços auxiliares não foram iniciados.
            </p>

            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">O que fazer agora?</h2>

            <ul class="space-y-3">
                <li class="flex items-start">
                    <span class="bg-blue-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center mt-0.5 mr-3 flex-shrink-0">1</span>
                    <span class="text-gray-300 text-sm">Verifique se o <strong>XAMPP</strong> (ou seu servidor de banco de dados) está aberto.</span>
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center mt-0.5 mr-3 flex-shrink-0">2</span>
                    <span class="text-gray-300 text-sm">Certifique-se de que os módulos <strong>Apache</strong> e <strong>MySQL</strong> estão marcados em <span class="text-green-400 font-mono">Running</span>.</span>
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center mt-0.5 mr-3 flex-shrink-0">3</span>
                    <span class="text-gray-300 text-sm">Após iniciar os serviços, aguarde 5 segundos e <strong>recarregue esta página</strong>.</span>
                </li>
            </ul>
        </div>

        <button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Tentar Recarregar Agora
        </button>

        <p class="mt-8 text-gray-500 text-xs italic">
            Erro técnico identificado: Conexão recusada (MySQL não iniciado)
        </p>
    </div>
</body>
</html>
