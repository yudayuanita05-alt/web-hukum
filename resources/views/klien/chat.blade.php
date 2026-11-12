@extends('layouts.app')

@section('title', 'Konsultasi Chat Online')

@section('styles')
<style>
    .chat-box {
        height: 480px;
        overflow-y: auto;
        background: #F8FAFC;
        border-radius: 12px;
        padding: 20px;
    }
    .chat-message {
        display: flex;
        margin-bottom: 15px;
    }
    .chat-message.sender {
        justify-content: flex-end;
    }
    .chat-message.receiver {
        justify-content: flex-start;
    }
    .chat-bubble {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 0.95rem;
        position: relative;
    }
    .chat-bubble.sender {
        background: var(--law-navy);
        color: #FFFFFF;
        border-bottom-right-radius: 4px;
    }
    .chat-bubble.receiver {
        background: #FFFFFF;
        color: #1E293B;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    .chat-time {
        font-size: 0.7rem;
        margin-top: 4px;
        opacity: 0.75;
        text-align: right;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar Perkara Terkait -->
        <div class="col-md-4">
            <div class="card card-custom p-3 h-100">
                <h6 class="fw-bold text-navy mb-3 px-2"><i class="fa-solid fa-list me-2"></i> Pilih Kasus Konsultasi</h6>
                @if($daftarPerkara->isEmpty())
                    <p class="text-muted small px-2">Anda belum memiliki permohonan perkara.</p>
                    <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-sm btn-navy m-2">Ajukan Permohonan</a>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($daftarPerkara as $p)
                            <a href="{{ route('klien.chat.index', $p->id_formulir) }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-2 p-3 {{ $selectedPerkara && $selectedPerkara->id_formulir == $p->id_formulir ? 'active bg-navy text-white' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold small">{{ $p->no_permohonan }}</span>
                                    <span class="badge {{ $selectedPerkara && $selectedPerkara->id_formulir == $p->id_formulir ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
                                        {{ $p->created_at->format('d/m') }}
                                    </span>
                                </div>
                                <div class="small text-truncate" style="opacity: 0.85;">
                                    {{ $p->uraian_singkat_perkara }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8">
            <div class="card card-custom p-4">
                @if(!$selectedPerkara)
                    <div class="text-center py-5">
                        <i class="fa-solid fa-comments display-3 text-muted mb-3"></i>
                        <h6 class="text-muted">Pilih salah satu perkara di sebelah kiri untuk memulai obrolan dengan Sekretariat.</h6>
                    </div>
                @else
                    <!-- Chat Header -->
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-headset fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-navy">Sekretariat Handoko & Partners</h6>
                                <small class="text-muted">Perkara: {{ $selectedPerkara->no_permohonan }}</small>
                            </div>
                        </div>
                        <a href="{{ route('klien.konsultasi.detail', $selectedPerkara->id_formulir) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-file-lines me-1"></i> Berkas
                        </a>
                    </div>

                    <!-- Messages Container -->
                    <div class="chat-box mb-3" id="chatBox">
                        @forelse($chats as $c)
                            <div class="chat-message {{ $c->id_pengirim == $user->id_user ? 'sender' : 'receiver' }}">
                                <div class="chat-bubble {{ $c->id_pengirim == $user->id_user ? 'sender' : 'receiver' }}">
                                    @if($c->id_pengirim != $user->id_user)
                                        <small class="fw-bold text-primary d-block mb-1">Sekretaris Kantor</small>
                                    @endif
                                    <div>{{ $c->pesan }}</div>
                                    <div class="chat-time">{{ $c->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted" id="emptyNotice">
                                <i class="fa-solid fa-comment-dots fs-1 mb-2"></i>
                                <p class="small mb-0">Belum ada riwayat pesan. Kirim pertanyaan Anda di bawah ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Input Bar -->
                    <form id="chatForm">
                        @csrf
                        <input type="hidden" name="id_formulir" value="{{ $selectedPerkara->id_formulir }}">
                        <div class="input-group">
                            <input type="text" name="pesan" id="messageInput" class="form-control" placeholder="Ketik pesan konsultasi Anda di sini..." autocomplete="off" required>
                            <button class="btn btn-navy px-4" type="submit" id="btnSend">
                                <i class="fa-solid fa-paper-plane me-1"></i> Kirim
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($selectedPerkara)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const idFormulir = '{{ $selectedPerkara->id_formulir }}';

        // Scroll to bottom
        chatBox.scrollTop = chatBox.scrollHeight;

        // Kirim Pesan via AJAX
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            messageInput.disabled = true;

            fetch("{{ route('klien.chat.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id_formulir: idFormulir,
                    pesan: text
                })
            })
            .then(res => res.json())
            .then(data => {
                messageInput.disabled = false;
                messageInput.value = '';
                messageInput.focus();

                if (data.success) {
                    appendMessage(data.chat.pesan, data.chat.waktu, true);
                    const notice = document.getElementById('emptyNotice');
                    if (notice) notice.remove();
                }
            })
            .catch(err => {
                messageInput.disabled = false;
                console.error(err);
            });
        });

        function appendMessage(text, time, isSender) {
            const divMsg = document.createElement('div');
            divMsg.className = 'chat-message ' + (isSender ? 'sender' : 'receiver');

            divMsg.innerHTML = `
                <div class="chat-bubble ${isSender ? 'sender' : 'receiver'}">
                    <div>${text}</div>
                    <div class="chat-time">${time}</div>
                </div>
            `;
            chatBox.appendChild(divMsg);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Polling setiap 3 detik untuk pesan baru
        setInterval(function () {
            fetch("{{ url('klien/chat/fetch') }}/" + idFormulir)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.chats) {
                        chatBox.innerHTML = '';
                        data.chats.forEach(c => {
                            appendMessage(c.pesan, c.waktu, c.is_sender);
                        });
                    }
                })
                .catch(e => console.log(e));
        }, 3000);
    });
</script>
@endif
@endsection

