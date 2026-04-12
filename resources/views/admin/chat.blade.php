@extends('layouts.admin')
@section('title', 'WhatsApp Chat - Warga')
@section('page-title', 'Pesan & Obrolan Warga')

@section('content')
<style>
    .wa-admin-container { height: calc(100vh - 160px); display: flex; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #ddd; position: relative; }
    .wa-list-panel { width: 350px; flex-shrink: 0; border-right: 1px solid rgba(0,0,0,0.08); display: flex; flex-direction: column; background: #fff; }
    
    @media (max-width: 768px) {
        .wa-admin-container { 
            height: calc(100dvh - 84px); /* dvh accounts for mobile browser address bar */
            border-radius: 0; border: none; 
            margin: -1rem; /* counteract parent padding if exists */
            width: calc(100% + 2rem);
        }
        .wa-list-panel { width: 100%; border: none; }
        .wa-chat-panel { width: 100%; position: absolute; inset: 0; z-index: 50; background: #fff; display: none; }
        .wa-chat-panel.active { display: flex; }
        .wa-list-panel.hidden-mobile { display: none; }
    }

    .wa-list-header { background: #f0f2f5; padding: 12px 16px; border-bottom: 1px solid rgba(0,0,0,0.08); height: 60px; display: flex; align-items: center; justify-content: space-between; }
    .wa-list-body { flex: 1; overflow-y: auto; }
    
    .wa-chat-panel { flex: 1; display: flex; flex-direction: column; background: #f1f5f9; position: relative; }
    .wa-chat-header { background: #ffffff; padding: 8px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; z-index: 10; min-height: 60px; }
    .wa-chat-body { 
        flex: 1; overflow-y: auto; padding: 20px 12px; 
        background-color: #f1f5f9;
        background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
        background-size: 20px 20px;
        display: flex; flex-direction: column; 
    }
    .wa-chat-footer { background: #f0f2f5; padding: 10px 12px; border-top: 1px solid rgba(0,0,0,0.08); }

    .bubble { max-width: 85%; padding: 6px 12px 2px; border-radius: 8px; font-size: 14px; line-height: 1.5; position: relative; margin-bottom: 4px; box-shadow: 0 1px 0.5px rgba(0,0,0,0.13); }
    @media (min-width: 768px) { .bubble { max-width: 65%; } }
    .bubble-in { background: #ffffff; align-self: flex-start; border-top-left-radius: 0; margin-left: 8px; }
    .bubble-out { background: #dcf8c6; align-self: flex-end; border-top-right-radius: 0; margin-right: 8px; }
    .wa-msg-wrapper { display: flex; flex-direction: column; margin-bottom: 8px; }
    .wa-msg-wrapper.mine { align-items: flex-end; }
    .wa-msg-wrapper.theirs { align-items: flex-start; }
    .bubble-in::before { content: ''; position: absolute; left: -8px; top: 0; width: 0; height: 0; border-top: 10px solid #ffffff; border-left: 10px solid transparent; }
    .bubble-out::before { content: ''; position: absolute; right: -8px; top: 0; width: 0; height: 0; border-top: 10px solid #dcf8c6; border-right: 10px solid transparent; }
    .bubble-meta { display: flex; align-items: center; justify-content: flex-end; gap: 4px; margin-top: 2px; }
    .bubble-time { font-size: 11px; color: rgba(0,0,0,0.45); }

    .wa-list-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; transition: background 0.2s; border-bottom: 1px solid rgba(0,0,0,0.04); text-decoration: none; }
    .wa-list-item:hover { background: #f5f6f6; }
    .wa-list-item.active { background: #f0f2f5; }
    
    .wa-input-group { background: #fff; border-radius: 24px; padding: 5px 15px; display: flex; align-items: center; gap: 10px; flex: 1; border: 1px solid transparent; }
    .wa-input-group:focus-within { border-color: #00a884; }
    .wa-input { flex: 1; border: none; outline: none; font-size: 14px; background: transparent; padding: 8px 0; }
    
    .search-highlight { background-color: #ffeb3b; color: #000; padding: 0 2px; border-radius: 2px; font-weight: bold; }

    .btn-icon { color: #54656f; cursor: pointer; background: none; border: none; padding: 6px; display: flex; align-items: center; border-radius: 50%; }
    .btn-icon:hover { background: rgba(0,0,0,0.05); }
    .btn-send { background: none; border: none; color: #54656f; cursor: pointer; display: flex; align-items: center; justify-content: center; }

    /* Media Styles */
    .chat-media { border-radius: 8px; overflow: hidden; margin-bottom: 4px; border: 1px solid rgba(0,0,0,0.05); cursor: pointer; position: relative; }
    .chat-media img { max-width: 100%; height: auto; max-height: 250px; display: block; object-fit: cover; }
    .chat-media video { max-width: 100%; max-height: 250px; display: block; }
    
    #previewArea { display: none; padding: 12px 20px; background: #fff; border-top: 1px solid #f1f5f9; position: relative; }
    .preview-container { display: flex; align-items: center; gap: 12px; }
    .preview-card { position: relative; width: 64px; height: 64px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
    .preview-card img, .preview-card video { width: 100%; height: 100%; object-fit: cover; }
    .preview-remove { position: absolute; top: -6px; right: -6px; background: #ef4444; color: #fff; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 14px; cursor: pointer; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

</style>

<div class="wa-admin-container shadow-2xl animate-fade-in">
    {{-- List Panel --}}
    <div class="wa-list-panel border-r border-gray-100 {{ $activeWarga ? 'hidden-mobile' : '' }}">
        <div class="wa-list-header !bg-white">
            <div class="flex-1 mr-2">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" id="contactSearchInput" placeholder="Cari warga..." 
                        class="w-full bg-gray-100 border-none rounded-lg py-1.5 pl-10 pr-3 text-sm focus:ring-1 focus:ring-emerald-500 focus:bg-white outline-none transition-all">
                </div>
            </div>
            <div class="flex items-center gap-2 text-gray-500">
                <button class="btn-icon"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg></button>
            </div>
        </div>
        <div class="wa-list-body custom-scrollbar">
            @forelse($conversations as $w)
            <a href="{{ route('admin.chat', ['warga_id' => $w->id]) }}" class="wa-list-item {{ $activeWarga?->id === $w->id ? 'active' : '' }}">
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold overflow-hidden flex-shrink-0">
                    @if($w->avatar)
                        <img src="{{ '/storage/'.$w->avatar }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($w->name, 0, 1)) }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-[14px] font-bold text-[#111b21] truncate">{{ $w->name }}</span>
                        @if($w->last_message)
                        <span class="text-[11px] text-gray-500">{{ $w->last_message->created_at->translatedFormat('H:i') }}</span>
                        @endif
                    </div>
                    <p class="text-[13px] text-[#667781] truncate mt-0.5">
                        @if($w->last_message && $w->last_message->file_path)
                            @if($w->last_message->file_type === 'image') 📷 Foto @else 🎥 Video @endif
                        @endif
                        {{ $w->last_message ? $w->last_message->isi_pesan : 'Mulai percakapan' }}
                    </p>
                </div>
                @if($w->unread_count > 0)
                <span class="ml-1 w-5 h-5 bg-[#25d366] text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                    {{ $w->unread_count }}
                </span>
                @endif
            </a>
            @empty
            <div class="p-10 text-center text-gray-400 text-sm">Belum ada percakapan.</div>
            @endforelse
        </div>
    </div>

    {{-- Chat Panel --}}
    <div class="wa-chat-panel {{ $activeWarga ? 'active' : '' }}">
        @if($activeWarga)
        <div class="wa-chat-header">
            <a href="{{ route('admin.chat') }}" class="md:hidden mr-1 p-2 text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if($activeWarga->avatar)
                    <img src="{{ '/storage/'.$activeWarga->avatar }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($activeWarga->name, 0, 1)) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-[15px] font-bold text-[#111b21]">{{ $activeWarga->name }}</h4>
                <p id="partnerStatus" class="text-[11px] {{ ($activeWarga && $activeWarga->isOnline()) ? 'text-emerald-600' : 'text-gray-400' }} font-medium">
                    {{ $activeWarga ? $activeWarga->lastSeenStatus() : 'Offline' }}
                </p>
            </div>
            <div class="flex items-center gap-4 text-gray-500">
                <div id="searchBox" class="hidden animate-fade-in">
                    <input type="text" id="chatSearchInput" placeholder="Cari pesan..." 
                        class="text-xs px-3 py-1.5 rounded-full border border-gray-200 outline-none focus:border-emerald-500 w-32 md:w-48 transition-all">
                </div>
                <button class="btn-icon" onclick="toggleSearch()"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
            </div>
        </div>

        <div id="chatArea" class="wa-chat-body custom-scrollbar">
            @foreach($activeMessages as $msg)
            @php $isMine = $msg->pengirim_id === auth()->id(); @endphp
            <div class="wa-msg-wrapper {{ $isMine ? 'mine' : 'theirs' }}" data-msg-id="{{ $msg->id }}">
                <div class="bubble {{ $isMine ? 'bubble-out' : 'bubble-in' }} animate-slide-up">
                    @if($msg->file_path)
                        <div class="chat-media" onclick="openFullMedia('{{ '/storage/'.$msg->file_path }}', '{{ $msg->file_type }}')">
                            @if($msg->file_type === 'image')
                                <img src="{{ '/storage/'.$msg->file_path }}">
                            @else
                                <video src="{{ '/storage/'.$msg->file_path }}" muted></video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 text-white"><svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
                            @endif
                        </div>
                    @endif
                    @if($msg->isi_pesan)
                        <div class="bubble-content" data-raw="{{ $msg->isi_pesan }}">{{ $msg->isi_pesan }}</div>
                    @endif
                    <div class="bubble-meta">
                        <span class="bubble-time">{{ $msg->created_at->translatedFormat('H:i') }}</span>
                        @if($isMine)
                            <span class="{{ $msg->dibaca_at ? 'text-[#53bdeb]' : 'text-gray-400' }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.5 7L13.5 12L11 9.5L10 10.5L13.5 14L19.5 8L18.5 7M12.5 7L7.5 12L5 9.5L4 10.5L7.5 14L13.5 8L12.5 7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div id="previewArea">
            <div class="preview-container">
                <div class="preview-card">
                    <div class="preview-remove" onclick="cancelUpload()">×</div>
                    <img id="imgPreview" class="hidden">
                    <video id="vidPreview" class="hidden" muted></video>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="fileName" class="text-sm font-bold text-[#111b21] truncate"></p>
                    <p id="fileSize" class="text-[11px] text-[#667781]"></p>
                </div>
            </div>
        </div>

        <div class="wa-chat-footer">
            <form id="formKirim" method="POST" action="{{ route('admin.chat.store', $activeWarga) }}" class="flex items-center gap-2" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="penerima_id" value="{{ $activeWarga->id }}">
                <button type="button" id="emojiBtn" class="btn-icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                <input type="file" id="fileInput" name="file" class="hidden" accept="image/*,video/*" onchange="previewFile(this)">
                <button type="button" class="btn-icon" onclick="document.getElementById('fileInput').click()"><svg class="w-6 h-6 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13"/></svg></button>
                <div class="wa-input-group">
                    <input type="text" id="inputPesan" name="isi_pesan" placeholder="Ketik pesan" class="wa-input" autocomplete="off">
                </div>
                <button type="submit" id="btnKirim" class="btn-send">
                    <svg id="sendIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    <span id="loadingIcon" class="hidden w-5 h-5 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
                </button>
            </form>
        </div>
        @else
        <div class="flex-1 flex flex-col items-center justify-center text-center p-12 bg-[#f0f2f5]">
            <div class="w-60 h-60 bg-white rounded-full flex items-center justify-center mb-8 shadow-sm">
                <img src="https://abs.twimg.com/emoji/v2/72x72/1f4ac.png" class="w-32 h-32 opacity-20">
            </div>
            <h3 class="text-3xl font-light text-[#41525d]">Obrolan Warga</h3>
            <p class="text-sm text-[#667781] mt-4 max-w-sm">Kirim lampiran gambar atau video untuk mempermudah koordinasi dengan warga.</p>
        </div>
        @endif
    </div>
</div>

<div id="mediaLightbox" class="fixed inset-0 bg-black/90 z-[100] hidden items-center justify-center p-4" onclick="closeMediaLightbox()">
    <div class="max-w-5xl max-h-full flex items-center justify-center" onclick="event.stopPropagation()">
        <img id="lightboxImg" class="hidden max-w-full max-h-full object-contain shadow-2xl rounded-lg">
        <video id="lightboxVid" class="hidden max-w-full max-h-full rounded-lg" controls></video>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Perbaikan Emoji Button menggunakan Dynamic Import (ESM)
import('https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js').then(module => {
    const EmojiButton = module.EmojiButton;
    const emojiBtn = document.querySelector('#emojiBtn');
    const input = document.querySelector('#inputPesan');
    
    if (emojiBtn && EmojiButton) {
        const picker = new EmojiButton({ 
            position: 'top-start',
            theme: 'light',
            autoHide: false,
            zIndex: 100
        });

        picker.on('emoji', selection => {
            input.value += selection.emoji;
            input.focus();
        });

        emojiBtn.addEventListener('click', () => picker.togglePicker(emojiBtn));
    }
}).catch(err => console.error('Gagal memuat EmojiButton:', err));

function scrollToBottom() {
    const chat = document.getElementById('chatArea');
    if (chat) chat.scrollTop = chat.scrollHeight;
}
scrollToBottom();

function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('fileName').innerText = file.name;
    document.getElementById('fileSize').innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('previewArea').style.display = 'block';
    if (file.type.startsWith('image/')) {
        const img = document.getElementById('imgPreview');
        img.src = URL.createObjectURL(file); img.classList.remove('hidden'); document.getElementById('vidPreview').classList.add('hidden');
    } else {
        const vid = document.getElementById('vidPreview');
        vid.src = URL.createObjectURL(file); vid.classList.remove('hidden'); document.getElementById('imgPreview').classList.add('hidden');
    }
}
function cancelUpload() {
    document.getElementById('fileInput').value = ''; document.getElementById('previewArea').style.display = 'none';
}
function openFullMedia(src, type) {
    const lb = document.getElementById('mediaLightbox');
    const li = document.getElementById('lightboxImg');
    const lv = document.getElementById('lightboxVid');
    lb.classList.replace('hidden', 'flex');
    if (type === 'image') {
        li.src = src; li.classList.remove('hidden'); lv.classList.add('hidden');
    } else {
        lv.src = src; lv.classList.remove('hidden'); li.classList.add('hidden'); lv.play();
    }
}
function closeMediaLightbox() {
    document.getElementById('lightboxVid').pause();
    document.getElementById('mediaLightbox').classList.replace('flex' , 'hidden');
}

document.getElementById('formKirim')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnKirim');
    const icon = document.getElementById('sendIcon');
    const loader = document.getElementById('loadingIcon');
    btn.disabled = true; icon.classList.add('hidden'); loader.classList.remove('hidden');
    try {
        const res = await fetch(this.action, {
            method: 'POST', body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (res.ok) { document.getElementById('inputPesan').value = ''; cancelUpload(); await pollMessages(); }
        else { const err = await res.json(); alert(err.error || 'Gagal'); }
    } catch(e) {}
    btn.disabled = false; icon.classList.remove('hidden'); loader.classList.add('hidden');
});

@if($activeWarga)
let lastMsgId = {{ $activeMessages->last()?->id ?? 0 }};
const wargaId = {{ $activeWarga->id }};
async function pollMessages() {
    try {
        const res = await fetch(`{{ route('admin.chat.poll') }}?after_id=${lastMsgId}&warga_id=${wargaId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();
        
        // Update Status Online
        const statusEl = document.getElementById('partnerStatus');
        if (statusEl && data.partner_status) {
            statusEl.innerText = data.partner_status;
            if (data.is_partner_online) {
                statusEl.classList.remove('text-gray-400');
                statusEl.classList.add('text-emerald-600');
            } else {
                statusEl.classList.remove('text-emerald-600');
                statusEl.classList.add('text-gray-400');
            }
        }

        const msgs = data.messages || [];
        msgs.forEach(msg => { if (msg.id > lastMsgId) { lastMsgId = msg.id; appendMessage(msg); } });
    } catch(e) {}
}
function appendMessage(msg) {
    const chat = document.getElementById('chatArea');
    const div = document.createElement('div');
    div.className = `wa-msg-wrapper ${msg.is_mine ? 'mine' : 'theirs'}`;
    
    // Check search query
    const query = document.getElementById('chatSearchInput')?.value.toLowerCase() || '';
    if (query && !msg.isi_pesan?.toLowerCase().includes(query)) {
        div.classList.add('hidden');
    }

    let media = '';
    if (msg.file_path) {
        if (msg.file_type === 'image') { media = `<div class="chat-media" onclick="openFullMedia('${msg.file_path}', 'image')"><img src="${msg.file_path}"></div>`; }
        else { media = `<div class="chat-media" onclick="openFullMedia('${msg.file_path}', 'video')"><video src="${msg.file_path}" muted></video><div class="absolute inset-0 flex items-center justify-center bg-black/20 text-white"><svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div></div>`; }
    }
    
    let content = '';
    if (msg.isi_pesan) {
        let text = escHtml(msg.isi_pesan);
        if (query) {
            const regex = new RegExp(`(${query})`, 'gi');
            text = text.replace(regex, '<mark class="search-highlight">$1</mark>');
        }
        content = `<div class="bubble-content" data-raw="${escHtml(msg.isi_pesan)}">${text}</div>`;
    }

    div.innerHTML = `
        <div class="bubble ${msg.is_mine ? 'bubble-out' : 'bubble-in'} animate-slide-up">
            ${media}
            ${content}
            <div class="bubble-meta"><span class="bubble-time">${msg.waktu}</span>
                ${msg.is_mine ? '<span class="text-[#53bdeb]"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.5 7L13.5 12L11 9.5L10 10.5L13.5 14L19.5 8L18.5 7M12.5 7L7.5 12L5 9.5L4 10.5L7.5 14L13.5 8L12.5 7"/></svg></span>' : ''}
            </div>
        </div>`;
    chat.appendChild(div); scrollToBottom();
}
function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
setInterval(pollMessages, 5000);

// Pencarian Pesan
function toggleSearch() {
    const box = document.getElementById('searchBox');
    const input = document.getElementById('chatSearchInput');
    if (box.classList.contains('hidden')) {
        box.classList.remove('hidden');
        input.focus();
    } else {
        box.classList.add('hidden');
        input.value = '';
        filterMessages('');
    }
}

document.getElementById('chatSearchInput')?.addEventListener('input', function(e) {
    filterMessages(e.target.value.toLowerCase());
});

function filterMessages(query) {
    const wrappers = document.querySelectorAll('.wa-msg-wrapper');
    wrappers.forEach(wrap => {
        const contentEl = wrap.querySelector('.bubble-content');
        if (!contentEl) {
            // Jika hanya media tanpa teks, sembunyikan jika ada query (kecuali jika mau cari 'foto')
            wrap.classList.toggle('hidden', query.length > 0);
            return;
        }

        const rawText = contentEl.getAttribute('data-raw') || contentEl.innerText;
        const lowerRaw = rawText.toLowerCase();

        if (lowerRaw.includes(query)) {
            wrap.classList.remove('hidden');
            if (query.length > 0) {
                const regex = new RegExp(`(${query})`, 'gi');
                contentEl.innerHTML = escHtml(rawText).replace(regex, '<mark class="search-highlight">$1</mark>');
            } else {
                contentEl.innerHTML = escHtml(rawText);
            }
        } else {
            wrap.classList.add('hidden');
        }
    });
}
@endif

// Pencarian Warga / Kontak di Sidebar
document.getElementById('contactSearchInput')?.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.wa-list-item');
    items.forEach(item => {
        const name = item.querySelector('span.truncate')?.innerText.toLowerCase() || '';
        item.style.display = name.includes(query) ? 'flex' : 'none';
    });
});
</script>
@endpush
