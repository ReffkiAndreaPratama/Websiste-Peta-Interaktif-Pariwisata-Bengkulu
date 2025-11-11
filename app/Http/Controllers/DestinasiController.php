<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinasiController extends Controller
{
    /* =========================
     * ADMIN: CRUD Destinasi
     * ========================= */

    public function index()
    {
        $destinasi = Destinasi::latest()->get();
        return view('admin.destinasi', compact('destinasi'));
    }

    public function store(Request $request)
    {
        // dd($request->all()); // aktifkan untuk debug

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('destinasi', 'public');
        }

        Destinasi::create($validated);

        return redirect()->route('admin.destinasi')->with('success', '✅ Destinasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $destinasi = Destinasi::findOrFail($id);

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($destinasi->gambar && Storage::disk('public')->exists($destinasi->gambar)) {
                Storage::disk('public')->delete($destinasi->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('destinasi', 'public');
        }

        $destinasi->update($validated);

        return redirect()->route('admin.destinasi')->with('success', '✅ Data destinasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $destinasi = Destinasi::findOrFail($id);

        if ($destinasi->gambar && Storage::disk('public')->exists($destinasi->gambar)) {
            Storage::disk('public')->delete($destinasi->gambar);
        }

        $destinasi->delete();

        return redirect()->route('admin.destinasi')->with('success', '🗑️ Destinasi berhasil dihapus.');
    }

    /* =========================
     * USER: List & Detail
     * ========================= */

    /** Halaman list destinasi untuk user */
    public function layoutIndex()
    {
        $destinasi = Destinasi::latest()->get();
        return view('destinasi', compact('destinasi'));
    }

    /** Detail destinasi untuk user → gunakan profile/desc.blade.php */
    public function show($id)
    {
        $d = Destinasi::findOrFail($id);

        // Kirim $d (utama) + alias $destinasi (opsional untuk kompatibilitas blade lama)
        return view('profile.desc', [
            'd' => $d,
            'destinasi' => $d,
        ]);
    }

    /** Halaman peta untuk user */
    public function layoutPeta()
    {
        // render awal; data marker disediakan juga oleh endpoint geojson (auto-sync)
        $destinasi = Destinasi::select('id','nama','latitude','longitude','gambar')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('peta', compact('destinasi'));
    }

    /* =========================
     * API ringan untuk sinkron peta
     * ========================= */

    /**
     * Endpoint "versi" data — sangat ringan.
     * Gabungan max(updated_at) + count untuk cek perubahan.
     * Dipanggil periodik dari halaman peta (polling).
     */
    public function changes()
    {
        $last  = Destinasi::max('updated_at');
        $count = Destinasi::count();

        return response()
            ->json([
                'version'      => optional($last)->toISOString() . '|' . $count,
                'last_updated' => optional($last)->toISOString(),
                'count'        => $count,
                'generated_at' => now()->toIso8601String(),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Endpoint GeoJSON — dipanggil hanya saat "version" berubah.
     * Mengirim seluruh marker yang memiliki koordinat.
     */
    public function geojson()
    {
        $features = Destinasi::select('id','nama','latitude','longitude','gambar','updated_at')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest('updated_at')
            ->get()
            ->map(function ($d) {
                return [
                    'type' => 'Feature',
                    'properties' => [
                        'id'         => $d->id,
                        'nama'       => $d->nama,
                        'gambar'     => $d->gambar ? asset('storage/'.$d->gambar) : null,
                        'detail_url' => url('/destinasi/'.$d->id),
                        'updated_at' => optional($d->updated_at)->toIso8601String(),
                    ],
                    'geometry' => [
                        'type'        => 'Point',
                        'coordinates' => [(float) $d->longitude, (float) $d->latitude],
                    ],
                ];
            });

        return response()
            ->json([
                'type'         => 'FeatureCollection',
                'features'     => $features,
                'generated_at' => now()->toIso8601String(),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
