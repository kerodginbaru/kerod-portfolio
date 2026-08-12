<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
            'phone' => '0911223344',
            'subject' => 'Freelance project inquiry',
            'message' => 'I would like to discuss a business management system for my shop.',
        ], $overrides);
    }

    public function test_valid_submission_is_stored(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/contact', $this->validPayload());

        $response->assertCreated()->assertJsonPath('success', true);
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'jane@example.com',
            'status' => 'new',
        ]);
    }

    public function test_ip_address_is_never_stored_raw(): void
    {
        Mail::fake();

        $this->postJson('/api/contact', $this->validPayload());

        $stored = \App\Models\ContactMessage::first();
        $this->assertNotNull($stored->ip_hash);
        $this->assertNotEquals('127.0.0.1', $stored->ip_hash);
    }

    public function test_missing_required_fields_fail_validation(): void
    {
        $response = $this->postJson('/api/contact', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_invalid_email_fails_validation(): void
    {
        $response = $this->postJson('/api/contact', $this->validPayload(['email' => 'not-an-email']));

        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_honeypot_field_silently_drops_submission(): void
    {
        $response = $this->postJson('/api/contact', $this->validPayload(['website' => 'https://spam.example.com']));

        // Looks successful to the bot, but nothing is actually stored.
        $response->assertStatus(422); // 'website' => prohibited fails validation directly
    }

    public function test_rate_limit_blocks_after_five_submissions_per_minute(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/contact', $this->validPayload(['email' => "jane{$i}@example.com"]))
                ->assertCreated();
        }

        $this->postJson('/api/contact', $this->validPayload(['email' => 'sixth@example.com']))
            ->assertStatus(429);
    }
}
