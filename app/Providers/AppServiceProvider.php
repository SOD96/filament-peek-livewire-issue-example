<?php

namespace App\Providers;

use App\Filament\Blocks\TipTapBlocks\FormBlock;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        TiptapEditor::configureUsing(function (TiptapEditor $component) {
            $component->blocks([
                FormBlock::class,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
