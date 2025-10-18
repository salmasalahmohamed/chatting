<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded=[];
    public function message(){
        return $this->hasMany(Message::class);
    }
    public function user(){
        return$this->belongsToMany(User::class);
    }
    public function receiver()
    {

        if ($this->sender_id === auth()->id()) {

            return User::firstWhere('id',$this->receiver_id);

        } else {

            return User::firstWhere('id',$this->sender_id);
        }


    }
}
