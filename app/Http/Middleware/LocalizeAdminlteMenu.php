<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizeAdminlteMenu
{
    public function handle(Request $request, Closure $next): Response
    {
        // This runs AFTER SetLocale has already set the session locale,
        // so __() calls here resolve to the correct language.
        config(['adminlte.menu' => $this->translatedMenu()]);

        return $next($request);
    }

    private function translatedMenu(): array
    {
        return [
            ['type' => 'navbar-search',    'text' => __('app.search'),       'topnav_right' => true],
            ['type' => 'fullscreen-widget',                                   'topnav_right' => true],
            ['text' => __('app.my_profile'), 'route' => 'admin.profile', 'icon' => 'fas fa-fw fa-user', 'type' => 'navbar-user'],

            ['text' => __('app.dashboard'), 'icon' => 'fas fa-fw fa-tachometer-alt', 'route' => 'admin.dashboard', 'classes' => 'nav-item-dashboard'],

            [
                'text' => __('app.donors'), 'icon' => 'fas fa-fw fa-users', 'icon_color' => '#28a745',
                'submenu' => [
                    ['text' => __('app.all_donors'),      'route' => 'admin.donors.index',          'icon' => 'fas fa-fw fa-address-book'],
                    ['text' => __('app.blood_donations'), 'route' => 'admin.blood-donations.index',  'icon' => 'fas fa-fw fa-tint'],
                    ['text' => __('app.money_donations'), 'route' => 'admin.money-donations.index',  'icon' => 'fas fa-fw fa-hand-holding-usd'],
                    ['text' => __('app.donor_cards'),     'route' => 'admin.donor-cards.index',      'icon' => 'fas fa-fw fa-id-card'],
                    ['text' => __('app.donor_stories'),   'route' => 'admin.donor-stories.index',    'icon' => 'fas fa-fw fa-quote-right'],
                ],
            ],
            [
                'text' => __('app.operations'), 'icon' => 'fas fa-fw fa-cogs', 'icon_color' => '#17a2b8',
                'submenu' => [
                    ['text' => __('app.blood_requests'),  'route' => 'admin.blood-requests.index',   'icon' => 'fas fa-fw fa-phone'],
                    ['text' => __('app.call_logs'),        'route' => 'admin.call-logs.index',        'icon' => 'fas fa-fw fa-list'],
                    ['text' => __('app.blood_inventory'),  'route' => 'admin.blood-inventory.index',  'icon' => 'fas fa-fw fa-warehouse'],
                    ['text' => __('app.campaigns'),        'route' => 'admin.campaigns.index',        'icon' => 'fas fa-fw fa-calendar-alt'],
                ],
            ],
            [
                'text' => __('app.analytics'), 'icon' => 'fas fa-fw fa-chart-line', 'icon_color' => '#ffc107',
                'submenu' => [
                    ['text' => __('app.reports'),     'route' => 'admin.reports.index',     'icon' => 'fas fa-fw fa-chart-bar'],
                    ['text' => __('app.audit_trail'), 'route' => 'admin.audit-logs.index',  'icon' => 'fas fa-fw fa-history'],
                ],
            ],
            [
                'text' => __('app.administration'), 'icon' => 'fas fa-fw fa-shield-alt', 'icon_color' => '#6c757d',
                'submenu' => [
                    ['text' => __('app.staff'),        'route' => 'admin.staff.index',        'icon' => 'fas fa-fw fa-user-shield', 'can' => 'manage-staff'],
                    ['text' => __('app.roles'),        'route' => 'admin.roles.index',        'icon' => 'fas fa-fw fa-shield-alt',  'can' => 'manage-settings'],
                    ['text' => __('app.permissions'),  'route' => 'admin.permissions.index',  'icon' => 'fas fa-fw fa-key',         'can' => 'manage-settings'],
                    ['text' => __('app.cities'),       'route' => 'admin.cities.index',       'icon' => 'fas fa-fw fa-city'],
                    ['text' => __('app.areas'),        'route' => 'admin.areas.index',        'icon' => 'fas fa-fw fa-map'],
                    ['text' => __('app.settings'),     'route' => 'admin.settings.index',     'icon' => 'fas fa-fw fa-cogs',        'can' => 'manage-settings'],
                    ['text' => __('app.landing_page'), 'route' => 'admin.landing-page.index', 'icon' => 'fas fa-fw fa-landmark'],
                ],
            ],
        ];
    }
}
