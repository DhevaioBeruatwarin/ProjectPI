<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat dengan Pembeli - Jogja Artsphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Poppins', sans-serif;
        }
        .chat-card {
            display: flex;
            min-height: 75vh;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .chat-sidebar {
            width: 320px;
            background-color: #1f2335;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 24px;
        }
        .conversation-list {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .conversation {
            display: block;
            padding: 12px;
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            margin-bottom: 10px;
            background-color: rgba(255,255,255,0.08);
            transition: background 0.2s;
        }
        .conversation.active,
        .conversation:hover {
            background-color: rgba(255,255,255,0.2);
        }
        .conversation-name {
            font-weight: 600;
        }
        .conversation-snippet {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
            margin-top: 4px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        .chat-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 24px;
            background: #fafafa;
        }
        .chat-header {
            border-bottom: 1px solid #ececec;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .chat-header h4 {
            margin: 0;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding-right: 10px;
        }
        .chat-message {
            max-width: 75%;
            margin-bottom: 12px;
            padding: 12px 16px;
            border-radius: 16px;
            position: relative;
            word-wrap: break-word;
        }
        .chat-message.self {
            margin-left: auto;
            background: #435ff6;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .chat-message.other {
            background: #fff;
            border-bottom-left-radius: 4px;
            border: 1px solid #eaeaea;
        }
        .chat-message time {
            display: block;
            font-size: 0.75rem;
            margin-top: 6px;
            opacity: 0.75;
        }
        .message-form {
            margin-top: 20px;
        }
        .message-form textarea {
            resize: none;
        }
        .start-form select {
            background: #11152c;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .start-form select option {
            color: #000;
        }
        @media (max-width: 992px) {
            .chat-card {
                flex-direction: column;
            }
            .chat-sidebar {
                width: 100%;
            }
            .chat-content {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="chat-card">
                    <aside class="chat-sidebar">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Percakapan</h5>
                        </div>

                        <div class="conversation-list">
                            @forelse($conversations as $conversation)
                                @php
                                    $isActive = $activeConversation && $conversation->id === $activeConversation->id;
                                    $otherPembeli = $conversation->getOtherPembeli($currentPembeli->id_pembeli);
                                    $latestMessage = $conversation->messages->first();
                                @endphp
                                <a href="{{ route('pembeli.chat.pembeli.index', ['conversation' => $conversation->id]) }}"
                                   class="conversation {{ $isActive ? 'active' : '' }}">
                                    <div class="conversation-name">{{ $otherPembeli->nama ?? 'Pengguna' }}</div>
                                    <div class="conversation-snippet">
                                        {{ $latestMessage?->message ? \Illuminate\Support\Str::limit($latestMessage->message, 45) : 'Mulai percakapan' }}
                                    </div>
                                </a>
                            @empty
                                <p class="text-white-50 small mb-0">Belum ada percakapan.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('pembeli.chat.pembeli.start') }}" class="start-form">
                            @csrf
                            <label class="form-label text-white-50 small mb-2">
                                Mulai chat baru dengan pembeli
                            </label>
                            <select class="form-select form-select-sm mb-2" name="counterpart_id" required>
                                <option value="">Pilih Pembeli</option>
                                @foreach($counterparts as $counterpart)
                                    <option value="{{ $counterpart->id_pembeli }}">{{ $counterpart->nama }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-light btn-sm w-100">Mulai Chat</button>
                        </form>
                    </aside>

                    <section class="chat-content">
                        @if($activeConversation)
                            @php
                                $chatPartner = $activeConversation->getOtherPembeli($currentPembeli->id_pembeli);
                            @endphp
                            <div class="chat-header">
                                <h4 class="mb-1">{{ $chatPartner->nama ?? 'Pengguna' }}</h4>
                                <small class="text-muted">Chat dengan pembeli</small>
                            </div>

                            <div class="chat-messages" id="chatMessages"
                                 data-fetch-url="{{ route('pembeli.chat.pembeli.messages', $activeConversation) }}"
                                 data-send-url="{{ route('pembeli.chat.pembeli.send', $activeConversation) }}"
                                 data-current-id="{{ $currentPembeli->id_pembeli }}">
                                @foreach($messages as $message)
                                    <div class="chat-message {{ $message->sender_id === $currentPembeli->id_pembeli ? 'self' : 'other' }}">
                                        <div>{{ $message->message }}</div>
                                        <time>{{ $message->created_at?->format('d M H:i') }}</time>
                                    </div>
                                @endforeach
                            </div>

                            <form id="messageForm" class="message-form">
                                <div class="input-group">
                                    <textarea 
                                        class="form-control" 
                                        name="message" 
                                        rows="2" 
                                        placeholder="Tulis pesan..." 
                                        required></textarea>
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>
                            </form>
                        @else
                            <div class="text-center text-muted" style="margin-top: 40%;">
                                <p>Pilih percakapan atau mulai chat baru</p>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const messagesContainer = document.getElementById('chatMessages');
            if (!messagesContainer) {
                return;
            }

            const fetchUrl = messagesContainer.dataset.fetchUrl;
            const sendUrl = messagesContainer.dataset.sendUrl;
            const currentPembeliId = parseInt(messagesContainer.dataset.currentId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const form = document.getElementById('messageForm');

            const escapeHtml = (value = '') => {
                return value
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const renderMessages = (messages) => {
                if (!messages?.length) {
                    messagesContainer.innerHTML = '<p class="text-muted small">Belum ada pesan. Mulai percakapan!</p>';
                    return;
                }

                messagesContainer.innerHTML = messages.map((message) => {
                    const cssClass = message.is_self ? 'self' : 'other';
                    const time = message.sent_at ?? '';
                    const safeMessage = escapeHtml(message.message ?? '').replace(/\n/g, '<br>');
                    return `
                        <div class="chat-message ${cssClass}">
                            <div>${safeMessage}</div>
                            <time>${time}</time>
                        </div>
                    `;
                }).join('');

                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            };

            const refreshMessages = async () => {
                if (!fetchUrl) return;
                try {
                    const response = await fetch(fetchUrl, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!response.ok) return;
                    const data = await response.json();
                    renderMessages(data.messages ?? []);
                } catch (error) {
                    console.error('Gagal memuat pesan', error);
                }
            };

            const sendMessage = async (event) => {
                event.preventDefault();
                if (!sendUrl) return;

                const textarea = form.querySelector('textarea[name="message"]');
                const button = form.querySelector('button[type="submit"]');
                const message = textarea.value.trim();

                if (!message) return;

                button.disabled = true;

                try {
                    const response = await fetch(sendUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ message })
                    });

                    if (response.ok) {
                        textarea.value = '';
                        await refreshMessages();
                    }
                } catch (error) {
                    console.error('Gagal mengirim pesan', error);
                } finally {
                    button.disabled = false;
                }
            };

            refreshMessages();
            setInterval(refreshMessages, 5000);

            if (form) {
                form.addEventListener('submit', sendMessage);
            }
        })();
    </script>
</body>
</html>

