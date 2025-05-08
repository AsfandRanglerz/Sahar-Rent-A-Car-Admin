<?php

namespace App\Http\Controllers;

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
        $messages = $id ? $this->firebase->getMessages($id) ?? [] : []; // Fetch messages only if 'id' exists
        $users = $this->firebase->getUsers(); // Fetch all profiles from the chats node

        \Log::info('Users fetched for sidebar:', $users); // Log the users for debugging

        $currentUser = $id ? collect($users)->firstWhere('id', $id) : null; // Find the current user if 'id' exists

        return view('mychat', [
            'messages' => $messages,
            'chatId' => $id,
            'users' => $users,
            'currentChatId' => $id,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Send a message to a specific chat.
     */
    public function sendMessage(Request $request)
    {
        $id = $request->query('id'); // Get the 'id' from the query parameter

        if (!$id) {
            return redirect()->back()->with('error', 'Chat ID is required.');
        }

        $data = [
            'text' => $request->message,
            'sendBy' => auth()->user()->id ?? 'guest',
            'sendByName' => auth()->user()->name ?? 'Guest', // Include the sender's name
            'createdAt' => now()->toDateTimeString(),
        ];

        // Add the message to Firebase
        $this->firebase->sendMessage($id, $data);

        return redirect()->route('chat.index', ['id' => $id]);
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
}