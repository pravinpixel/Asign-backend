<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

        Validator::extend('startsWithProductName', function ($attribute, $value, $parameters, $validator) {
           
            $productId = $parameters[0];
            $productName = Product::findOrFail($productId)->name;
            // $productCodePrefix = substr($value, 0, strlen($productName));
            // $expectedPrefix = '';
        
            switch ($productName) {

                case 'Inventory Labels':
                    if (preg_match('/^INENV/', $value)){
                        return true;
                    }
                    break;
                case 'Authentication Labels':
                    if (preg_match('/^AUENV/', $value)){
                        return true;
                    }
                    break;
            }
        
        
            return false;
        });
        
        // Validator::extend('startsWithProductName', function ($attribute, $value, $parameters, $validator) {
        //     $productId = $parameters[0];
        //     $productCodePrefix = substr($value, 0, 5);
        //     $productName = Product::findOrFail($productId)->name;
        //     $productNamePrefix = strtoupper(substr($productName, 0, 4));
        //     return $productCodePrefix === $productNamePrefix;
        // });
    }
}
