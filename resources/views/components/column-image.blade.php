
<div class="w-16 h-16 rounded-lg overflow-hidden">
    @if (isset($default))
    <img src="{{ $image ? asset('storage/' . $image->url) : asset('storage/'.$default.'/default.png') }}" alt="Imagen de {{ $default }}" class="w-full h-full object-cover">
    @else
    <img src="{{ $image ? asset('storage/' . $image->url) : asset('storage/producto/default.png') }}" alt="Imagen de producto" class="w-full h-full object-cover">
    @endif
</div>