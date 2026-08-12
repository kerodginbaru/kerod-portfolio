<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->success([
            'projects_total' => Project::count(),
            'projects_featured' => Project::featured()->count(),
            'projects_by_status' => Project::query()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'messages_unread' => ContactMessage::where('status', 'new')->count(),
            'blog_posts_published' => BlogPost::published()->count(),
            'blog_posts_draft' => BlogPost::where('status', 'draft')->count(),
            'technologies_total' => Technology::count(),
            'services_total' => Service::count(),
        ], 'Dashboard data retrieved successfully.');
    }
}
