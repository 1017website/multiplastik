@props(['name', 'label', 'value' => null, 'folder' => 'site'])

<div class="mb-3">
    <label class="form-label">{{ $label }}</label>

    @if($value)
        <div class="mb-2">
            <img src="{{ str_starts_with($value, 'http') ? $value : asset('uploads/'.$folder.'/'.$value) }}"
                 alt="" style="max-height:80px;border:1px solid #ddd;border-radius:4px;padding:3px;background:#fff;">
        </div>
    @endif

    <input type="file" name="{{ $name }}" class="form-control mb-2" accept="image/*">
    <input type="text" name="{{ $name }}_url_manual" class="form-control" placeholder="...atau paste URL gambar (https://...)">
    <small class="text-muted">Upload file ATAU isi URL. Jika diisi keduanya, URL yang dipakai.</small>
</div>
