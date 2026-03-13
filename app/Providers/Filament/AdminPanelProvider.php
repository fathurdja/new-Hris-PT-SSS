<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('HRIS PT. SSS')
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_START,
                fn (): string => Blade::render('
                    <style>
                        body {
                            background-image: url("/bg.png") !important;
                            background-size: cover !important;
                            background-position: center !important;
                            background-attachment: fixed !important;
                        }
                        /* Glassmorphism for Filament panels to make text readable */
                        .fi-main {
                            background: rgba(255, 255, 255, 0.85) !important;
                            backdrop-filter: blur(12px);
                            border-radius: 1rem;
                            margin: 1rem;
                            min-height: calc(100vh - 2rem);
                        }
                        .fi-topbar {
                            background: rgba(255, 255, 255, 0.75) !important;
                            backdrop-filter: blur(12px);
                        }
                        .fi-sidebar {
                            background: rgba(255, 255, 255, 0.9) !important;
                            backdrop-filter: blur(16px);
                        }
                        div[role="dialog"] {
                            background: rgba(255, 255, 255, 0.85) !important;
                            backdrop-filter: blur(16px);
                        }
                        @media (prefers-color-scheme: dark) {
                            .fi-main, .fi-topbar, .fi-sidebar, div[role="dialog"] {
                                background: rgba(17, 24, 39, 0.85) !important;
                            }
                        }
                    </style>
                ')
            )
            ->colors([
                'primary' => '#003049',
                'danger' => '#D62828',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
