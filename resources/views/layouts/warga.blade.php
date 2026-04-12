<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warga') - Smart Kas RT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', 'Segoe UI', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 99px; }
        .sidebar-scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.15) transparent; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px;
            font-size: 13.5px; font-weight: 500; color: #94a3b8;
            transition: all 0.18s ease; text-decoration: none; position: relative;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%;
            transform: translateY(-50%); width: 3px; height: 60%;
            background: #34d399; border-radius: 0 4px 4px 0;
        }
        .nav-item svg { flex-shrink: 0; opacity: 0.7; }
        .nav-item.active svg, .nav-item:hover svg { opacity: 1; }
        .nav-label { font-size: 10px; font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: #94a3b8; padding: 16px 14px 6px; }
        .logout-btn {
            display: flex; align-items: center; gap: 10px; padding: 10px 14px;
            border-radius: 10px; font-size: 13.5px; font-weight: 500; color: #f87171;
            transition: all 0.18s ease; width: 100%; background: transparent; border: none; cursor: pointer;
        }
        .logout-btn:hover { background: rgba(248,113,113,0.12); color: #fca5a5; }
    </style>
</head>
<body class="bg-slate-100">
<div class="flex h-screen overflow-hidden">

    {{-- OVERLAY MOBILE --}}
    <div id="sidebarOverlay"
        class="fixed inset-0 bg-black/50 z-40 hidden"
        onclick="closeSidebar()"></div>

    {{-- SIDEBAR WARGA --}}
    <aside id="sidebar"
        class="fixed lg:relative inset-y-0 left-0 w-64 lg:w-60 flex-shrink-0 flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300"
        style="background: linear-gradient(180deg, #064e3b 0%, #065f46 50%, #047857 100%);">

        <div class="px-5 pt-6 pb-5 flex items-center justify-between">
            <a href="{{ route('warga.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0 overflow-hidden bg-white text-emerald-600 font-extrabold text-lg relative">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute inset-0 w-full h-full object-cover z-10"
                        onerror="this.style.opacity='0'">
                </div>
                <div>
                    <h1 class="text-white font-bold text-base leading-tight">Smart Kas RT</h1>
                    <p class="text-emerald-300 text-xs">Portal Warga</p>
                </div>
            </a>
            <button onclick="closeSidebar()" class="lg:hidden text-white/60 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="mx-5 h-px bg-white/10 mb-2"></div>

        <nav class="flex-1 px-3 overflow-y-auto pb-2 sidebar-scroll">
            <p class="nav-label">Menu</p>

            <a href="{{ route('warga.dashboard') }}"
               class="nav-item {{ request()->routeIs('warga.dashboard') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('warga.iuran') }}"
               class="nav-item {{ request()->routeIs('warga.iuran') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tagihan Iuran
            </a>


            <a href="{{ route('bayar') }}"
               class="nav-item {{ request()->routeIs('bayar*') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Bayar Iuran
            </a>

            <a href="{{ route('warga.riwayat') }}"
               class="nav-item {{ request()->routeIs('warga.riwayat') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Pembayaran
            </a>

            <a href="{{ route('notifikasi.index') }}"
               class="nav-item {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifikasi
                <span id="sidebarNotifBadge" class="ml-auto text-xs bg-red-500 text-white px-1.5 py-0.5 rounded-full font-semibold hidden">0</span>
            </a>

            <a href="{{ route('warga.profil') }}"
               class="nav-item {{ request()->routeIs('warga.profil') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Profil Saya
            </a>

            <a href="{{ route('warga.chat') }}"
               class="nav-item {{ request()->routeIs('warga.chat') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Hubungi Admin
                <span id="wargaChatBadge" class="ml-auto text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-full font-black hidden">0</span>
            </a>
        </nav>

        <div class="mx-5 h-px bg-white/10"></div>

        <div class="px-3 py-4 space-y-2">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 overflow-hidden">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.outerHTML='{{ strtoupper(substr(auth()->user()->name ?? "W", 0, 1)) }}'">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'W', 0, 1)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Warga' }}</p>
                    <p class="text-emerald-300 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            <a href="{{ route('logout.get') }}" class="logout-btn"
                onclick="event.preventDefault(); if(confirm('Yakin ingin keluar?')) window.location.href=this.href;">
                <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-4 lg:px-6 py-3.5 flex items-center justify-between flex-shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                {{-- Hamburger mobile --}}
                <button onclick="openSidebar()" class="lg:hidden p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-base font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Smart Kas RT › @yield('page-title', 'Dashboard')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Notif --}}
                <div class="relative" id="notifWrapper">
                    <button onclick="toggleNotif()" class="relative p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notifBadge" class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-xs rounded-full items-center justify-center font-bold hidden">0</span>
                    </button>
                    <div id="notifDropdown" class="hidden absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <h4 class="font-semibold text-gray-800 text-sm">Notifikasi</h4>
                            <form method="POST" action="{{ route('notifikasi.baca-semua') }}" id="bacaSemuaForm" class="hidden">@csrf</form>
                            <button onclick="document.getElementById('bacaSemuaForm').submit()" class="text-xs text-emerald-600 hover:underline">Tandai dibaca</button>
                        </div>
                        <div id="notifList" class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                            <div class="px-4 py-6 text-center text-sm text-gray-400">Memuat...</div>
                        </div>
                        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                            <a href="{{ route('notifikasi.index') }}" class="block text-center text-xs text-emerald-600 font-medium hover:underline">Lihat semua</a>
                        </div>
                    </div>
                </div>
                {{-- Avatar --}}
                <div class="flex items-center gap-2 pl-2 border-l border-gray-100">
                    {{-- Foto/avatar → lightbox full --}}
                    <button onclick="openAvatarFull()" class="flex-shrink-0 focus:outline-none">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-bold overflow-hidden ring-2 ring-transparent hover:ring-emerald-300 transition-all">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.outerHTML='{{ strtoupper(substr(auth()->user()->name ?? "W", 0, 1)) }}'">
                            @else
                                {{ strtoupper(substr(auth()->user()->name ?? 'W', 0, 1)) }}
                            @endif
                        </div>
                    </button>
                    {{-- Nama → halaman profil --}}
                    <a href="{{ route('warga.profil') }}" class="hidden sm:block hover:opacity-80 transition-opacity">
                        <p class="text-sm font-medium text-gray-700 leading-tight">{{ auth()->user()->name ?? 'Warga' }}</p>
                        <p class="text-xs text-gray-400 leading-tight">Warga RT</p>
                    </a>
                </div>

                {{-- Lightbox avatar full --}}
                <div id="avatarLightbox" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/80 backdrop-blur-sm" onclick="closeAvatarFull()">
                    <div class="relative" onclick="event.stopPropagation()">
                        <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-white shadow-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'text-white font-bold\' style=\'font-size:72px\'>{{ strtoupper(substr(auth()->user()->name ?? "W", 0, 1)) }}</span>'">
                            @else
                                <span class="text-white font-bold" style="font-size:72px">{{ strtoupper(substr(auth()->user()->name ?? 'W', 0, 1)) }}</span>
                            @endif
                        </div>
                        <p class="text-white text-center font-semibold mt-3">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-center text-sm">Warga RT</p>
                        <button onclick="closeAvatarFull()" class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 lg:p-6">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
<script>
const notifSound = new Audio('https://cdn.pixabay.com/audio/2022/03/15/audio_78330a8776.mp3');

const iconMap = {
    sukses:    { bg:'bg-green-100', color:'text-green-600', path:'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
    peringatan:{ bg:'bg-orange-100', color:'text-orange-500', path:'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z' },
    info:      { bg:'bg-blue-100', color:'text-blue-600', path:'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
};

function timeAgo(d) {
    const s = Math.floor((Date.now()-new Date(d))/1000);
    if(s<60) return s+' detik lalu';
    if(s<3600) return Math.floor(s/60)+' menit lalu';
    if(s<86400) return Math.floor(s/3600)+' jam lalu';
    return Math.floor(s/86400)+' hari lalu';
}

async function updateBadges() {
    try {
        // Notifikasi Dropdown & Badge
        const resNotif = await fetch('{{ route("notifikasi.dropdown", [], false) }}', {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        if (!resNotif.ok) throw new Error('Notif fetch failed');
        const dataNotif = await resNotif.json();
        
        const badgeNotif = document.getElementById('notifBadge');
        const sideBadgeNotif = document.getElementById('sidebarNotifBadge');
        
        if(dataNotif.belum_dibaca > 0) {
            const txt = dataNotif.belum_dibaca > 9 ? '9+' : dataNotif.belum_dibaca;
            if(badgeNotif) { badgeNotif.textContent = txt; badgeNotif.classList.remove('hidden'); badgeNotif.classList.add('flex'); }
            if(sideBadgeNotif) { sideBadgeNotif.textContent = txt; sideBadgeNotif.classList.remove('hidden'); }
            
            // Putar suara jika ada notifikasi baru
            if (typeof lastUnreadCount !== 'undefined' && dataNotif.belum_dibaca > lastUnreadCount) {
                notifSound.play().catch(e => console.log('Autoplay blocked'));
            }
        } else {
            if(badgeNotif) { badgeNotif.classList.add('hidden'); badgeNotif.classList.remove('flex'); }
            if(sideBadgeNotif) { sideBadgeNotif.classList.add('hidden'); }
        }
        window.lastUnreadCount = dataNotif.belum_dibaca;

        // Render list notifikasi jika dropdown terbuka
        const dd = document.getElementById('notifDropdown');
        if (dd && !dd.classList.contains('hidden')) {
            const list = document.getElementById('notifList');
            if(!dataNotif.items || !dataNotif.items.length) { 
                list.innerHTML='<div class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada notifikasi</div>'; 
            } else {
                list.innerHTML = dataNotif.items.map(n => {
                    const ic = iconMap[n.tipe]||iconMap.info;
                    const unread = !n.dibaca_at;
                    const isReminder = n.tipe === 'peringatan';
                    const link = isReminder ? '{{ route("bayar") }}' : '{{ route("notifikasi.index") }}';
                    
                    return `<a href="${link}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-all ${unread?'bg-blue-50/40':''}">
                        <div class="w-8 h-8 rounded-xl ${ic.bg} flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 ${ic.color}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${ic.path}"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm ${unread?'font-bold text-gray-900':'font-medium text-gray-700'} truncate">${n.judul}</p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">${n.pesan}</p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">${timeAgo(n.created_at)}</p>
                                ${isReminder ? '<span class="text-[9px] font-bold text-orange-600 bg-orange-100 px-1.5 py-0.5 rounded uppercase">Bayar &rsaquo;</span>' : ''}
                            </div>
                        </div>
                    </a>`;
                }).join('');

            }
        }

        // Chat Badge
        const resChat = await fetch('{{ route('chat.unread', [], false) }}', {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        if (!resChat.ok) throw new Error('Chat fetch failed');
        const dataChat = await resChat.json();
        const badgeChat = document.getElementById('wargaChatBadge');
        if (badgeChat) {
            if (dataChat.count > 0) {
                badgeChat.innerText = dataChat.count > 9 ? '9+' : dataChat.count;
                badgeChat.classList.remove('hidden');

                // Putar suara jika ada pesan chat baru
                if (typeof lastChatCount !== 'undefined' && dataChat.count > lastChatCount) {
                    notifSound.play().catch(e => console.log('Autoplay blocked'));
                }
            } else {
                badgeChat.classList.add('hidden');
            }
            window.lastChatCount = dataChat.count;
        }
    } catch(e){
        console.warn('Polling background error:', e);
    }
}

function toggleNotif() {
    const dd = document.getElementById('notifDropdown');
    dd.classList.toggle('hidden');
    if(!dd.classList.contains('hidden')) updateBadges();
}

function openSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.add('hidden');
    document.body.style.overflow = '';
}

function openAvatarFull() {
    const lb = document.getElementById('avatarLightbox');
    lb.classList.remove('hidden'); lb.classList.add('flex');
}

function closeAvatarFull() {
    const lb = document.getElementById('avatarLightbox');
    lb.classList.add('hidden'); lb.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', () => {
    updateBadges();
    
    document.querySelectorAll('#sidebar .nav-item').forEach(el => {
        el.addEventListener('click', () => { if (window.innerWidth < 1024) closeSidebar(); });
    });

    document.addEventListener('click', e => {
        const w = document.getElementById('notifWrapper');
        if(w && !w.contains(e.target)) document.getElementById('notifDropdown').classList.add('hidden');
    });

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAvatarFull(); });

    // Polling setiap 30 detik
    setInterval(updateBadges, 30000);
});
</script>
</body>
</html>

