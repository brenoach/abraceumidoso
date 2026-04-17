<?php
if (file_exists(ROOT_PATH . 'vendor/autoload.php')) {
    require_once ROOT_PATH . 'vendor/autoload.php';
} else {
    die("Erro Crítico: A pasta 'vendor' não foi encontrada. O login com Google não vai funcionar.");
}

    
    $clientID = '542179864570-vf7jgq7cqtq8snk5udevo5dubbkkshsr.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/abraceumidoso/actions/callback_google.php';

    
    //   Criar o cliente do Google
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");    
    
    //              Gerar a URL para o botão de login
    $loginUrl = $client->createAuthUrl();
    
    
    //      Se o usuário já estiver logado, não deixa ele ver a tela de login de novo
    
    //   Gerar a URL para o botão de login
           //echo "<a href='$loginUrl' style='padding:10px; background:#4285f4; color:white; text-decoration:none;'>Fazer Login com Google</a>";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrace um Idoso</title>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/assets/css/style.css?v=1.6">

    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/img/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/img/favicon-apple.png">

    <style>
        .perfil-cabecalho { display: flex; align-items: center; gap: 10px; padding: 5px 15px; border-right: 1px solid #ddd; }
        .user-avatar img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #673AB7; }
        .user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .user-info strong { color: #5A3821; font-size: 0.9rem; }
        .user-info small { color: #673AB7; font-size: 0.7rem; font-weight: bold; }
    </style>
</head>
<body>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind Config for Custom Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            creme: '#FDF8F4',
                            marrom: '#5B3A26',
                            roxo: '#6A1B9A',
                            verde: '#4CAF50',
                            laranja: '#ebb860'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Styles & Chart Container Constraints */
        body {
            background-color: #FDF8F4; /* Creme background */
            color: #333333;
            scroll-behavior: smooth;
        }

        .chart-container {
            position: relative;
            width: 100%;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            height: 280px;
            max-height: 350px;
        }

        @media (min-width: 768px) {
            .chart-container {
                height: 320px;
            }
        }

        /* Hide scrollbar for clean horizontal scrolling areas if any */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .step-active {
            border-color: #6A1B9A;
            background-color: #F3E8FF;
        }

        .bg-crossfade {
            transition: opacity 1.5s ease-in-out;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Chosen Palette: Creme (#FDF8F4), Marrom (#5B3A26), Roxo (#6A1B9A), Verde (#4CAF50) -->
    
    <!-- Application Structure Plan: 
         The SPA is structured as a vertical scrolling storytelling dashboard, moving from the emotional hook (Hero) to the logical problem (Context), the proposed value (Audience & Flow), quantitative impact (Metrics Dashboard), and finally the technical backbone (Tech & Conclusion). 
         - A fixed navigation bar allows quick jumping between sections.
         - Interactive tabs in the Context section prevent text overload.
         - An interactive stepper explains the operational flow sequentially.
         - A dedicated Metrics section uses Chart.js to simulate the measurable goals outlined in the PI.
         This structure converts a linear presentation into an exploratory, user-driven experience. 
    -->
    
    <!-- Visualization & Content Choices: 
         - Goal: Inform about the context -> Method: Interactive Tabs -> Interaction: Click to toggle text blocks -> Justification: Keeps UI clean while providing detailed background.
         - Goal: Explain the system flow -> Method: Interactive Timeline/Stepper -> Interaction: Click steps to reveal description -> Justification: Breaks down complex logistics into digestible actions.
         - Goal: Demonstrate Metrics (Visits/Letters) -> Method: Chart.js Bar & Line charts -> Interaction: Tooltips & Legends -> Justification: Quantifies the "Mensurabilidade" goal effectively without SVG (using Canvas).
         - NO SVG/Mermaid used. Reliance on CSS, HTML structure, and Unicode emojis for visual cues.
    -->
    
    <!-- CONFIRMATION: NO SVG graphics used. NO Mermaid JS used. -->

    <!-- Navigation -->
    <!-- <nav class="fixed top-0 w-full bg-brand-creme/90 backdrop-blur-md border-b border-brand-marrom/10 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <span class="text-2xl">👴👵</span>
                    <span class="font-bold text-xl text-brand-marrom">Abrace um Idoso</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#cenario" class="text-brand-marrom hover:text-brand-roxo transition-colors font-medium">Cenário</a>
                    <a href="#solucao" class="text-brand-marrom hover:text-brand-roxo transition-colors font-medium">A Solução</a>
                    <a href="#metricas" class="text-brand-marrom hover:text-brand-roxo transition-colors font-medium">Métricas</a>
                    <a href="#tecnologia" class="text-brand-marrom hover:text-brand-roxo transition-colors font-medium">Tecnologia</a>
                </div>
            </div>
        </div>
    </nav> -->

    <!-- Main Content -->
    <main class="pt-20 pb-12">
        
        <!-- Hero Section -->
        <section id="hero" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 flex flex-col items-center text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-brand-marrom tracking-tight mb-4">
                Conectando gerações <br> <span class="text-brand-roxo">com carinho</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg md:text-xl text-gray-600 mb-10">
                Uma plataforma web dedicada a combater a solidão em Instituições de Longa Permanência (ILPIs), facilitando visitas e a troca de cartas.
            </p>

            <!-- Video Player Simulation -->
            <div id="simulated-video-player" class="w-full max-w-4xl aspect-video bg-black rounded-2xl shadow-2xl relative overflow-hidden group border-4 border-white/50 cursor-pointer" onclick="toggleVideo()">
                <!-- Backgrounds for Crossfade -->
                <div id="vid-bg-1" class="absolute inset-0 bg-cover bg-center bg-crossfade opacity-60" style="background-image: url('https://images.unsplash.com/photo-1516307365426-bea591f05011?auto=format&fit=crop&w=1200&q=80');"></div>
                <div id="vid-bg-2" class="absolute inset-0 bg-cover bg-center bg-crossfade opacity-0"></div>
                
                <!-- Play Overlay -->
                <div id="vid-play-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 group-hover:bg-black/30 transition-all duration-500 z-30">
                    <div class="w-20 h-20 bg-brand-roxo rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(106,27,154,0.6)] transform group-hover:scale-110 transition-transform duration-300">
                        <span class="text-white text-3xl ml-1" id="vid-play-icon">▶</span>
                    </div>
                    <p class="text-white mt-6 font-medium px-6 py-2 rounded-full bg-black/60 tracking-wide" id="vid-play-text">Assistir ao Vídeo da Campanha</p>
                </div>

                <!-- Subtitles -->
                <div class="absolute bottom-10 left-0 right-0 px-4 md:px-12 text-center z-20 pointer-events-none">
                    <p id="vid-subtitles" class="text-lg md:text-2xl text-white font-semibold drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] bg-black/40 inline-block px-6 py-3 rounded-xl opacity-0 transition-opacity duration-500 max-w-2xl"></p>
                </div>

                <!-- Progress Bar -->
                <div class="absolute bottom-0 left-0 h-1.5 bg-brand-roxo z-40 transition-all duration-100 ease-linear" id="vid-progress" style="width: 0%;"></div>
            </div>
            
            <!-- Background Music Audio Element -->
            <audio id="bg-music" loop hidden>
                <!-- Music: "River Meditation" by Audionautix is licensed under a Creative Commons Attribution 4.0 license. https://creativecommons.org/licenses/by/4.0/ -->
                <!-- Artist: http://audionautix.com/ -->
                <source src="https://audionautix.com/Music/RiverMeditation.mp3" type="audio/mpeg">
                Seu navegador não suporta o elemento de áudio.
            </audio>

            <p class="mt-6 text-sm text-gray-500 font-medium">Projeto Integrador • Abrace Um Idoso - 2026</p>
        </section>

        <!-- Context & Problem Section -->
        <section id="cenario" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-brand-marrom/10">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-brand-marrom mb-4">Entendendo o Desafio</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Explore o contexto que motivou a criação do projeto e o problema central que buscamos resolver na Baixada Santista.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8 items-start">
                <!-- Interactive Tabs -->
                <div class="w-full md:w-1/3 flex flex-col gap-3">
                    <button onclick="switchTab('tab-cenario', this)" class="tab-btn w-full text-left px-6 py-4 rounded-xl border-2 border-brand-roxo bg-brand-roxo text-white font-bold transition-all shadow-md">
                        🌍 O Cenário Atual
                    </button>
                    <button onclick="switchTab('tab-problema', this)" class="tab-btn w-full text-left px-6 py-4 rounded-xl border-2 border-gray-200 bg-white text-gray-600 font-bold hover:border-brand-roxo/50 transition-all shadow-sm">
                        ⚠️ O Problema
                    </button>
                    <button onclick="switchTab('tab-diferencial', this)" class="tab-btn w-full text-left px-6 py-4 rounded-xl border-2 border-gray-200 bg-white text-gray-600 font-bold hover:border-brand-roxo/50 transition-all shadow-sm">
                        ⭐ Nosso Diferencial
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="w-full md:w-2/3 bg-white p-8 rounded-2xl shadow-lg min-h-[250px] border border-gray-100">
                    <div id="tab-cenario" class="tab-content block">
                        <h3 class="text-2xl font-bold text-brand-marrom mb-4">Capital dos Idosos</h3>
                        <p class="text-gray-700 leading-relaxed text-lg">
                            Santos é a cidade com a maior proporção de idosos no Brasil, representando <strong class="text-brand-roxo">22% da população</strong>. 
                            Neste cenário, dezenas de Instituições de Longa Permanência (ILPIs) abrigam idosos que, frequentemente, sofrem com a <strong>solidão e o isolamento social</strong> severo, distantes do convívio familiar e comunitário.
                        </p>
                    </div>
                    
                    <div id="tab-problema" class="tab-content hidden">
                        <h3 class="text-2xl font-bold text-brand-marrom mb-4">Desorganização e Barreiras</h3>
                        <p class="text-gray-700 leading-relaxed text-lg mb-4">
                            Atualmente, a gestão de visitas é feita de forma ineficiente e apenas restrito a idosos:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700">
                            <li>Uso de planilhas de Excel confusas. Às vezes nem isso.  </li>
                            <li>Grupos de WhatsApp desorganizados.</li>
                            <li>Visitas sem agendamento prévio, sobrecarregando a equipe técnica.</li>
                        </ul>
                        <p class="mt-4 text-gray-700">Falta uma ferramenta centralizada que conecte a boa vontade da comunidade à rotina estruturada das instituições.</p>
                    </div>

                    <div id="tab-diferencial" class="tab-content hidden">
                        <h3 class="text-2xl font-bold text-brand-marrom mb-4">Por que somos diferentes?</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div class="p-4 bg-brand-creme rounded-lg border border-brand-marrom/10">
                                <span class="text-2xl mb-2 block">❤️</span>
                                <h4 class="font-bold text-brand-marrom">Foco Afetivo</h4>
                                <p class="text-sm text-gray-600">Conexão baseada na <i>história de vida</i> do idoso, não apenas logística.</p>
                            </div>
                            <div class="p-4 bg-brand-creme rounded-lg border border-brand-marrom/10">
                                <span class="text-2xl mb-2 block">✉️</span>
                                <h4 class="font-bold text-brand-marrom">Sistema de Cartas</h4>
                                <p class="text-sm text-gray-600">Troca de mensagens digitais que são impressas e entregues fisicamente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Flow & Audience Section -->
        <section id="solucao" class="bg-brand-marrom text-brand-creme py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">A Solução em Ação</h2>
                    <p class="max-w-2xl mx-auto opacity-80">Como o "Abrace um Idoso" funciona na prática para os três pilares do projeto: Idosos, Voluntários e Funcionários.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Audience Cards -->
                    <div class="space-y-6">
                        <div class="bg-white/10 p-6 rounded-xl backdrop-blur-sm border border-white/20 flex gap-4 items-start">
                            <div class="text-4xl">👴</div>
                            <div>
                                <h3 class="font-bold text-xl text-white">Beneficiários: Os Idosos</h3>
                                <p class="opacity-80 text-sm mt-1">Redução da depressão senil e maior engajamento social ao receberem atenção contínua e personalizada.</p>
                            </div>
                        </div>
                        <div class="bg-white/10 p-6 rounded-xl backdrop-blur-sm border border-white/20 flex gap-4 items-start">
                            <div class="text-4xl">🤝</div>
                            <div>
                                <h3 class="font-bold text-xl text-white">Usuários: Voluntários</h3>
                                <p class="opacity-80 text-sm mt-1">Pessoas da comunidade santista encontram um ambiente prático e seguro para doar seu tempo e carinho.</p>
                            </div>
                        </div>
                        <div class="bg-white/10 p-6 rounded-xl backdrop-blur-sm border border-white/20 flex gap-4 items-start">
                            <div class="text-4xl">📋</div>
                            <div>
                                <h3 class="font-bold text-xl text-white">Gestores: Funcionários</h3>
                                <p class="opacity-80 text-sm mt-1">Administradores ganham facilidade na gestão de agendas, aprovação de perfis e organização da rotina.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Flow -->
                    <div class="bg-white text-gray-800 p-8 rounded-2xl shadow-xl">
                        <h3 class="text-2xl font-bold text-brand-marrom mb-6 text-center">O Fluxo de Agendamento</h3>
                        
                        <div class="relative border-l-4 border-gray-200 ml-4 space-y-8 pb-4">
                            
                            <div class="relative pl-8 cursor-pointer group" onclick="highlightStep(this)">
                                <div class="absolute w-8 h-8 bg-brand-roxo rounded-full -left-[18px] top-0 flex items-center justify-center text-white font-bold border-4 border-white shadow-sm transition-transform group-hover:scale-110">1</div>
                                <h4 class="font-bold text-lg text-brand-roxo">Escolha e Empatia</h4>
                                <p class="text-sm text-gray-600 mt-1">O voluntário acessa o mural, lê a história de vida e escolhe um idoso com quem se identifica.</p>
                            </div>

                            <div class="relative pl-8 cursor-pointer group opacity-60 hover:opacity-100 transition-opacity" onclick="highlightStep(this)">
                                <div class="absolute w-8 h-8 bg-gray-300 rounded-full -left-[18px] top-0 flex items-center justify-center text-white font-bold border-4 border-white shadow-sm transition-transform group-hover:scale-110">2</div>
                                <h4 class="font-bold text-lg text-gray-700">Seleção de Data</h4>
                                <p class="text-sm text-gray-600 mt-1">Baseado na disponibilidade pré-cadastrada pela instituição, o voluntário seleciona um horário.</p>
                            </div>

                            <div class="relative pl-8 cursor-pointer group opacity-60 hover:opacity-100 transition-opacity" onclick="highlightStep(this)">
                                <div class="absolute w-8 h-8 bg-gray-300 rounded-full -left-[18px] top-0 flex items-center justify-center text-white font-bold border-4 border-white shadow-sm transition-transform group-hover:scale-110">3</div>
                                <h4 class="font-bold text-lg text-gray-700">Aprovação Administrativa</h4>
                                <p class="text-sm text-gray-600 mt-1">O funcionário recebe a notificação no painel de controle e analisa a solicitação (Pendente -> Aprovado).</p>
                            </div>

                            <div class="relative pl-8 cursor-pointer group opacity-60 hover:opacity-100 transition-opacity" onclick="highlightStep(this)">
                                <div class="absolute w-8 h-8 bg-gray-300 rounded-full -left-[18px] top-0 flex items-center justify-center text-white font-bold border-4 border-white shadow-sm transition-transform group-hover:scale-110">4</div>
                                <h4 class="font-bold text-lg text-gray-700">Visita Confirmada</h4>
                                <p class="text-sm text-gray-600 mt-1">Sistema confirma o agendamento e o voluntário realiza a visita, concluindo o ciclo de carinho.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Metrics Dashboard Section -->
        <section id="metricas" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-brand-marrom mb-4">Métricas e Impacto Mensurável</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Um dos objetivos centrais do projeto é gerar dados claros para as instituições. Explore as simulações de impacto abaixo geradas através dos painéis administrativos.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chart 1: Visitas -->
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h3 class="text-xl font-bold text-brand-marrom mb-2 text-center">Taxa de Conversão de Visitas</h3>
                    <p class="text-sm text-gray-500 text-center mb-6">Comparativo entre agendamentos solicitados e visitas efetivamente concluídas.</p>
                    <div class="chart-container">
                        <canvas id="visitasChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Cartas -->
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h3 class="text-xl font-bold text-brand-marrom mb-2 text-center">Volume de Cartas Trocadas</h3>
                    <p class="text-sm text-gray-500 text-center mb-6">Crescimento do engajamento digital através do sistema de correspondência.</p>
                    <div class="chart-container">
                        <canvas id="cartasChart"></canvas>
                    </div>
                </div>
            </div>
           
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-brand-creme p-6 rounded-xl text-center border-b-4 border-brand-roxo">
                    <div class="text-4xl mb-2">📊</div>
                    <div class="text-3xl font-black text-brand-marrom" id="kpi-visitas">0%</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Comparecimento</div>
                </div>
                <div class="bg-brand-creme p-6 rounded-xl text-center border-b-4 border-brand-roxo">
                    <div class="text-4xl mb-2">⏱️</div>
                    <div class="text-3xl font-black text-brand-marrom" id="kpi-tempo">0h</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Tempo Médio Resposta</div>
                </div>
                <div class="bg-brand-creme p-6 rounded-xl text-center border-b-4 border-brand-roxo">
                    <div class="text-4xl mb-2">💌</div>
                    <div class="text-3xl font-black text-brand-marrom" id="kpi-cartas">0</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Cartas Enviadas/Mês</div>
                </div>
            </div>
        </section>

        <!-- Tech & Conclusion Section -->
        <section id="tecnologia" class="bg-white border-t border-gray-200 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row gap-12">
                    <!-- Tech Stack -->
                    <div class="w-full md:w-1/2">
                        <h2 class="text-3xl font-bold text-brand-marrom mb-6">Bastidores Técnicos</h2>
                        <p class="text-gray-600 mb-8">A plataforma foi desenvolvida aplicando arquitetura limpa e tecnologias de mercado, garantindo segurança e escalabilidade.</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg flex items-center gap-3 border border-gray-100">
                                <span class="text-2xl">🐘</span>
                                <div>
                                    <p class="font-bold text-gray-800">PHP 8.x</p>
                                    <p class="text-xs text-gray-500">Backend Lógico</p>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg flex items-center gap-3 border border-gray-100">
                                <span class="text-2xl">🗄️</span>
                                <div>
                                    <p class="font-bold text-gray-800">MySQL</p>
                                    <p class="text-xs text-gray-500">Banco Relacional</p>
                                </div>
                            </div>
                           
                            <div class="p-4 bg-gray-50 rounded-lg flex items-center gap-3 border border-gray-100">
                                <span class="text-2xl">⚙️</span>
                                <div>
                                    <p class="font-bold text-gray-800">Scrum & Git</p>
                                    <p class="text-xs text-gray-500">Metodologia Ágil</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conclusion & Learnings -->
                    <div class="w-full md:w-1/2">
                        <h2 class="text-3xl font-bold text-brand-marrom mb-6">Conclusão e Visão de Futuro</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <h4 class="font-bold text-brand-roxo flex items-center gap-2"><span class="text-xl">🏆</span> Desafios Superados</h4>
                                <p class="text-gray-600 text-sm mt-1">Gestão segura de sessões de usuário com múltiplos perfis. Resolução de conflitos de rotas em servidores locais (XAMPP) vs. Nuvem (InfinityFree). Elaboração de consultas SQL complexas para evitar dados duplicados em agendas.</p>
                            </div>
                            
                            <div>
                                <h4 class="font-bold text-brand-roxo flex items-center gap-2"><span class="text-xl">💡</span> Aprendizados</h4>
                                <p class="text-gray-600 text-sm mt-1">A extrema importância da empatia no <b>UX Design</b>: entender que a interface do funcionário precisa ser rápida e funcional, enquanto a do voluntário precisa ser acolhedora e narrativa.</p>
                            </div>

                            <div class="p-4 bg-brand-creme rounded-xl border border-brand-laranja/30">
                                <h4 class="font-bold text-brand-marrom flex items-center gap-2"><span class="text-xl">🚀</span> Próximos Passos</h4>
                                <ul class="list-disc pl-5 text-sm text-gray-700 mt-2 space-y-1">
                                    <li>Módulo de IA para tradução de cartas (Voz para Texto) focando em acessibilidade.</li>
                                    <li>Integração com Google Maps para visualização geográfica das ILPIs.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
        
    </main>
    <section class="hero-container">
        
        <div class="hero-image">
            <img src="<?= BASE_URL ?>/assets/img/imagemBanner.png" alt="Idoso e neta lendo">
        </div>
    
        <div class="hero-content">
            <h1>Conectando gerações com carinho</h1>
            <p>Encontre companhia, compartilhe histórias e faça a diferença na vida de quem tem muito a ensinar.</p>
            <a href="#" class="btn-hero">Saiba Mais</a>
        </div>
    
    </section>
    
   
    <!-- JavaScript Application Logic -->
    <script>
        // --- Tab Navigation Logic ---
        function switchTab(tabId, btnElement) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-brand-roxo', 'text-white', 'border-brand-roxo');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
            });
            
            // Show selected content
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('block');
            
            // Highlight selected button
            btnElement.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
            btnElement.classList.add('bg-brand-roxo', 'text-white', 'border-brand-roxo');
        }

        // --- Interactive Flow Stepper Logic ---
        function highlightStep(element) {
            // Reset all steps opacity and styling
            const steps = element.parentElement.children;
            for(let i=0; i<steps.length; i++) {
                steps[i].classList.add('opacity-60');
                steps[i].querySelector('div').classList.remove('bg-brand-roxo');
                steps[i].querySelector('div').classList.add('bg-gray-300');
                steps[i].querySelector('h4').classList.remove('text-brand-roxo');
                steps[i].querySelector('h4').classList.add('text-gray-700');
            }
            
            // Highlight clicked step
            element.classList.remove('opacity-60');
            element.querySelector('div').classList.remove('bg-gray-300');
            element.querySelector('div').classList.add('bg-brand-roxo');
            element.querySelector('h4').classList.remove('text-gray-700');
            element.querySelector('h4').classList.add('text-brand-roxo');
        }

        // --- Chart.js Implementations ---
        document.addEventListener('DOMContentLoaded', function() {
            
            // Common Chart Options for Responsiveness
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'Inter' } } },
                    tooltip: {
                        backgroundColor: '#5B3A26',
                        titleFont: { family: 'Inter', size: 14 },
                        bodyFont: { family: 'Inter', size: 13 },
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            };

            // 1. Visitas Chart (Bar)
            const canvasVisitas = document.getElementById('visitasChart');
            if (canvasVisitas) {
                new Chart(canvasVisitas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                        datasets: [
                            {
                                label: 'Solicitadas',
                                data: [45, 60, 85, 110, 150, 180],
                                backgroundColor: '#E5E7EB', // Gray
                                borderRadius: 4
                            },
                            {
                                label: 'Concluídas',
                                data: [30, 48, 75, 95, 135, 170],
                                backgroundColor: '#6A1B9A', // Brand Roxo
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 2. Cartas Chart (Line)
            const canvasCartas = document.getElementById('cartasChart');
            if (canvasCartas) {
                new Chart(canvasCartas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                        datasets: [{
                            label: 'Cartas Trocadas Mensalmente',
                            data: [15, 28, 55, 102, 160, 240],
                            borderColor: '#ebb860', // Brand Laranja
                            backgroundColor: 'rgba(235, 184, 96, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#5B3A26',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true,
                            tension: 0.4 // Smooth curves
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // --- Simple Number Animation for KPIs ---
            function animateValue(id, start, end, duration, suffix) {
                let obj = document.getElementById(id);
                if (!obj) return;
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    obj.innerHTML = Math.floor(progress * (end - start) + start) + suffix;
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // Animate KPIs when section comes into view (simple implementation)
            let animated = false;
            window.addEventListener('scroll', () => {
                const metricSection = document.getElementById('metricas');
                if(!animated && metricSection && window.scrollY + window.innerHeight > metricSection.offsetTop + 200) {
                    animateValue("kpi-visitas", 0, 92, 1500, "%");
                    animateValue("kpi-tempo", 0, 4, 1500, "h");
                    animateValue("kpi-cartas", 0, 240, 1500, "");
                    animated = true;
                }
            });
        });

        // --- Simulated Video Player Logic ---
        const videoScript = [
            { time: 0, text: "Você um dia vai envelhecer.", img: "https://images.unsplash.com/photo-1516307365426-bea591f05011?auto=format&fit=crop&w=1200&q=80" },
            { time: 4, text: "Já parou para pensar como será esse momento?", img: null },
            { time: 8, text: "Hoje, milhares de idosos vivem em casas de acolhimento...", img: "https://images.unsplash.com/photo-1573497620053-ea5300f94f21?auto=format&fit=crop&w=1200&q=80" },
            { time: 13, text: "esperando por uma visita. Por uma conversa. Por um simples sorriso.", img: "https://images.unsplash.com/photo-1529156069898-49953eb1f5bc?auto=format&fit=crop&w=1200&q=80" },
            { time: 19, text: "O aplicativo Abrace um Idoso foi criado para mudar isso.", img: "https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?auto=format&fit=crop&w=1200&q=80" },
            { time: 24, text: "Ele conecta voluntários a idosos que precisam de atenção, cuidado...", img: null },
            { time: 30, text: "e, muitas vezes, apenas de alguém que os escute.", img: "https://images.unsplash.com/photo-1531496730074-83b638c0a7ac?auto=format&fit=crop&w=1200&q=80" },
            { time: 36, text: "Juntos, podemos criar uma cultura de visitação e apoio a quem já tanto contribuiu com a nossa sociedade.", img: null },
            { time: 44, text: "Porque um dia, esse idoso pode ser você.", img: "https://images.unsplash.com/photo-1516307365426-bea591f05011?auto=format&fit=crop&w=1200&q=80" },
            { time: 49, text: "Faça parte dessa corrente do bem. Baixe o aplicativo.", img: "https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?auto=format&fit=crop&w=1200&q=80" },
            { time: 54, text: "Abrace um idoso hoje.", img: "https://images.unsplash.com/photo-1573497620053-ea5300f94f21?auto=format&fit=crop&w=1200&q=80" },
            { time: 58, text: "", img: null }
        ];

        let vidInterval;
        let vidTime = 0;
        let isPlaying = false;
        let currentBg = 1;

        function toggleVideo() {
            const overlay = document.getElementById('vid-play-overlay');
            const icon = document.getElementById('vid-play-icon');
            const text = document.getElementById('vid-play-text');
            const subtitleEl = document.getElementById('vid-subtitles');
            const bgMusic = document.getElementById('bg-music'); // Get the audio element
            
            if (isPlaying) {
                // Pause
                clearInterval(vidInterval);
                isPlaying = false;
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                icon.innerText = "▶";
                text.innerText = "Vídeo Pausado - Continuar";
                subtitleEl.classList.remove('opacity-100');
                subtitleEl.classList.add('opacity-0');
                bgMusic.pause(); // Pause music
            } else {
                // Play
                if (vidTime >= 58) vidTime = 0; // Reset if ended
                isPlaying = true;
                overlay.classList.add('opacity-0', 'pointer-events-none');
                bgMusic.play(); // Play music
                
                // Force update immediately on play
                updateVideo(vidTime);
                
                vidInterval = setInterval(() => {
                    vidTime += 0.1;
                    updateVideo(vidTime);
                    
                    if (vidTime >= 60) {
                        // Stop at end
                        clearInterval(vidInterval);
                        isPlaying = false;
                        vidTime = 0;
                        document.getElementById('vid-progress').style.width = '0%';
                        subtitleEl.classList.remove('opacity-100');
                        subtitleEl.classList.add('opacity-0');
                        icon.innerText = "▶";
                        text.innerText = "Assistir Novamente";
                        overlay.classList.remove('opacity-0', 'pointer-events-none');
                        bgMusic.pause(); // Stop music at end
                        bgMusic.currentTime = 0; // Reset music track
                    }
                }, 100);
            }
        }

        function updateVideo(time) {
            // Progress bar (60 seconds)
            const progress = (time / 60) * 100;
            document.getElementById('vid-progress').style.width = `${progress}%`;

            // Find current script segment
            let currentSegment = null;
            for (let i = videoScript.length - 1; i >= 0; i--) {
                if (time >= videoScript[i].time) {
                    currentSegment = videoScript[i];
                    break;
                }
            }

            if (currentSegment) {
                const subtitleEl = document.getElementById('vid-subtitles');
                
                // Subtitles
                if (subtitleEl.innerText !== currentSegment.text && currentSegment.text !== undefined) {
                    if (currentSegment.text === "") {
                        subtitleEl.classList.remove('opacity-100');
                        subtitleEl.classList.add('opacity-0');
                    } else {
                        subtitleEl.innerText = currentSegment.text;
                        subtitleEl.classList.remove('opacity-0');
                        subtitleEl.classList.add('opacity-100');
                    }
                }

                // Image Crossfade
                if (currentSegment.img) {
                    const nextBg = currentBg === 1 ? 2 : 1;
                    const nextEl = document.getElementById(`vid-bg-${nextBg}`);
                    const currentEl = document.getElementById(`vid-bg-${currentBg}`);
                    
                    // Only trigger crossfade if the image actually changes
                    if (!nextEl.style.backgroundImage.includes(currentSegment.img) && !currentEl.style.backgroundImage.includes(currentSegment.img)) {
                        nextEl.style.backgroundImage = `url('${currentSegment.img}')`;
                        nextEl.classList.remove('opacity-0');
                        nextEl.classList.add('opacity-60');
                        
                        currentEl.classList.remove('opacity-60');
                        currentEl.classList.add('opacity-0');
                        
                        currentBg = nextBg;
                    }
                }
            }
        }
    </script>


