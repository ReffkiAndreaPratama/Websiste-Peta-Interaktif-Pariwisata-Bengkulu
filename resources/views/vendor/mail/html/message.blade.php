<x-mail::layout>

{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
  <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/KotaBengkulu.png'))) }}"
       alt="Dinas Pariwisata Kota Bengkulu"
       style="height:60px; display:block; margin:0 auto;">
  <div style="margin-top:8px; font-weight:600; color:#0b3c6f;">
    Dinas Pariwisata Kota Bengkulu
  </div>
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy DIHAPUS --}}

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} Dinas Pariwisata Kota Bengkulu<br>
Sistem Informasi Peta Interaktif Wisata Pesisir
</x-mail::footer>
</x-slot:footer>

</x-mail::layout>
