<?php

namespace Tests\Feature;

use App\Livewire\Chat\ChatList;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;


class ChatListComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_marks_sender_deleted_at_when_user_is_sender()
    {
        $user = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation=Conversation::create([
            'sender_id'=>$user->id,
            'receiver_id'=>$receiver->id,
        ]);
        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'sender_deleted_at' => null,
        ]);

        $this->actingAs($user);
        Livewire::test(ChatList::class)
            ->call('deleteByUser', $conversation->id);

        $this->assertNotNull($message->fresh()->sender_deleted_at);
    }

    /** @test */
    public function test_force_deletes_conversation_when_both_users_deleted_it()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation=Conversation::create([
            'sender_id'=>$sender->id,
            'receiver_id'=>$receiver->id,
        ]);
        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'sender_deleted_at' => now(),
            'receiver_deleted_at' => now(),
        ]);

        $this->actingAs($sender);
        Livewire::test(ChatList::class)
            ->call('deleteByUser', $conversation->id);

        $this->assertDatabaseMissing('conversations', [
            'id' => $conversation->id,
        ]);
    }
}
