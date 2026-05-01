<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function edit(): View
    {
        $settings = AppSetting::values([
            'store_name' => 'StockCashier Store',
            'store_address' => '',
            'store_phone' => '',
            'store_email' => '',
            'store_logo' => null,
            'receipt_footer' => 'Terima kasih sudah berbelanja.',
            'currency_prefix' => 'Rp',
            'receipt_paper_size' => '80mm',
            'receipt_auto_print' => 'false',
            'receipt_show_logo' => 'true',
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
            'store_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'receipt_footer' => ['nullable', 'string', 'max:1000'],
            'currency_prefix' => ['required', 'string', 'max:10'],
            'receipt_paper_size' => ['required', 'in:58mm,80mm'],
            'receipt_auto_print' => ['nullable', 'boolean'],
            'receipt_show_logo' => ['nullable', 'boolean'],
        ]);

        $oldSettings = AppSetting::values();
        if ($request->hasFile('store_logo')) {
            $oldLogo = $oldSettings['store_logo'] ?? null;

            $validated['store_logo'] = $request->file('store_logo')->store('settings', 'public');

            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
        } else {
            unset($validated['store_logo']);
        }

        $validated['receipt_auto_print'] = $request->boolean('receipt_auto_print') ? 'true' : 'false';
        $validated['receipt_show_logo'] = $request->boolean('receipt_show_logo') ? 'true' : 'false';

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