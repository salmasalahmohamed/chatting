<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body = '';
    public $loadMessages;

public function loadMessage(){

    $userId = auth()->id();


    $this->loadMessages = Message::where('conversation_id', $this->selectedConversation->id)
        ->where(function ($query) use ($userId) {

            $query->where('sender_id', $userId)
                ->whereNull('sender_deleted_at');
        })->orWhere(function ($query) use ($userId) {

            $query->where('receiver_id', $userId)
                ->whereNull('receiver_deleted_at');
        })

        ->get();


    return $this->loadMessages;
}
public function mount(){
    $this->loadMessage();
}
    public function save()
    {
        $this->validate(['body'=>'required|string']);
        $createdMessage =  Message::create([
            'body' => $this->body,
            'sender_id' => Auth::user()->id,
            'receiver_id'=>$this->selectedConversation->receiver()->id,
            'conversation_id'=>$this->selectedConversation->id
        ]);
        $this->selectedConversation->updated_at=now();
        $this->selectedConversation->save();
        $this->loadMessage();
        $this->selectedConversation->receiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->receiver()->id

            ));
        $this->redirect(''.$this->selectedConversation->id);
    }

    public function getListeners()
    {

        $auth_id = auth()->user()->id;

        return [

            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'

        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {

            if ($event['conversation_id'] == $this->selectedConversation->id) {


                $newMessage = Message::find($event['message_id']);

                $newMessage->read_at = now();
                $newMessage->save();

                $this->loadMessages->push($newMessage);



            }
        }
    }
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
