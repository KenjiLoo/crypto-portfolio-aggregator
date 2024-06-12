<?php

namespace App\Providers;

use App\Sanctum\PersonalAccessToken;
use App\Validators\CustomValidator;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobFailed;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        app('validator')->resolver(function ($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
            return new CustomValidator($translator, $data, $rules, $messages, $customAttributes);
        });

        Queue::failing(function (JobFailed $event) {
            log_error('[JOB FAILED]' . json_encode([
                'e' => $event->exception->getMessage(),
                'job' => $event->job->resolveName(),
                'payload' => $event->job->getRawBody(),
                'trace' => $event->exception->getTraceAsString(),
            ], JSON_PRETTY_PRINT), 'error');
        });
    }
}
