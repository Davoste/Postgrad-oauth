<?php

namespace App\Providers;

use App\Models\SupervisionMeeting;
use App\Models\Thesis;
use App\Policies\SupervisionMeetingPolicy;
use App\Policies\ThesisPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Thesis::class            => ThesisPolicy::class,
        SupervisionMeeting::class => SupervisionMeetingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
