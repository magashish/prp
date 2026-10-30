<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $adminEmail = Setting::where('key', 'admin_email')->value('value') ?? config('mail.admin_email');
        $parkingCompanyEmail = Setting::where('key', 'parking_company_email')->value('value') ?? config('mail.parking_company_email');

        return view('admin.settings', compact('adminEmail', 'parkingCompanyEmail'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'admin_email'           => 'required|email|max:255',
            'parking_company_email' => 'required|email|max:255',
        ]);

        Setting::updateOrCreate(['key' => 'admin_email'], ['value' => $request->admin_email]);
        Setting::updateOrCreate(['key' => 'parking_company_email'], ['value' => $request->parking_company_email]);

        return redirect()->route('admin.settings.edit')->with('success', 'Email contacts updated successfully!');
    }
}
