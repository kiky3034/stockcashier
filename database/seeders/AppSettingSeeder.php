<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'store_name' => 'StockCashier Store',
            'store_address' => 'Jl. Contoh No. 123',
            'store_phone' => '081234567890',
            'store_email' => 'store@stockcashier.test',
            'receipt_footer' => 'Terima kasih sudah berbelanja.',
            'currency_prefix' => 'Rp',
        ];

        foreach ($settings as $key => $value) {
            AppSetting::setValue($key, $value);
        }
    }
}