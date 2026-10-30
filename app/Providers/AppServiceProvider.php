<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->shareShopData();
    }

    /**
     * ข้อมูลที่ทุกหน้าใช้ร่วมกัน
     * - favoriteIds: id สินค้าที่อยู่ในรายการโปรด (ทำให้หัวใจบนการ์ดเป็นสีชมพู)
     * - cartCount: จำนวนชิ้นในตะกร้า (โชว์บนปุ่มตะกร้าที่ header)
     */
    protected function shareShopData(): void
    {
        View::composer(['partials.product-card', 'products.show'], function ($view) {
            $view->with('favoriteIds', once(fn () => auth()->check()
                ? auth()->user()->wishlist()->pluck('products.id')->all()
                : []));
        });

        View::composer('layouts.app', function ($view) {
            $view->with('cartCount', auth()->check()
                ? (int) auth()->user()->cartProducts()->sum('cart_items.quantity')
                : 0);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
