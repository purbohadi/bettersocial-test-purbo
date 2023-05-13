<?php

namespace App\Providers;

use App\Exceptions\PrevalidationPassedException;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        $this->app->afterResolving(ValidatesWhenResolved::class, function ($request) {
            $request->throwIfPrevalidate();
        });
 
        Request::macro('throwIfPrevalidate', function () {
            if ($this->has('prevalidate')) {
                throw new PrevalidationPassedException;
            }
        });

        Request::macro('validate', function (array $rules, ...$params) {
            validator()->validate($this->all(), $rules, ...$params);
            if ($this->has('prevalidate')) {
                throw new PrevalidationPassedException;
            }
        });        
    }
}
