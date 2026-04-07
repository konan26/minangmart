<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\NavigationMenu::create([
            'label' => 'DASHBOARD',
            'route' => 'admin.dashboard',
            'active_key' => 'dashboard',
            'icon' => 'fas fa-th-large',
            'sort_order' => 1,
        ]);

        \App\Models\NavigationMenu::create([
            'label' => 'KELOLA PETUGAS',
            'route' => 'admin.petugas.index',
            'active_key' => 'petugas',
            'icon' => 'fas fa-users-cog',
            'sort_order' => 2,
        ]);

        \App\Models\NavigationMenu::create([
            'label' => 'ACTIVITY LOGS',
            'route' => 'admin.activity_logs.index',
            'active_key' => 'activity_logs',
            'icon' => 'fas fa-history',
            'sort_order' => 3,
        ]);

        \App\Models\NavigationMenu::create([
            'label' => 'REPORTS',
            'route' => 'admin.reports.index',
            'active_key' => 'reports',
            'icon' => 'fas fa-chart-line',
            'sort_order' => 4,
        ]);
    }
}
