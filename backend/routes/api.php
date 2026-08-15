<?php

use App\Http\Controllers\Api\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Api\Admin\ContactMessageController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\EducationController as AdminEducationController;
use App\Http\Controllers\Api\Admin\ExperienceController as AdminExperienceController;
use App\Http\Controllers\Api\Admin\ProjectCategoryController;
use App\Http\Controllers\Api\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Api\Admin\ProjectImageController;
use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Api\Admin\SkillCategoryController;
use App\Http\Controllers\Api\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Api\Admin\SocialLinkController as AdminSocialLinkController;
use App\Http\Controllers\Api\Admin\TechnologyController as AdminTechnologyController;
use App\Http\Controllers\Api\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SocialLinkController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
| Read-only, no authentication. Cached at the HTTP layer by the frontend's
| fetch `revalidate` option; add response caching here later if traffic
| warrants it.
*/

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/featured', [ProjectController::class, 'featured']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);

Route::get('/technologies', [TechnologyController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/experience', [ExperienceController::class, 'index']);
Route::get('/education', [EducationController::class, 'index']);

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{blogPost}', [BlogController::class, 'show']);

Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/social-links', [SocialLinkController::class, 'index']);
Route::get('/site-settings', [SiteSettingController::class, 'index']);

// Tightly rate limited: 5 submissions per minute per IP, defined in
// bootstrap/app.php / RouteServiceProvider-equivalent throttle config.
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact');

/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::put('/password', [AdminAuthController::class, 'changePassword']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::apiResource('projects', AdminProjectController::class)->except(['show'])->names('admin.projects');
        Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('admin.projects.show');
        Route::post('/projects/{project}/restore', [AdminProjectController::class, 'restore']);
        Route::patch('/projects/{project}/toggle-featured', [AdminProjectController::class, 'toggleFeatured']);
        Route::post('/projects/reorder', [AdminProjectController::class, 'reorder']);

        Route::post('/projects/{project}/images', [ProjectImageController::class, 'store']);
        Route::delete('/projects/{project}/images/{image}', [ProjectImageController::class, 'destroy']);
        Route::patch('/projects/{project}/images/{image}/cover', [ProjectImageController::class, 'setCover']);
        Route::post('/projects/{project}/images/reorder', [ProjectImageController::class, 'reorder']);

        Route::apiResource('project-categories', ProjectCategoryController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.project-categories');

        Route::apiResource('technologies', AdminTechnologyController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.technologies');

        Route::apiResource('services', AdminServiceController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.services');

        Route::apiResource('skill-categories', SkillCategoryController::class)
            ->only(['index', 'store', 'destroy'])
            ->names('admin.skill-categories');
        Route::apiResource('skills', AdminSkillController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('admin.skills');

        Route::apiResource('experience', AdminExperienceController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.experience');

        Route::apiResource('education', AdminEducationController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.education');

        Route::apiResource('blog', AdminBlogPostController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.blog');

        Route::apiResource('testimonials', AdminTestimonialController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.testimonials');

        Route::apiResource('social-links', AdminSocialLinkController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.social-links');
        Route::post('/social-links/reorder', [AdminSocialLinkController::class, 'reorder']);

        Route::get('/site-settings', [AdminSiteSettingController::class, 'index']);
        Route::put('/site-settings', [AdminSiteSettingController::class, 'update']);
        Route::post('/site-settings/photo', [AdminSiteSettingController::class, 'uploadPhoto']);

        Route::apiResource('messages', ContactMessageController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->names('admin.messages');
    });
});
