<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);
    
        $settings = Setting::first(); //Get existing settings
    
        if (!$settings) {
            $settings = new Setting(); //Create if not exists
        }
    
        //Save new logo if uploaded
        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/AppLogo/' . $filename;
    
            $file->move(public_path('uploads/AppLogo/'), $filename);
    
            //Delete the old file if it exists
            if ($settings->app_logo && File::exists(public_path($settings->app_logo))) {
                File::delete(public_path($settings->app_logo));
            }
    
            $settings->app_logo = $path;
        }
    
        //Assign all form values to settings
        $settings->app_name = $request->app_name;
        $settings->company_name = $request->company_name;
        $settings->company_phone = $request->company_phone;
        $settings->country = $request->country;
        $settings->state = $request->state;
        $settings->city = $request->city;
        $settings->zip_code = $request->zip_code;
        $settings->fax_number = $request->fax_number;
        $settings->company_address = $request->company_address;
    
        $settings->save(); //Save to database
    
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
    

}
