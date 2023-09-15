<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\IncomingExceptionEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment('local')) {
                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag() ||
                   $entry->type === EntryType::BATCH ||
                   $entry->type === EntryType::DUMP ||
                   $entry->type === EntryType::EVENT ||
                   $entry->type === EntryType::REQUEST ||
                   $entry->type === EntryType::LOG ||
                   $entry->type === EntryType::MAIL ||
                   $entry->type === EntryType::MODEL ||
                   $entry->type === EntryType::QUERY ||
                   $entry->type === EntryType::REDIS ||
                   $entry->type === EntryType::JOB ||
                   $entry->type === EntryType::GATE ||
                   $entry->type === EntryType::VIEW ||
                   $entry->type === EntryType::SCHEDULED_TASK ||
                   $entry->type === EntryType::EXCEPTION ||
                   $entry->type === EntryType::CLIENT_REQUEST ||
                   $entry->type === EntryType::CACHE ||
                   $entry->type === EntryType::NOTIFICATION ||
                   $entry->type === EntryType::COMMAND;
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                    '1521910992@qq.com',
                    'shiquansong@qq.com'
            ]);
        });
    }
}
