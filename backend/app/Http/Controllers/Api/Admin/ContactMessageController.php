<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactMessageRequest;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ContactMessage::class);

        $query = ContactMessage::query()->orderByDesc('created_at');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $messages = $query->paginate($request->integer('per_page', 20));

        return $this->success(ContactMessageResource::collection($messages), 'Messages retrieved successfully.');
    }

    public function show(ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('view', $contactMessage);

        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
        }

        return $this->success(new ContactMessageResource($contactMessage), 'Message retrieved successfully.');
    }

    public function update(UpdateContactMessageRequest $request, ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('update', $contactMessage);

        $contactMessage->update($request->validated());

        return $this->success(new ContactMessageResource($contactMessage), 'Message updated successfully.');
    }

    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('delete', $contactMessage);

        $contactMessage->delete();

        return $this->success(null, 'Message deleted successfully.');
    }
}
