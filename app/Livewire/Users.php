<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Users extends Component
{
    public function message($userId){

        $authUserId=Auth::user()->id;
        $exsitConversation=Conversation::where(function ($q) use($authUserId,$userId){
            $q->where('sender_id',$authUserId)->where('receiver_id',$userId);
        })->orwhere(function ($q) use($authUserId,$userId){
            $q->where('sender_id',$userId)->where('receiver_id',$authUserId);
        })->first();


if (!$exsitConversation){
    $exsitConversation=Conversation::create([
        'sender_id'=>$authUserId,
        'receiver_id'=>$userId,
    ]);
}
return redirect()->route('chat',['query'=>$exsitConversation->id]);








    }












    public function render()
    {
        return view('livewire.users',['users'=>User::where('id','!=',Auth::user()->id)->get()]);
    }
}
