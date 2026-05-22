<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Get all settings and transform them into an associative array
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Fallback to env if database is empty for these specific keys
        if (! isset($settings['META_PIXEL_ID'])) {
            $settings['META_PIXEL_ID'] = env('META_PIXEL_ID');
        }
        if (! isset($settings['GOOGLE_ANALYTICS_ID'])) {
            $settings['GOOGLE_ANALYTICS_ID'] = env('GOOGLE_ANALYTICS_ID');
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'meta_pixel_id' => 'nullable|string',
            'google_analytics_id' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            $settingKey = strtoupper($key);
            $setting = Setting::where('key', $settingKey)->first();
            $oldValue = $setting ? $setting->value : null;

            Setting::updateOrCreate(
                ['key' => $settingKey],
                ['value' => $value]
            );

            if ($oldValue !== $value) {
                ActivityLogger::log('setting_updated', "Modification du paramètre {$settingKey} : ".($oldValue ?? 'Non défini').' -> '.($value ?? 'Non défini'));
            }
        }

        return redirect()->back()->with('success', 'Paramètres de tracking mis à jour avec succès !');
    }
}
