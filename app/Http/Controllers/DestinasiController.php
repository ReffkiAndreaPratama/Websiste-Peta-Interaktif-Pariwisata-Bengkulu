<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;



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
    $validated = $request->validate([
        'nama'      => 'required|string|max:255',
        'kategori'  => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'latitude'  => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    // generate slug unik
    $validated['slug'] = $this->generateUniqueSlug($validated['nama']);

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $ext = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = time() . '_' . Str::slug($basename) . '.' . $ext;

        // simpan original
        $path = $file->storeAs('destinasi', $filename, 'public');
        $validated['gambar'] = $path;

        // buat thumbnail & webp via Intervention Image
        try {
            $img = Image::make($file->getRealPath())
                ->fit(800, 600, function ($constraint) {
                    $constraint->upsize();
                });

            // thumbnail (simpan dengan kualitas)
            $thumbPath = 'destinasi/thumbs/sm_'.$filename;
            Storage::disk('public')->put($thumbPath, (string) $img->encode($ext, 80));

            // webp versi
            try {
                Storage::disk('public')->put('destinasi/webp/'.pathinfo($filename, PATHINFO_FILENAME).'.webp', (string) $img->encode('webp', 75));
            } catch (\Throwable $e) {
                // ignore webp error
                \Log::warning('WebP encode failed: '.$e->getMessage());
            }
        } catch (\Throwable $e) {
            \Log::warning('Thumbnail generation failed (store): '.$e->getMessage());
        }
    }

    DB::transaction(function () use ($validated) {
        Destinasi::create($validated);
    });

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
        'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    // update slug kalau nama berubah
    if (isset($validated['nama']) && $validated['nama'] !== $destinasi->nama) {
        $validated['slug'] = $this->generateUniqueSlug($validated['nama'], $destinasi->id);
    }

    if ($request->hasFile('gambar')) {
        // hapus file lama + derived
        if ($destinasi->gambar && Storage::disk('public')->exists($destinasi->gambar)) {
            Storage::disk('public')->delete($destinasi->gambar);
            $oldFilename = basename($destinasi->gambar);
            if ($oldFilename) {
                $oldThumb = 'destinasi/thumbs/sm_'.$oldFilename;
                $oldWebp = 'destinasi/webp/'.pathinfo($oldFilename, PATHINFO_FILENAME).'.webp';
                if (Storage::disk('public')->exists($oldThumb)) Storage::disk('public')->delete($oldThumb);
                if (Storage::disk('public')->exists($oldWebp)) Storage::disk('public')->delete($oldWebp);
            }
        }

        $file = $request->file('gambar');
        $ext = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = time() . '_' . Str::slug($basename) . '.' . $ext;

        $path = $file->storeAs('destinasi', $filename, 'public');
        $validated['gambar'] = $path;

        try {
            $img = Image::make($file->getRealPath())
                ->fit(800, 600, function ($constraint) {
                    $constraint->upsize();
                });

            Storage::disk('public')->put('destinasi/thumbs/sm_'.$filename, (string) $img->encode($ext, 80));

            try {
                Storage::disk('public')->put('destinasi/webp/'.pathinfo($filename, PATHINFO_FILENAME).'.webp', (string) $img->encode('webp', 75));
            } catch (\Throwable $e) {
                \Log::warning('WebP encode failed (update): '.$e->getMessage());
            }
        } catch (\Throwable $e) {
            \Log::warning('Thumbnail generation failed (update): '.$e->getMessage());
        }
    }

    DB::transaction(function () use ($destinasi, $validated) {
        $destinasi->update($validated);
    });

    return redirect()->route('admin.destinasi')->with('success', '✅ Data destinasi berhasil diperbarui.');
}

public function destroy($id)
{
    $destinasi = Destinasi::findOrFail($id);

    if ($destinasi->gambar && Storage::disk('public')->exists($destinasi->gambar)) {
        Storage::disk('public')->delete($destinasi->gambar);

        $filename = basename($destinasi->gambar);
        $thumb = 'destinasi/thumbs/sm_'.$filename;
        $webp = 'destinasi/webp/'.pathinfo($filename, PATHINFO_FILENAME).'.webp';
        if (Storage::disk('public')->exists($thumb)) Storage::disk('public')->delete($thumb);
        if (Storage::disk('public')->exists($webp)) Storage::disk('public')->delete($webp);
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
    // Ambil destinasi plus agregasi rating & jumlah ulasan
    $destinasi = Destinasi::withCount('reviews')      // menghasilkan: reviews_count
        ->withAvg('reviews', 'rating')                // menghasilkan: reviews_avg_rating
        ->latest()
        ->get()
        ->map(function ($d) {
            // Normalisasi ke nama yang dipakai blade
            $d->review_count = $d->reviews_count ?? 0;

            $avg = $d->reviews_avg_rating ?? null;
            $d->rating_avg = $avg !== null ? round((float) $avg, 1) : 0;

            return $d;
        });

    return view('destinasi', compact('destinasi'));
}


    /** Detail destinasi untuk user → gunakan profile/desc.blade.php */
    public function show($id)
    {
        // eager-load reviews (urut terbaru)
        $d = Destinasi::with(['reviews' => function($q){
            $q->latest();
        }])->findOrFail($id);

        // hitung rata-rata rating & jumlah ulasan
        $ratingAvg = $d->reviews()->avg('rating');
        $ratingAvg = $ratingAvg !== null ? round((float) $ratingAvg, 2) : 0;
        $ratingCount = $d->reviews()->count();

        return view('profile.desc', [
            'd'            => $d,
            'destinasi'    => $d, // kompatibilitas blade lama
            'rating_avg'   => $ratingAvg,
            'rating_count' => $ratingCount,
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

    public function geojson()
    {
        $features = Destinasi::select(
                'id',
                'nama',
                'kategori', // ✅ WAJIB
                'latitude',
                'longitude',
                'gambar',
                'updated_at'
            )
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
                        'kategori'   => strtolower(trim($d->kategori)), // ✅ FIX
                        'gambar'     => $d->gambar ? asset('storage/'.$d->gambar) : null,
                        'detail_url' => url('/destinasi/'.$d->id),
                        'updated_at' => optional($d->updated_at)->toIso8601String(),
                    ],
                    'geometry' => [
                        'type'        => 'Point',
                        'coordinates' => [
                            (float) $d->longitude,
                            (float) $d->latitude
                        ],
                    ],
                ];
            });

        return response()->json([
            'type'         => 'FeatureCollection',
            'features'     => $features,
            'generated_at' => now()->toIso8601String(),
        ]);
    }


    /**
     * Helper: generate slug unik dari nama.
     * Jika $ignoreId diberikan, baris dengan id itu diabaikan saat cek unik.
     */
    protected function generateUniqueSlug(string $nama, $ignoreId = null): string
    {
        $base = Str::slug($nama);
        $slug = $base;
        $i = 1;

        $query = Destinasi::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = "{$base}-{$i}";
            $i++;

            // rebuild query for new slug
            $query = Destinasi::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
