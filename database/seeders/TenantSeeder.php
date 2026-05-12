<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['name' => 'Cliente Demo'],
            [
                'country' => 'AR',
                'currency' => 'ARS',
                'productive_hours_month' => 160,
                'active' => true,
            ]
        );

        $defaultSettings = [
            'price_alert_days' => '30',
            'min_margin_alert_pct' => '20',
        ];

        foreach ($defaultSettings as $key => $value) {
            $tenant->settings()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        $this->command->info("Tenant \"{$tenant->name}\" listo (ID: {$tenant->id}).");
    }
}
