<?php
// app/Http/Controllers/Admin/DriverController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::withCount('jadwals')->orderBy('nama')->get();
        return view('admin.driver.index', compact('drivers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:100',
            'no_whatsapp'     => 'required|string|unique:drivers,no_whatsapp',
            'no_kendaraan'    => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
        ]);

        Driver::create($data);
        return back()->with('success', 'Driver berhasil ditambahkan.');
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:100',
            'no_whatsapp'     => 'required|string|unique:drivers,no_whatsapp,' . $driver->id,
            'no_kendaraan'    => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'is_active'       => 'boolean',
        ]);

        $driver->update($data);
        return back()->with('success', 'Data driver diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return back()->with('success', 'Driver dihapus.');
    }
}
