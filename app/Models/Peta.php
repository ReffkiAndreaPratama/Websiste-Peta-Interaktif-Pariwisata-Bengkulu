<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Destinasi;

class DestinasiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gambar'    => 'nullable|image|max:2048',
        ]);

        // Generate slug unik dari nama
        $base = Str::slug($validated['nama']);
        $slug = $base;
        $i = 1;

        while (Destinasi::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        $validated['slug'] = $slug;

        // Upload file gambar bila ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('destinasi', 'public');
        }

        // Simpan ke database
        Destinasi::create($validated);

        return redirect()
            ->route('admin.destinasi')
            ->with('success', '✅ Destinasi berhasil ditambahkan.');
    }
}
