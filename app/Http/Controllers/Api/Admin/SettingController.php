<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(Setting::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key'   => 'required|string|unique:settings,key|max:255',
            'value' => 'nullable|string',
            'tipe'  => 'nullable|string'
        ]);

        $setting = Setting::create($validated);
        return response()->json($setting, 201);
    }

    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        $validated = $request->validate([
            'key'   => 'required|string|max:255|unique:settings,key,'.$id,
            'value' => 'nullable|string',
            'tipe'  => 'nullable|string'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $validated['value'] = '/uploads/settings/' . $filename;
        }

        $setting->update($validated);
        return response()->json($setting);
    }

    public function bulkUpdate(Request $request)
    {
        $settingsData = $request->all();
        foreach ($settingsData as $key => $value) {
            // Jika valuenya adalah file
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $value = '/uploads/settings/' . $filename;
            }
            
            // Skip update if value is exactly string 'null' or empty and we're not explicitly clearing it?
            // Actually, we should update.
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(null, 204);
    }
}
