<?php

namespace Tests\Feature;

use App\Livewire\Chat\ChatBox;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Livewire\Livewire;


class ChatBoxComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_loads_messages_for_the_selected_conversation()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation=Conversation::create([
            'sender_id'=>$sender->id,
            'receiver_id'=>$receiver->id,
        ]);
        $message1 = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'sender_deleted_at' => null,
        ]);

        $message2 = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $receiver->id,
            'receiver_id' => $sender->id,
            'receiver_deleted_at' => null,
        ]);

        Livewire::actingAs($sender)
            ->test(ChatBox::class, ['selectedConversation' => $conversation])
            ->assertSee($message1->body);
    }
    /** @test */
    public function test_sends_a_new_message_and_notifies_receiver()
    {
        //
        Notification::fake();

        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation=Conversation::create([
            'sender_id'=>$sender->id,
            'receiver_id'=>$receiver->id,
        ]);

        $conversation->setRelation('receiver', $receiver);

        Livewire::actingAs($sender)
            ->test(ChatBox::class, ['selectedConversation' => $conversation])
            ->set('body', 'Hello there!')
            ->call('save');

        $this->assertDatabaseHas('messages', [
            'body' => 'Hello there!',
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'conversation_id' => $conversation->id,
        ]);

        Notification::assertSentTo($receiver, MessageSent::class);
    }
}
