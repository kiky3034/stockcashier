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
            'store_logo' => null,
            'receipt_footer' => 'Terima kasih sudah berbelanja.',
            'currency_prefix' => 'Rp',
            'receipt_paper_size' => '80mm',
            'receipt_auto_print' => 'false',
            'receipt_show_logo' => 'true',
        ];

        foreach ($settings as $key => $value) {
            AppSetting::setValue($key, $value);
        }
    }
}