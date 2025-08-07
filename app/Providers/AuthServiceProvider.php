<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Subject::class => \App\Policies\SubjectPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 