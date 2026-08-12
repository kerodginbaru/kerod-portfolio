<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    use ApiResponse;

    public function store(ContactRequest $request): JsonResponse
    {
        // Honeypot: bots fill every field, including the hidden "website"
        // input real visitors never see. Pretend success without storing
        // anything, so the bot doesn't learn to adapt.
        if ($request->filled('website')) {
            return $this->success(null, 'Message sent successfully.', 201);
        }

        $contactMessage = ContactMessage::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'phone' => $request->string('phone') ?: null,
            'subject' => $request->string('subject'),
            'message' => $request->string('message'),
            'status' => 'new',
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
        ]);

        $notifyEmail = config('mail.contact_notification_email');
        if ($notifyEmail) {
            Mail::to($notifyEmail)->queue(new NewContactMessage($contactMessage));
        }

        return $this->success(null, 'Message sent successfully. I\'ll get back to you soon.', 201);
    }
}
