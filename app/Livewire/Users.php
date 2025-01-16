<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class Users extends Component
{
    public function message($userId)
    {
        $autheUser = auth()->user();
        $existingConversation = Conversation::where(function ($query) use ($autheUser, $userId) {
            $query->where('sender_id', $autheUser->id)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($autheUser, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $autheUser->id);
        })->first();

        if ($existingConversation) {
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }
        $createdConversation = Conversation::create([
            'sender_id' => $autheUser->id,
            'receiver_id' => $userId
        ]);

        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }
    public function render()
    {
        return view('livewire.users', ['users' => User::where('id', '!=', auth()->id())->get()]);
    }

}
