<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppSettingController extends Controller
{
    public function edit(): View
    {
        $settings = AppSetting::values([
            'store_name' => 'StockCashier Store',
            'store_address' => '',
            'store_phone' => '',
            'store_email' => '',
            'receipt_footer' => 'Terima kasih sudah berbelanja.',
            'currency_prefix' => 'Rp',
        ]);

        return view('pages.admin.settings.edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_address' => ['nullable', 'string', 'max:1000'],
            'store_phone' => ['nullable', 'string', 'max:100'],
            'store_email' => ['nullable', 'email', 'max:255'],
            'receipt_footer' => ['nullable', 'string', 'max:1000'],
            'currency_prefix' => ['required', 'string', 'max:10'],
        ]);

        $oldSettings = AppSetting::values();

        foreach ($validated as $key => $value) {
            AppSetting::setValue($key, $value);
        }

        $activityLog->log(
            event: 'settings_updated',
            description: 'Application settings diperbarui.',
            subject: null,
            properties: [
                'old' => $oldSettings,
                'new' => $validated,
            ],
            user: $request->user(),
        );

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Settings berhasil diperbarui.');
    }
}