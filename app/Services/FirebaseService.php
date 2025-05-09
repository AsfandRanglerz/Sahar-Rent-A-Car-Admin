<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $serviceAccountPath = base_path('storage/firebase/firebase_credentials.json'); // Your downloaded service account key

        // ✅ Set the correct database URL here
        $databaseUrl = 'https://sahar-rent-a-car-default-rtdb.firebaseio.com';

        $factory = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri($databaseUrl); // <--- add this line

        $this->database = $factory->createDatabase();
    }

    public function sendMessage($chatId, $data)
    {
        // Push the message to the Firebase database
        $this->database
            ->getReference('chats/' . $chatId)
            ->push($data);

        // Log the message for debugging
        \Log::info("Message sent to Chat ID {$chatId}:", $data);

        return true;
    }

    public function getUsers(): array
    {
        $chats = $this->database
            ->getReference('chats') // Fetch all chats from the 'chats' node
            ->getValue() ?? []; // Return an empty array if no chats are found

        $users = [];
        foreach ($chats as $userId => $chat) {
            if (is_array($chat)) {
                // Get the last message
                $lastMessage = end($chat);

                // Count unread messages
                $unreadCount = 0;
                foreach ($chat as $message) {
                    if (isset($message['read']) && !$message['read'] && (!auth()->check() || $message['sendBy'] !== auth()->user()->id)) {
                        $unreadCount++;
                    }
                }

                $users[] = [
                    'id' => $userId,
                    'name' => "User {$userId}", // Placeholder name, replace with actual name if available
                    'lastMessage' => $lastMessage['text'] ?? 'No messages yet',
                    'lastMessageTime' => $lastMessage['createdAt'] ?? 'N/A',
                    'usertype' => $lastMessage['usertype'] ?? 'default', // Include the type
                    'mytype' => $lastMessage['mytype'] ?? '', // Include the mytype value
                    'unreadCount' => $unreadCount, // Add unread message count
                ];
            } else {
                $users[] = [
                    'id' => $userId,
                    'name' => "User {$userId}",
                    'lastMessage' => 'No messages yet',
                    'lastMessageTime' => 'N/A',
                    'usertype' => 'N/A', // Default type if no messages exist
                    'mytype' => '', // Default mytype if no messages exist
                    'unreadCount' => 0, // Default unread count
                ];
            }
        }

        \Log::info('All users fetched from Firebase:', ['users' => $users]);

        return $users; // Ensure an array is always returned
    }
    public function getMessages($chatId)
    {
        $messages = $this->database
            ->getReference('chats/' . $chatId)
            ->getValue() ?? []; // Return an empty array if no messages are found

        if (!is_array($messages)) {
            \Log::warning("Invalid messages format for Chat ID {$chatId}.");
            return []; // Return an empty array if the format is invalid
        }

        \Log::info("Messages for Chat ID {$chatId}:", ['messages' => $messages]);

        return $messages;
    }

    public function getChatParticipants()
    {
        $chats = $this->database
            ->getReference('chats')
            ->getValue() ?? []; // Fetch all chats or return an empty array

        if (empty($chats)) {
            \Log::warning('No chats found in Firebase.');
            return []; // Return an empty array if no chats are found
        }

        $participants = [];
        foreach ($chats as $chatId => $messages) {
            if (!is_array($messages)) {
                \Log::warning("Invalid messages format for Chat ID {$chatId}.");
                continue; // Skip invalid message formats
            }

            foreach ($messages as $message) {
                if (isset($message['sendBy'])) {
                    $participants[$message['sendBy']] = [
                        'id' => $message['sendBy'],
                        'name' => $message['sendByName'] ?? 'Unknown', // Assuming 'sendByName' exists in Firebase
                    ];
                }
            }
        }

        return array_values($participants); // Return unique participants as an array
    }

    public function markMessageAsRead($chatId, $messageKey)
    {
        $this->database
            ->getReference("chats/{$chatId}/{$messageKey}/read")
            ->set(true);

        \Log::info("Message {$messageKey} in Chat ID {$chatId} marked as read.");
    }
}