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
        background: #007bff;
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
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="row border">
                <div class="col-4 px-0 sidebar-sec">
                    @forelse ($users as $user)
                        <div class="user {{ $user['id'] == $currentChatId ? 'active' : '' }}"
                            onclick="window.location.href='{{ route('chat.index', ['id' => $user['id']]) }}'">
                            <div>
                                <strong>{{ $user['name'] }}</strong>
                                <div>{{ $user['lastMessage'] ?? 'No messages yet' }}</div>
                            </div>
                            <small>{{ $user['lastMessageTime'] ?? 'N/A' }}</small>
                        </div>
                    @empty
                        <p>No users available.</p>
                    @endforelse
                </div>
           
        
            <div class="col-8 px-0 chat-container">
                <div class="chat-header">
                    {{ $currentUser['name'] ?? '' }}
                </div>
                <div class="chat-messages" id="chat-messages">
                    @foreach ($messages as $msg)
                        <div class="message {{ $msg['sendBy'] === (auth()->user()->id ?? 'Admin') ? 'sent' : 'received' }}">
                            <strong>{{ $msg['sendBy'] }}</strong>: {{ $msg['text'] }}
                            <div><small>{{ $msg['createdAt'] }}</small></div>
                        </div>
                    @endforeach
                </div>
                <form class="chat-input" id="chat-form">
                    @csrf
                    <textarea name="message" id="message" rows="1" placeholder="Type a message..." required></textarea>
                    <button type="submit">Send</button>
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

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault(); // Prevent the form from reloading the page

    const message = messageInput.value.trim();
    if (!message) return; // Prevent sending empty messages

    // Clear the textarea immediately for better responsiveness
    messageInput.value = '';
    try {
        // Send the message via AJAX
        const response = await fetch('{{ route('chat.send', ['id' => $currentChatId]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message
            })
        });

        if (response.ok) {
            loadMessages(); // Reload the chat messages dynamically
            loadUsers(); // Reload the users dynamically
        } else {
            console.error('Failed to send message');
        }
    } catch (error) {
        console.error('Error sending message:', error);
    } 
});
document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom(); // Scroll to the latest message when the chat opens
});

function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

async function loadMessages() {
    const response = await fetch('{{ route('chat.index', ['id' => $currentChatId]) }}');
    const html = await response.text();
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');

    // Update chat messages
    const newMessages = doc.getElementById('chat-messages').innerHTML;
    chatMessages.innerHTML = newMessages;
    scrollToBottom(); // Scroll to the latest message after loading messages
}

async function loadUsers() {
    try {
        const response = await fetch('{{ route('chat.index', ['id' => $currentChatId]) }}');
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update sidebar-sec
        const newSidebarSec = doc.querySelector('.sidebar-sec').innerHTML;
        sidebarSec.innerHTML = newSidebarSec;
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

// Poll for new messages and users every 2 seconds
setInterval(() => {
    loadMessages();
    loadUsers();
}, 2000);
</script>
<script>
    const users = @json($users); // Pass the users variable as JSON
    console.log('All users:', users);
</script>
@endsection
