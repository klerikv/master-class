<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('min_words', function ($attribute, $value, $parameters, $validator) {
            $words = preg_split('/\s+/', trim($value));
            $words = array_filter($words);
            return count($words) >= ($parameters[0] ?? 2);
        });
        
        Validator::replacer('min_words', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':min', $parameters[0] ?? 2, 'Поле должно содержать минимум :min слова.');
        });
    }
}
