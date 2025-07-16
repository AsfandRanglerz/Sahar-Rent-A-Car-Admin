<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ChatController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Display chat messages and all profiles.
     */


    public function index(Request $request)
    {
        $id = $request->query('id'); // Get the 'id' from the query parameter
        $type = $request->query('type'); // Get the 'type' from the query parameter

        $messages = $id ? $this->firebase->getMessages($id) ?? [] : []; // Fetch messages only if 'id' exists
        $users = $this->firebase->getUsers(); // Fetch all profiles from the chats node
        
        
        // 🧹 Filter out deleted users
    $users = array_filter($users, function ($user) {
        if (!isset($user['id'], $user['usertype'])) return false;
$exists = false;

    if ($user['usertype'] === 'customer') {
        $exists = \App\Models\User::where('id', $user['id'])->exists();
    }

    if ($user['usertype'] === 'driver') {
        $exists = \App\Models\Driver::where('id', $user['id'])->exists();
    }
    // ❌ If user not found in DB, delete their chat from Firebase
    // if (!$exists) {
    //     app(\App\Services\FirebaseService::class)->deleteChat($user['id']);
    // }

     if (!$exists && !empty($chat[0]['mytype']) && $chat[0]['mytype'] !== 'admin') {
    app(\App\Services\FirebaseService::class)->deleteChat($userId);
    }
    return $exists;
});

        // Mark messages as read
        if ($id) {
            foreach ($messages as $key => $message) {
                if (
                    isset($message['read']) &&
                    !$message['read'] &&
                    (!auth()->check() || $message['sendBy'] !== (auth()->user()->id ?? '')) &&
                    ($message['mytype'] ?? '') !== 'admin' // Skip messages with 'mytype' set to 'admin'
                ) {
                    $this->firebase->markMessageAsRead($id, $key);
                }
            }
        }

        // Sort users by lastMessageTime in descending order
        usort($users, function ($a, $b) {
            return strtotime($b['lastMessageTime'] ?? '1970-01-01') - strtotime($a['lastMessageTime'] ?? '1970-01-01');
        });

        \Log::info('Users fetched for sidebar:', $users); // Log the users for debugging

        $currentUser = $id ? collect($users)->firstWhere('id', $id) : null; // Find the current user if 'id' exists

        return view('mychat', [
            'messages' => $messages,
            'chatId' => $id,
            'usertype' => $type, // Pass the 'type' to the view
            'users' => $users,
            'currentChatId' => $id,
            'currentUser' => $currentUser
        ]);
    }

    public function chatpendingCounter()
{
    $users = $this->firebase->getUsers(); // Fetch all chats

    $unreadCount = 0;

    foreach ($users as $user) {
        $chatId = $user['id'] ?? null;
        if ($chatId) {
            $messages = $this->firebase->getMessages($chatId);
            foreach ($messages ?? [] as $msg) {
                if (
                    isset($msg['read']) &&
                    !$msg['read'] &&
                    ($msg['mytype'] ?? '') !== 'admin'
                ) {
                    $unreadCount++;
                }
            }
        }
    }

    return response()->json(['count' => $unreadCount]);
}

    /**
     * Send a message to a specific chat.
     */
    public function sendMessage(Request $request)
    {
        $id = $request->query('id'); // Get the 'id' from the query parameter
        $type = $request->query('type'); // Get the 'type' from the query parameter
        \Log::info('Request data:', $request->all());
        if (!$id) {
            return redirect()->back()->with('error', 'Chat ID is required.');
        }

        $data = [
            'text' => $request->message,
            'sendBy' => auth()->user()->id ?? 'Admin',
            'sendByName' => auth()->user()->name ?? 'Admin', // Include the sender's name
            'createdAt' => now()->toDateTimeString(),
            'usertype' => $type, // Use the variable instead of hardcoding
            'read' => false, // Default to unread
            'mytype' => 'admin',
        ];

        // Log the data being sent
        \Log::info('Type being sent:', ['type' => $type]);
        // Add the message to Firebase
        $this->firebase->sendMessage($id, $data);

        return redirect()->route('chat.index', ['id' => $id, 'type' => $type]);
    }

    /**
     * Fetch all profiles directly from Firebase.
     */
    private function getAllProfiles()
    {
        $users = $this->firebase->getUsers(); // Fetch all users from the 'users' node
        $profiles = [];

        foreach ($users as $userId => $user) {
            $profiles[] = [
                'id' => $userId,
                'name' => $user['name'] ?? 'Unknown',
                'lastMessage' => $user['lastMessage'] ?? 'No messages yet', // Default value
                'lastMessageTime' => $user['lastMessageTime'] ?? '', // Default value
            ];
        }

        \Log::info('Profiles fetched:', $profiles); // Log profiles for debugging

        return $profiles;
    }

    public function deleteChat($userId)
{
    $database = $this->firebase->getDatabase();
    $chatRef = $database->getReference('chats/' . $userId);
    $chatRef->remove(); // This deletes the chat node
}

}