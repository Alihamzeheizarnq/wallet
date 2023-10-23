<?php

namespace App\Providers;

use App\Models\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class GlobalModelEventListener extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Event::listen('eloquent.saved*', function ($event, $data) {
            $model = array_shift($data);
            $modelName = strtolower(class_basename($model));

            if ($modelName !== 'log' && !$this->app->runningInConsole()) {
                Log::create([
                    'action' => 'saved',
                    'entity_type' => $modelName,
                    'entity_id' => $model->id,
                    'user_id' => auth()?->user()?->id ?? $model->user_id,
                    'after_changes' => json_encode($model->getAttributes()),
                    'before_changes' => '{}',
                ]);
            }


        });

        Event::listen('eloquent.updating*', function ($event, $data) {
            $model = array_shift($data);

            $after = array_diff($model->getAttributes(), $model->getOriginal());
            $before = array_diff($model->getOriginal(), $model->getAttributes());

            Log::create([
                'action' => 'updated',
                'entity_type' => strtolower(class_basename($model)),
                'entity_id' => $model->id,
                'user_id' => auth()->user()->id,
                'before_changes' => json_encode($before),
                'after_changes' => json_encode($after)
            ]);

        });


        Event::listen('eloquent.destroying*', function ($event, $data) {
            $model = array_shift($data);

            Log::create([
                'action' => 'deleted',
                'entity_type' => strtolower(class_basename($model)),
                'entity_id' => $model->id,
                'user_id' => auth()->user()->id,
                'before_changes' => json_encode($model->getOriginal()),
            ]);
        });
    }
}
