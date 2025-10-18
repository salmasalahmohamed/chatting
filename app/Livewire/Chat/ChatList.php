<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;

    public function deleteByUser($id)
    {
        $userId = auth()->id();
        $conversation = Conversation::find($id);


        $conversation->message()->each(function ($message) use ($userId) {

            if ($message->sender_id === $userId) {

                $message->update(['sender_deleted_at' => now()]);
            } elseif ($message->receiver_id === $userId) {

                $message->update(['receiver_deleted_at' => now()]);
            }


        });



        $receiverAlsoDeleted =$conversation->message()
            ->where(function ($query) use($userId){

                $query->where('sender_id',$userId)
                    ->orWhere('receiver_id',$userId);

            })->where(function ($query) use($userId){

                $query->whereNull('sender_deleted_at')
                    ->orWhereNull('receiver_deleted_at');

            })->doesntExist();



        if ($receiverAlsoDeleted) {

            $conversation->forceDelete();
        }



//        return redirect(route('chat.index'));



    }
    public function render()
    {

        $user=Auth::user();
        return view('livewire.chat.chat-list',['conversations'=>$user->conversation()->latest('updated_at')->get(),]);
    }
}
