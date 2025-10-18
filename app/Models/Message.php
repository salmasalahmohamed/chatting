<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Message extends Model
{
    use HasFactory, Notifiable;

    protected $guarded=[];
    protected $dates=['read_at','receiver_deleted_at','sender_deleted_at'];

    public function conversation(){
    return $this->belongsTo(Conversation::class);
}

    public function isRead(){
        return $this->read_at !=null;
    }

    public function sender()
    {

        return $this->belongsTo(User::class, 'sender_id');
    }


    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }


}
