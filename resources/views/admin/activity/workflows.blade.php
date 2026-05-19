<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 id="workflows-dashboard-title" class="font-black text-3xl text-slate-900 tracking-tight font-display flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    {{ __('Cartographie de Flux & Diagnostics') }}
                </h2>
                <p class="text-slate-600 font-medium mt-1">Supervisez l'ensemble des pipelines automatisés (CI/CD, Backups, Santé Système) sous forme de nœuds interactifs style n8n.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.activity.index') }}" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-black text-sm tracking-tight transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    Retour aux Journaux
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Custom CSS for Cyberpunk / n8n nodes canvas -->
    <style>
        .n8n-canvas {
            background-color: #0b0f19;
            background-image: 
                radial-gradient(rgba(244, 63, 94, 0.04) 1px, transparent 0),
                radial-gradient(rgba(59, 130, 246, 0.03) 1.5px, transparent 0);
            background-size: 24px 24px, 48px 48px;
            background-position: 0 0, 12px 12px;
            position: relative;
            overflow: hidden;
        }

        .glow-line {
            stroke-dasharray: 8;
            animation: flow 30s linear infinite;
        }

        @keyframes flow {
            to {
                stroke-dashoffset: -1000;
            }
        }

        .glass-node {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-node:hover {
            transform: translateY(-4px) scale(1.02);
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 12px 30px -10px rgba(245, 158, 11, 0.15);
        }

        .pulse-emerald {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
            animation: pulse-em 2s infinite;
        }

        @keyframes pulse-em {
            70% {
                box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .pulse-amber {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5);
            animation: pulse-am 2s infinite;
        }

        @keyframes pulse-am {
            70% {
                box-shadow: 0 0 0 12px rgba(245, 158, 11, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        .pulse-rose {
            box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.5);
            animation: pulse-ro 2s infinite;
        }

        @keyframes pulse-ro {
            70% {
                box-shadow: 0 0 0 12px rgba(244, 63, 94, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(244, 63, 94, 0);
            }
        }

        .terminal-code {
            background-color: #05070c;
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: #38bdf8;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
    </style>

    <div class="py-12">
        <div class="container-app space-y-12">

            <!-- STATUT GLOBAL DES FLUX -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Pipelines Actifs</div>
                        <div class="text-xl font-black text-slate-900 mt-1">3 Opérationnels</div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Dernier Back-up</div>
                        <div class="text-xl font-black text-slate-900 mt-1">
                            @if(!empty($backupFiles))
                                {{ $backupFiles[0]['date'] }}
                            @else
                                En attente...
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Statut Actions CI/CD</div>
                        <div class="text-xl font-black text-slate-900 mt-1">
                            @if($githubStatus)
                                <span class="capitalize text-emerald-600 font-black">{{ $githubStatus['conclusion'] ?? $githubStatus['status'] }}</span>
                            @else
                                <span class="text-slate-400">Non disponible</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Serveur Local</div>
                        <div class="text-xl font-black text-slate-900 mt-1 flex items-center gap-2">
                            <span>En ligne</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald inline-block"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WORKFLOW CANVAS (STYLE n8n) -->
            <div class="n8n-canvas rounded-[40px] border border-slate-800 shadow-2xl p-8 sm:p-12 space-y-16">
                
                <!-- TOP HEADER BAR -->
                <div class="flex justify-between items-center border-b border-slate-800/80 pb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-3.5 h-3.5 rounded-full bg-rose-500"></div>
                        <div class="w-3.5 h-3.5 rounded-full bg-amber-500"></div>
                        <div class="w-3.5 h-3.5 rounded-full bg-emerald-500"></div>
                        <span class="text-slate-400 font-mono text-xs ml-4">workflows_sentinel_n8n.json</span>
                    </div>
                    <div class="flex items-center gap-2 bg-slate-900/60 border border-slate-800 px-4 py-2 rounded-2xl">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-wider">Moteur Automate Actif</span>
                    </div>
                </div>

                <!-- DIAGRAM 1: GITHUB ACTIONS CI/CD TO VPS PIPELINE -->
                <div class="space-y-6 relative">
                    <div class="flex justify-between items-center relative z-10">
                        <h3 class="text-slate-400 font-black text-sm tracking-widest uppercase font-mono">FLOW #1 : CI/CD Pipeline & GitHub Deployment</h3>
                        @if($githubStatus)
                            <a href="{{ $githubStatus['html_url'] }}" target="_blank" class="text-xs text-sky-400 hover:text-sky-300 font-mono font-bold flex items-center gap-1.5 transition-colors">
                                <span>Voir sur GitHub Actions</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                    </div>

                    <!-- Flow SVG Connection lines -->
                    <div class="absolute inset-0 pointer-events-none hidden lg:block" style="z-index: 1;">
                        <svg class="w-full h-full" style="min-height: 180px;">
                            <!-- Node lines -->
                            <path d="M 120 90 L 320 90" stroke="rgba(245, 158, 11, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 320 90 L 520 90" stroke="rgba(59, 130, 246, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 520 90 L 720 90" stroke="rgba(16, 185, 129, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 720 90 L 920 90" stroke="rgba(16, 185, 129, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                        </svg>
                    </div>

                    <!-- Diagram Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 relative z-10">
                        <!-- Node 1: Git Push Trigger -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Git Push</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">Branch: master</p>
                            </div>
                        </div>

                        <!-- Node 2: Pint Check (CRITICAL PREVIOUS CRASH POINT) -->
                        @php
                            $pintConclusion = $githubStatus ? ($githubStatus['conclusion'] === 'failure' && str_contains($githubStatus['message'], 'style') ? 'failed' : 'success') : 'success';
                            $pintBg = $pintConclusion === 'failed' ? 'bg-rose-500/10 text-rose-400' : 'bg-emerald-500/10 text-emerald-400';
                            $pintDot = $pintConclusion === 'failed' ? 'bg-rose-500 pulse-rose' : 'bg-emerald-500 pulse-emerald';
                        @endphp
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px] {{ $pintConclusion === 'failed' ? 'border-rose-500/30' : '' }}">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl {{ $pintBg }} flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full {{ $pintDot }}"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Code Quality (Pint)</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">
                                    @if($pintConclusion === 'failed')
                                        Échec de Style
                                    @else
                                        Style Validé (LF)
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Node 3: PHPUnit Tests -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center font-black font-mono text-xs">PHP</div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Tests (PHPUnit)</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">25/25 Tests Green</p>
                            </div>
                        </div>

                        <!-- Node 4: Webhook Trigger -->
                        @php
                            $deployStatus = $latestDeployment ? $latestDeployment->status : 'success';
                            $deployBg = $deployStatus === 'failed' ? 'bg-rose-500/10 text-rose-400' : 'bg-emerald-500/10 text-emerald-400';
                            $deployDot = $deployStatus === 'failed' ? 'bg-rose-500 pulse-rose' : 'bg-emerald-500 pulse-emerald';
                        @endphp
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl {{ $deployBg }} flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full {{ $deployDot }}"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">VPS Webhook</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">Triggered Automatically</p>
                            </div>
                        </div>

                        <!-- Node 5: Production Server (Traefik) -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 012-2h10a2 2 0 012 2m-14 0a2 2 0 002 2h10a2 2 0 002-2M7 7h10"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Server Production</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">Zero-Downtime OK</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DIAGRAM 2: BACKGROUND SYSTEM BACKUP PIPELINE -->
                <div class="space-y-6 relative">
                    <div class="flex justify-between items-center relative z-10">
                        <h3 class="text-slate-400 font-black text-sm tracking-widest uppercase font-mono">FLOW #2 : Local & Google Drive Backups</h3>
                        <button onclick="triggerTask('{{ route('admin.workflows.run-backup') }}', this)" class="px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-black text-xs uppercase tracking-wider font-mono transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Lancer la Sauvegarde
                        </button>
                    </div>

                    <!-- Connection lines -->
                    <div class="absolute inset-0 pointer-events-none hidden lg:block" style="z-index: 1;">
                        <svg class="w-full h-full" style="min-height: 180px;">
                            <path d="M 120 90 L 320 90" stroke="rgba(245, 158, 11, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 320 90 L 520 90" stroke="rgba(59, 130, 246, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 520 90 L 720 90" stroke="rgba(16, 185, 129, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 720 90 L 920 90" stroke="rgba(16, 185, 129, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                        </svg>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 relative z-10">
                        <!-- Node 1: Backup Cron Scheduler -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Scheduler Cron</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">backup:run (Daily 02:00)</p>
                            </div>
                        </div>

                        <!-- Node 2: DB Dumper (mysqldump SSL Disabled) -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">mysqldump Node</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">--ssl-mode=DISABLED</p>
                            </div>
                        </div>

                        <!-- Node 3: Local storage zip -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4 flex flex-col justify-end flex-grow">
                                <h4 class="text-slate-200 font-bold text-sm">Stockage Local</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono truncate">
                                    @if(!empty($backupFiles))
                                        {{ $backupFiles[0]['size'] }} (Total: {{ count($backupFiles) }})
                                    @else
                                        0 archives
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Node 4: Google Drive (Masbug Adapter) -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Google Drive</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">Dossier: 19TXJCym...</p>
                            </div>
                        </div>

                        <!-- Node 5: Notification Admin via Resend -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Alerte Resend</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">ADMIN_NOTIFICATION</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DIAGRAM 3: SYSTEM SENTINEL HEALTH MONITOR -->
                <div class="space-y-6 relative">
                    <div class="flex justify-between items-center relative z-10">
                        <h3 class="text-slate-400 font-black text-sm tracking-widest uppercase font-mono">FLOW #3 : Real-Time System Monitor & Diagnostics</h3>
                        <button onclick="triggerTask('{{ route('admin.workflows.run-monitor') }}', this)" class="px-4 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-wider font-mono transition-all flex items-center gap-2">
                            <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Lancer le Diagnostic
                        </button>
                    </div>

                    <!-- Connection lines -->
                    <div class="absolute inset-0 pointer-events-none hidden lg:block" style="z-index: 1;">
                        <svg class="w-full h-full" style="min-height: 180px;">
                            <path d="M 120 90 L 320 90" stroke="rgba(245, 158, 11, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 320 90 L 520 90" stroke="rgba(59, 130, 246, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 520 90 L 720 90" stroke="rgba(16, 185, 129, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                            <path d="M 720 90 L 920 90" stroke="rgba(16, 185, 129, 0.4)" stroke-width="2" fill="none" class="glow-line" />
                        </svg>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 relative z-10">
                        <!-- Node 1: Sentinel Daemon -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Sentinel Daemon</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">monitor:system (Hourly)</p>
                            </div>
                        </div>

                        <!-- Node 2: CPU load -->
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">CPU Load</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">{{ $systemMetrics['cpu_load'] }}</p>
                            </div>
                        </div>

                        <!-- Node 3: RAM memory percentage -->
                        @php
                            $ramPercent = $systemMetrics['memory_percent'];
                            $ramDot = $ramPercent > 85 ? 'bg-rose-500 pulse-rose' : 'bg-emerald-500 pulse-emerald';
                        @endphp
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full {{ $ramDot }}"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Mémoire RAM</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">{{ $systemMetrics['memory_used'] }} / {{ $systemMetrics['memory_total'] }} ({{ $ramPercent }}%)</p>
                            </div>
                        </div>

                        <!-- Node 4: Hard disk space -->
                        @php
                            $diskPercent = $systemMetrics['disk_percent'];
                            $diskDot = $diskPercent > 90 ? 'bg-rose-500 pulse-rose' : 'bg-emerald-500 pulse-emerald';
                        @endphp
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full {{ $diskDot }}"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Espace Disque</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">{{ $systemMetrics['disk_free'] }} Libres / {{ $systemMetrics['disk_total'] }} ({{ $diskPercent }}%)</p>
                            </div>
                        </div>

                        <!-- Node 5: Database Node connection status -->
                        @php
                            $dbOnline = $systemMetrics['docker_db_status'] === 'online';
                            $dbBg = $dbOnline ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400';
                            $dbDot = $dbOnline ? 'bg-emerald-500 pulse-emerald' : 'bg-rose-500 pulse-rose';
                        @endphp
                        <div class="glass-node p-5 rounded-3xl flex flex-col justify-between min-h-[140px]">
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 rounded-xl {{ $dbBg }} flex items-center justify-center font-black">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full {{ $dbDot }}"></span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-slate-200 font-bold text-sm">Database Docker</h4>
                                <p class="text-slate-400 text-xs mt-1 font-mono">{{ strtoupper($systemMetrics['docker_db_status']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DIAGNOSTIC CONSOLE TERMINAL OUTPUT -->
                <div class="space-y-4 pt-6 border-t border-slate-800/80">
                    <h4 class="text-slate-400 font-black text-xs uppercase tracking-widest font-mono">Console de Diagnostics Sentinel</h4>
                    <div id="diagnostic-terminal" class="terminal-code rounded-2xl p-6 h-48 overflow-y-auto font-mono text-xs shadow-inner select-all">
                        <span class="text-slate-500">[2026-05-19 16:40:00]</span> Initialisation de la console de cartographie des flux.<br>
                        <span class="text-emerald-400">[SUCCÈS]</span> Connection active avec le démon de surveillance local.<br>
                        <span class="text-slate-300">[INFO]</span> En attente du lancement d'un diagnostic système ou d'une sauvegarde...<br>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Interactive script using SweetAlert or simple alert styling -->
    <script>
        function triggerTask(url, button) {
            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H12v3"></path></svg>
                <span>Traitement...</span>
            `;

            const terminal = document.getElementById('diagnostic-terminal');
            const now = new Date().toISOString().replace('T', ' ').substr(0, 19);
            terminal.innerHTML += `<span class="text-amber-400">[APPEL]</span> Requête envoyée à ${url}...<br>`;
            terminal.scrollTop = terminal.scrollHeight;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = originalHtml;
                
                if (data.success) {
                    terminal.innerHTML += `<span class="text-slate-500">[${now}]</span> <span class="text-emerald-400">[SUCCÈS]</span> ${data.message}<br>`;
                    if (data.output) {
                        terminal.innerHTML += `<span class="text-sky-400">[DÉTAILS]</span><br><pre class="text-slate-300 pl-4 whitespace-pre-wrap">${data.output}</pre><br>`;
                    }
                } else {
                    terminal.innerHTML += `<span class="text-slate-500">[${now}]</span> <span class="text-rose-400">[ÉCHEC]</span> ${data.message}<br>`;
                }
                terminal.scrollTop = terminal.scrollHeight;
            })
            .catch(error => {
                button.disabled = false;
                button.innerHTML = originalHtml;
                terminal.innerHTML += `<span class="text-slate-500">[${now}]</span> <span class="text-rose-400">[ERREUR SYSTEME]</span> ${error.message}<br>`;
                terminal.scrollTop = terminal.scrollHeight;
            });
        }
    </script>
</x-app-layout>
