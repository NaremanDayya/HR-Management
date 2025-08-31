<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function updateAgeThreshold(Request $request)
    {
        $request->validate([
            'max_age' => 'required|numeric|min:1|max:100',
        ]);

        Setting::updateOrCreate(
            ['key' => 'age_alert_threshold'],
            ['value' => $request->max_age]
        );

        return back()->with('success', 'تم تحديث العمر الأقصى بنجاح ✅');

    }
}
