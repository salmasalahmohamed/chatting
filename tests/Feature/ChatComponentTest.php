<?php

namespace Tests\Feature;

use App\Livewire\Chat\Chat;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;


class ChatComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_marks_unread_messages_as_read_when_mount()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation=Conversation::create([
            'sender_id'=>$user1->id,
            'receiver_id'=>$user2->id,
        ]);
        $message1 = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'receiver_id' => $user2->id,
            'read_at' => null,
        ]);

        $message2 = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'receiver_id' => $user2->id,
            'read_at' => null,
        ]);

        $this->actingAs($user2);

        Livewire::test(Chat::class, ['query' => $conversation->id])
            ->assertStatus(200);

        $this->assertNotNull($message1->fresh()->read_at);
        $this->assertNotNull($message2->fresh()->read_at);
    }
}
