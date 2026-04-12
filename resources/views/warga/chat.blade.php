@extends('layouts.warga')
@section('title', 'WhatsApp Chat - Admin')
@section('page-title', 'WhatsApp Chat Admin')

@section('content')
{{-- CSS Khusus WhatsApp Style --}}
<style>
    .wa-container { height: calc(100vh - 160px); display: flex; flex-direction: column; background: #f8fafc; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; position: relative; }
    
    @media (max-width: 768px) {
        .wa-container { 
            height: calc(100dvh - 84px); 
            border-radius: 0; border: none; 
            margin: -1rem; 
            width: calc(100% + 2rem);
        }
        .wa-header { padding: 10px 16px; min-height: 60px; }
        .wa-body { padding: 20px 12px; }
        .wa-footer { padding: 10px 12px; }
    }

    .wa-header { background: #ffffff; padding: 12px 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; z-index: 10; }
    .wa-body { 
        flex: 1; overflow-y: auto; padding: 24px 5%; 
        background-color: #f1f5f9;
        background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
        background-size: 20px 20px;
        display: flex; flex-direction: column; 
    }
    .wa-footer { background: #f0f2f5; padding: 10px 16px; border-top: 1px solid rgba(0,0,0,0.08); }
    
    .bubble { max-width: 65%; padding: 6px 12px 2px; border-radius: 8px; font-size: 14px; line-height: 1.5; position: relative; margin-bottom: 4px; box-shadow: 0 1px 0.5px rgba(0,0,0,0.13); }
    .bubble-in { background: #ffffff; align-self: flex-start; border-top-left-radius: 0; margin-left: 8px; }
    .bubble-out { background: #dcf8c6; align-self: flex-end; border-top-right-radius: 0; margin-right: 8px; }
    
    .wa-msg-wrapper { display: flex; flex-direction: column; margin-bottom: 8px; }
    .wa-msg-wrapper.mine { align-items: flex-end; }
    .wa-msg-wrapper.theirs { align-items: flex-start; }
    
    /* Tail Effect */
    .bubble-in::before { content: ''; position: absolute; left: -8px; top: 0; width: 0; height: 0; border-top: 10px solid #ffffff; border-left: 10px solid transparent; }
    .bubble-out::before { content: ''; position: absolute; right: -8px; top: 0; width: 0; height: 0; border-top: 10px solid #dcf8c6; border-right: 10px solid transparent; }

    .bubble-content { position: relative; word-wrap: break-word; }
    .bubble-meta { display: flex; align-items: center; justify-content: flex-end; gap: 4px; margin-top: 2px; }
    .bubble-time { font-size: 11px; color: rgba(0,0,0,0.45); }
    
    .wa-input-group { background: #fff; border-radius: 24px; padding: 5px 15px; display: flex; align-items: center; gap: 10px; border: 1px solid transparent; transition: all 0.2s; }
    .wa-input-group:focus-within { border-color: #00a884; }
    .wa-input { flex: 1; border: none; outline: none; font-size: 14px; background: transparent; padding: 8px 0; }
    
    .btn-icon { color: #54656f; cursor: pointer; transition: color 0.2s; background: none; border: none; padding: 4px; display: flex; align-items: center; }
    .btn-icon:hover { color: #111b21; }
    
    .btn-send { background: none; border: none; color: #54656f; cursor: pointer; transition: transform 0.2s; }
    .btn-send:hover { transform: scale(1.1); color: #00a884; }

    /* Media Styles */
    .chat-media { border-radius: 6px; overflow: hidden; margin-bottom: 4px; border: 1px solid rgba(0,0,0,0.05); cursor: pointer; }
    .chat-media img { max-width: 100%; height: auto; max-height: 300px; display: block; object-fit: cover; }
    .chat-media video { max-width: 100%; max-height: 300px; display: block; }

    #previewArea { display: none; padding: 12px 20px; background: #fff; border-top: 1px solid #f1f5f9; position: relative; }
    .preview-container { display: flex; align-items: center; gap: 12px; }
    .preview-card { position: relative; width: 64px; height: 64px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
    .preview-card img, .preview-card video { width: 100%; height: 100%; object-fit: cover; }
    .preview-remove { position: absolute; top: -6px; right: -6px; background: #ef4444; color: #fff; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 14px; cursor: pointer; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    
    .search-highlight { background-color: #ffeb3b; color: #000; padding: 0 2px; border-radius: 2px; font-weight: bold; }
</style>

<div class="wa-container shadow-xl animate-fade-in">
    {{-- Header --}}
    <div class="wa-header">
        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
            @if($admin && $admin->avatar)
                <img src="{{ '/storage/'.$admin->avatar }}" class="w-full h-full object-cover">
            @else
                <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-[15px] font-bold text-[#111b21] truncate">{{ $admin->name ?? 'Admin RT' }}</h4>
            <p id="partnerStatus" class="text-[11px] {{ ($admin && $admin->isOnline()) ? 'text-emerald-600' : 'text-gray-400' }} font-medium">
                {{ $admin ? $admin->lastSeenStatus() : 'Offline' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div id="searchBox" class="hidden animate-fade-in">
                <input type="text" id="chatSearchInput" placeholder="Cari pesan..." 
                    class="text-xs px-3 py-1.5 rounded-full border border-gray-200 outline-none focus:border-emerald-500 w-32 md:w-48 transition-all">
            </div>
            <button class="btn-icon" onclick="toggleSearch()"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
        </div>
    </div>

    {{-- Body --}}
    <div id="chatArea" class="wa-body custom-scrollbar">
        @if($messages->isEmpty())
        <div class="flex justify-center items-center h-full">
            <span class="bg-[#e1f3fb] text-[#54656f] text-[12px] px-4 py-1.5 rounded-lg shadow-sm font-medium uppercase tracking-wider">Mulai Percakapan Media</span>
        </div>
        @endif

        @foreach($messages as $msg)
        @php $isMine = $msg->pengirim_id === auth()->id(); @endphp
        <div class="wa-msg-wrapper {{ $isMine ? 'mine' : 'theirs' }}" data-msg-id="{{ $msg->id }}">
            <div class="bubble {{ $isMine ? 'bubble-out' : 'bubble-in' }} animate-slide-up">
                @if($msg->file_path)
                    <div class="chat-media" onclick="openFullMedia('{{ '/storage/'.$msg->file_path }}', '{{ $msg->file_type }}')">
                        @if($msg->file_type === 'image')
                            <img src="{{ '/storage/'.$msg->file_path }}" alt="Pesan Gambar">
                        @elseif($msg->file_type === 'video')
                            <video src="{{ '/storage/'.$msg->file_path }}" muted></video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20 text-white">
                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
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
                            @if($msg->dibaca_at)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.5 7L13.5 12L11 9.5L10 10.5L13.5 14L19.5 8L18.5 7M12.5 7L7.5 12L5 9.5L4 10.5L7.5 14L13.5 8L12.5 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Preview --}}
    <div id="previewArea">
        <div class="preview-container">
            <div class="preview-card">
                <div class="preview-remove" onclick="cancelUpload()">×</div>
                <img id="imgPreview" class="hidden">
                <video id="vidPreview" class="hidden" muted></video>
            </div>
            <div class="flex-1">
                <p id="fileName" class="text-sm font-bold text-gray-800 truncate"></p>
                <p id="fileSize" class="text-[11px] text-gray-500"></p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="wa-footer">
        <form id="formKirim" method="POST" action="{{ route('warga.chat.store') }}" class="flex items-center gap-2" enctype="multipart/form-data">
            @csrf
            <button type="button" id="emojiBtn" class="btn-icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
            <input type="file" id="fileInput" name="file" class="hidden" accept="image/*,video/*" onchange="previewFile(this)">
            <button type="button" class="btn-icon" onclick="document.getElementById('fileInput').click()"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></button>
            <div class="wa-input-group flex-1 shadow-sm">
                <input type="text" id="inputPesan" name="isi_pesan" placeholder="Ketik pesan" class="wa-input px-1" autocomplete="off">
            </div>
            <button type="submit" id="btnKirim" class="btn-send">
                <svg id="sendIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                <span id="loadingIcon" class="hidden w-5 h-5 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
            </button>
        </form>
    </div>
</div>

{{-- Lightbox --}}
<div id="mediaLightbox" class="fixed inset-0 bg-black/90 z-[100] hidden items-center justify-center p-4" onclick="closeMediaLightbox()">
    <div class="max-w-5xl max-h-full flex items-center justify-center relative" onclick="event.stopPropagation()">
        <img id="lightboxImg" class="hidden max-w-full max-h-full object-contain">
        <video id="lightboxVid" class="hidden max-w-full max-h-full" controls></video>
        <button class="absolute -top-10 -right-10 text-white hover:text-red-500" onclick="closeMediaLightbox()"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
</div>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.2s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
</style>
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
    const reader = new FileReader();
    document.getElementById('fileName').innerText = file.name;
    document.getElementById('fileSize').innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('previewArea').style.display = 'block';
    
    if (file.type.startsWith('image/')) {
        const img = document.getElementById('imgPreview');
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
        document.getElementById('vidPreview').classList.add('hidden');
    } else {
        const vid = document.getElementById('vidPreview');
        vid.src = URL.createObjectURL(file);
        vid.classList.remove('hidden');
        document.getElementById('imgPreview').classList.add('hidden');
    }
}

function cancelUpload() {
    document.getElementById('fileInput').value = '';
    document.getElementById('previewArea').style.display = 'none';
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
    const lb = document.getElementById('mediaLightbox');
    document.getElementById('lightboxVid').pause();
    lb.classList.replace('flex', 'hidden');
}

document.getElementById('formKirim')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const input = document.getElementById('inputPesan');
    const fileInput = document.getElementById('fileInput');
    if (!input.value.trim() && !fileInput.files.length) return;

    const btn = document.getElementById('btnKirim');
    const icon = document.getElementById('sendIcon');
    const loader = document.getElementById('loadingIcon');
    
    btn.disabled = true;
    icon.classList.add('hidden');
    loader.classList.remove('hidden');

    try {
        const res = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (res.ok) {
            input.value = '';
            cancelUpload();
            await pollMessages();
        } else {
            const err = await res.json();
            alert(err.error || 'Gagal mengirim pesan');
        }
    } catch(e) {}
    btn.disabled = false;
    icon.classList.remove('hidden');
    loader.classList.add('hidden');
});

let lastMsgId = {{ $messages->last()?->id ?? 0 }};
async function pollMessages() {
    try {
        const res = await fetch(`{{ route('warga.chat.poll') }}?after_id=${lastMsgId}`, {
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
        msgs.forEach(msg => {
            if (msg.id > lastMsgId) {
                lastMsgId = msg.id;
                appendMessage(msg);
            }
        });
    } catch(e) {}
}

function appendMessage(msg) {
    const chat = document.getElementById('chatArea');
    const empty = chat.querySelector('.h-full');
    if (empty) empty.remove();
    const div = document.createElement('div');
    div.className = `wa-msg-wrapper ${msg.is_mine ? 'mine' : 'theirs'}`;
    
    // Check search query
    const query = document.getElementById('chatSearchInput')?.value.toLowerCase() || '';
    if (query && !msg.isi_pesan?.toLowerCase().includes(query)) {
        div.classList.add('hidden');
    }

    let media = '';
    if (msg.file_path) {
        if (msg.file_type === 'image') {
            media = `<div class="chat-media" onclick="openFullMedia('${msg.file_path}', 'image')"><img src="${msg.file_path}"></div>`;
        } else {
            media = `<div class="chat-media" onclick="openFullMedia('${msg.file_path}', 'video')"><video src="${msg.file_path}" muted></video><div class="absolute inset-0 flex items-center justify-center bg-black/20"><svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div></div>`;
        }
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
            <div class="bubble-meta">
                <span class="bubble-time">${msg.waktu}</span>
                ${msg.is_mine ? '<span class="text-[#53bdeb]"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg></span>' : ''}
            </div>
        </div>`;
    chat.appendChild(div);
    scrollToBottom();
}

function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}
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
</script>
@endpush
