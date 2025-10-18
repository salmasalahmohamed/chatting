<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => 1,
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'body' => $this->faker->sentence(),
            'read_at' => null,
            'receiver_deleted_at' => null,
            'sender_deleted_at' => null,
        ];
    }
}
