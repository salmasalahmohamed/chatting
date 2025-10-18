<?php

namespace Tests\Feature;

use App\Livewire\Chat\ChatBox;
use App\Livewire\Users;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;


class UsersComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_if_user_message_exist_conversation_to_same_user()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation=Conversation::create([
            'sender_id'=>$sender->id,
            'receiver_id'=>$receiver->id,
        ]);
        Livewire::actingAs($sender)
            ->test(Users::class)
            ->call('message', $receiver->id)
            ->assertRedirect(route('chat', ['query' => $conversation->id]));


    }
}
