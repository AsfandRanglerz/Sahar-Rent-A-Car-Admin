@extends('admin.layout.app')
@section('title', 'Privacy Policy')
@section('content')
    <style>
        .sidebar-sec {
            width: 30%;
            height: 464px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }

        .sidebar-sec .user {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-sec .user.active {
            background: #e0e0e0;
        }

        .chat-container {
            height: 464px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 15px;
            background: #575fab;
            color: white;
            font-size: 18px;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f9f9f9;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            max-width: 70%;
            word-wrap: break-word;      /* ✅ Breaks long words */
            white-space: normal; 
        }

        .message.sent {
            background: #d1f0d1;
            margin-left: auto;
        }

        .message.received {
            background: #f1f1f1;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .chat-input textarea {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }

        .chat-input button {
            margin-left: 10px;
            padding: 10px 20px;
            background: #575fab;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .last-message {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
        display: block;
        font-size: 14px;
        color: #555;
    }

    </style>
    @php use Carbon\Carbon; @endphp

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="row border">
                <div class="col-4 px-0 sidebar-sec">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <input type="text" placeholder="Search User..." class="form-control" id="searchUser"
                            onkeyup="searchUser()" />
                    </div>
                        @forelse ($users as $user)
                            <div class="user {{ $user['id'] == $currentChatId ? 'active' : '' }}"
                                onclick="window.location.href='{{ route('chat.index', ['id' => $user['id'], 'type' => $user['usertype']]) }}'">
                                <div style="gap: 10px;" class="d-flex justify-content-between align-items-center">
                                    <div class="user-image">
                                        @php
                                            $userModel =
                                                $user['usertype'] == 'customer'
                                                    ? App\Models\User::where('id', $user['id'])->first()
                                                    : App\Models\Driver::where('id', $user['id'])->first();

                                            $image = $userModel ? $userModel->image : null;
                                            $name = $userModel ? $userModel->name : 'Unknown';
                                        @endphp
                                        <img src="{{ $image ? asset($image) : asset('/public/admin/assets/images/users/1746614348.png') }}"
                                            alt="" class="rounded-circle" style="width: 40px; height: 40px;">
                                    </div>
                                    <div style="max-width: 200px;">
                                        <strong>{{ $name }}</strong>
                                        <div class="last-message d-block">{{ $user['lastMessage'] ?? 'No messages yet' }}</div>
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $time = isset($user['lastMessageTime'])
                                            ? Carbon::parse($user['lastMessageTime'])->format('d M Y, h:i A') // 12-hour format
                                            : 'N/A';
                                    @endphp
                                    <small>{{ $time }}</small>

                                    
                                    @if (in_array($user['usertype'], ['customer', 'driver']) && $user['unreadCount'] > 0 && $user['mytype'] !== "admin")
                                        <span class="badge badge-danger">{{ $user['unreadCount'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center mt-2">No users available.</p>
                        @endforelse
                    </div>


                    <div class="col-8 px-0 chat-container">
                        @php
                            // Find the current user based on $currentChatId
                            $currentUser = collect($users)->firstWhere('id', $currentChatId);

                            if ($currentUser) {
                                $userModel =
                                    $currentUser['usertype'] == 'customer'
                                        ? App\Models\User::where('id', $currentUser['id'])->first()
                                        : App\Models\Driver::where('id', $currentUser['id'])->first();

                                $image = $userModel ? $userModel->image : null;
                                $name = $userModel ? $userModel->name : 'Unknown';
                            } else {
                                $image = null;
                                $name = 'Unknown';
                            }
                        @endphp

                        <div class="chat-header">
                            <div style="gap: 10px;" class="d-flex align-items-center">
                                <div class="user-image">
                                    <img src="{{ $image ? asset($image) : asset('/public/admin/assets/images/users/1746614348.png') }}"
                                        alt="" class="rounded-circle" style="width: 40px; height: 40px;">
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white">{{ $name }}</h6>
                                    <p style="font-size: 0.8rem; color: #d9d8d8; line-height: 1.1;" class="mb-0">
                                        {{ $currentUser['usertype'] ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chat-messages" id="chat-messages">
                            @foreach ($messages as $msg)
                                <div
                                    {{-- class="message {{ $msg['sendBy'] === (auth()->user()->id ?? 'Admin') ? 'sent' : 'received' }}">
                                    <strong>{{ $msg['sendBy'] }}</strong>: {{ $msg['text'] }}
                                    <div><small>{{ $msg['createdAt'] }}</small></div> --}}
                                    @php
                                    $sendBy = $msg['sendBy'] ?? 'System';
                                    $text = $msg['text'] ?? '[No Message]';
                                    $createdAt = $msg['createdAt'] ?? now();
                                    $isSent = $sendBy == (auth()->user()->id ?? 'Admin');
                                    @endphp
                                    <div class="message {{ $isSent ? 'sent' : 'received' }}">
                                    <strong>{{ $sendBy }}</strong>: {{ $text }}
                                    <div><small>{{ Carbon::parse($createdAt)->format('d M Y, h:i A') }}</small></div>
                                    
                                </div>
                            @endforeach
                        </div>
                        <form class="chat-input" id="chat-form">
                            @csrf
                            <textarea name="message" id="message" rows="1" placeholder="Type a message..." required></textarea>
                            <button type="submit"><span data-feather="send"></span></button>
                        </form>
                    </div>
                </div>


        </section>
    </div>
@endsection
@section('js')
    <script>
        const chatForm = document.getElementById('chat-form');
        const chatMessages = document.getElementById('chat-messages');
        const messageInput = document.getElementById('message');
        const sendButton = chatForm.querySelector('button');
        const sidebarSec = document.querySelector('.sidebar-sec'); // Fixed variable name
        let isSearching = false;

        function searchUser() {
            const input = document.getElementById('searchUser');
            const filter = input.value.toLowerCase();
            const users = document.querySelectorAll('.sidebar-sec .user');
            let hasVisibleUsers = false;

            isSearching = true; // Update the search state

            users.forEach(user => {
                const userName = user.querySelector('strong').textContent.toLowerCase();
                if (userName.includes(filter)) {
                    user.style.display = ''; // Show the user
                    hasVisibleUsers = true;
                } else {
                    user.style.display = 'none'; // Hide the user
                }
            });

            // Check if no users are visible
            const noUsersMessage = document.getElementById('noUsersMessage');
            if (!hasVisibleUsers) {
                if (!noUsersMessage) {
                    const message = document.createElement('p');
                    message.id = 'noUsersMessage';
                    message.textContent = 'No users found.';
                    message.style.textAlign = 'center';
                    message.style.color = '#888';
                    document.querySelector('.sidebar-sec').appendChild(message);
                }
            } else {
                if (noUsersMessage) {
                    noUsersMessage.remove(); // Remove the message if users are visible
                }
            }
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent the form from reloading the page

            const message = messageInput.value.trim();
            if (!message) return; // Prevent sending empty messages

            // Clear the textarea immediately for better responsiveness
            messageInput.value = '';
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const id = urlParams.get('id'); // Get the 'id' parameter from the query string
                const type = urlParams.get('type'); // Get the 'type' parameter from the query string

                const response = await fetch(
                    `{{ route('chat.send') }}?id=${encodeURIComponent(id)}&type=${encodeURIComponent(type)}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message
                        })
                    }
                );

                if (response.ok) {
                    await loadMessages(true); // Reload the chat messages and header dynamically
                    loadUsers(); // Reload the users dynamically
                } else {
                    console.error('Failed to send message');
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            // scrollToBottom(); // Scroll to the latest message when the chat opens
        (async () => {
        await loadMessages(true);
        })();
        setTimeout(() => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
         }, 100);

            // Listen for the "Enter" key press in the message input
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) { // Check if "Enter" is pressed without "Shift"
                    e.preventDefault(); // Prevent adding a new line

                    const message = messageInput.value.trim();
                    if (message) { // Only send the message if it has a value
                        chatForm.dispatchEvent(new Event('submit')); // Trigger the form submission
                    }
                }
            });
        });

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    function scrollToBottomIfNeeded() {
    const threshold = 100; // px from bottom
    const isNearBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < threshold;

    if (isNearBottom) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}


        async function loadMessages(shouldScroll = false) {
            const response = await fetch('{{ route('chat.index', ['id' => $currentChatId]) }}');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Update chat messages
            const newMessages = doc.getElementById('chat-messages').innerHTML;
            chatMessages.innerHTML = newMessages;
            // scrollToBottom(); // Scroll to the latest message after loading messages
             if (shouldScroll) {
                setTimeout(() => {
                    scrollToBottom();
                }, 50); // slight delay ensures DOM is updated
            } else {
                scrollToBottomIfNeeded();
            }
            // Update chat header
            const newChatHeader = doc.querySelector('.chat-header');
            if (newChatHeader) {
                const currentChatHeader = document.querySelector('.chat-header');
                currentChatHeader.innerHTML = newChatHeader.innerHTML;
            }
        }
        async function loadUsers() {
            // Skip reloading users if the search input is active
            if (isSearching) {
                // console.log('Skipping user reload due to active search input.');
                return;
            }

            try {
                const response = await fetch('{{ route('chat.index', ['id' => $currentChatId]) }}');
                if (!response.ok) {
                    throw new Error(`Failed to fetch users: ${response.statusText}`);
                }

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update sidebar-sec
                const newSidebarSec = doc.querySelector('.sidebar-sec');
                if (newSidebarSec) {
                    sidebarSec.innerHTML = newSidebarSec.innerHTML;
                    // console.log('Users reloaded successfully.');
                } else {
                    // console.error('Failed to find .sidebar-sec in the fetched HTML.');
                }
            } catch (error) {
                // console.error('Error loading users:', error);
            }
        }

        // Poll for new messages and users every 2 seconds
        setInterval(() => {
            // console.log('Polling for new messages and users...');
            loadMessages();
            loadUsers();
        }, 10000);
    </script>
    <script>
        const users = @json($users); // Pass the users variable as JSON
        // console.log('All users:', users);
    </script>
@endsection
