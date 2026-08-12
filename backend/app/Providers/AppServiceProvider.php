<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;
use App\Policies\BlogPostPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\EducationPolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ServicePolicy;
use App\Policies\SkillPolicy;
use App\Policies\SocialLinkPolicy;
use App\Policies\TestimonialPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(Skill::class, SkillPolicy::class);
        Gate::policy(Experience::class, ExperiencePolicy::class);
        Gate::policy(Education::class, EducationPolicy::class);
        Gate::policy(BlogPost::class, BlogPostPolicy::class);
        Gate::policy(Testimonial::class, TestimonialPolicy::class);
        Gate::policy(SocialLink::class, SocialLinkPolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);

        // 5 contact-form submissions per minute per IP — generous enough
        // for a real visitor, tight enough to blunt scripted spam.
        RateLimiter::for('contact', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // 10 login attempts per minute per IP, independent of Laravel's
        // per-account throttling in AdminAuthController.
        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Default API throttle applied globally in bootstrap/app.php.
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });
    }
}
