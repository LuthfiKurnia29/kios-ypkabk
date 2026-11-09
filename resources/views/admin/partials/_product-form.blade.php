{{--
    Partial form fields untuk Tambah / Edit Produk.
    Dipakai di modal tambah (tanpa $isEdit) dan modal edit ($isEdit = true).
--}}
@php $isEdit = $isEdit ?? false; @endphp

{{-- Nama Produk --}}
<div>
    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
        placeholder="Contoh: Tote Bag Lukis Pelangi Harapan"
        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('name') border-rose-400 @enderror">
    @error('name')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Harga & Stok --}}
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga (Rp) <span class="text-rose-500">*</span></label>
        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="500"
            placeholder="35000"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('price') border-rose-400 @enderror">
        @error('price')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Stok <span class="text-rose-500">*</span></label>
        <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
            placeholder="10"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('stock') border-rose-400 @enderror">
        @error('stock')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Kategori & Sumber --}}
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori</label>
        <select name="category_id"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
            <option value="">Tanpa Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Sumber Produk</label>
        <select name="source"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
            <option value="">Pilih Sumber</option>
            <option value="karya" {{ old('source') === 'karya' ? 'selected' : '' }}>Karya ABK</option>
            <option value="preloved" {{ old('source') === 'preloved' ? 'selected' : '' }}>Preloved</option>
        </select>
    </div>
</div>

{{-- Kondisi & Nama Pengrajin --}}
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Kondisi</label>
        <select name="condition"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
            <option value="">Pilih Kondisi</option>
            <option value="baru" {{ old('condition') === 'baru' ? 'selected' : '' }}>Baru</option>
            <option value="mulus" {{ old('condition') === 'mulus' ? 'selected' : '' }}>Mulus</option>
            <option value="layak" {{ old('condition') === 'layak' ? 'selected' : '' }}>Layak Pakai</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pengrajin</label>
        <input type="text" name="artisan_name" value="{{ old('artisan_name') }}" maxlength="255"
            placeholder="Opsional"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
    </div>
</div>

{{-- Deskripsi --}}
<div>
    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Produk</label>
    <textarea name="description" rows="3" maxlength="1000"
        placeholder="Ceritakan tentang produk ini secara singkat..."
        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('description') }}</textarea>
</div>

{{-- Upload File Gambar (Blob DB) --}}
<div>
    <label class="block text-xs font-semibold text-gray-700 mb-1">
        Foto / Gambar Produk
        @if($isEdit)
            <span class="text-gray-400 font-normal">(opsional jika tidak diubah)</span>
        @endif
    </label>
    <div class="flex items-center gap-4 p-3 rounded-xl border border-gray-200 bg-gray-50/50">
        {{-- Preview Box --}}
        <div class="w-16 h-16 rounded-xl border border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden shrink-0 shadow-xs relative">
            <img id="{{ $isEdit ? 'img-preview-edit' : 'img-preview-tambah' }}" src="" alt="Pratinjau" class="w-full h-full object-cover hidden">
            <svg id="{{ $isEdit ? 'icon-placeholder-edit' : 'icon-placeholder-tambah' }}" class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

        {{-- File Input --}}
        <div class="flex-1 min-w-0">
            <input type="file" name="image" id="{{ $isEdit ? 'file-input-edit' : 'file-input-tambah' }}" accept="image/*"
                onchange="handleImagePreview(this, '{{ $isEdit ? 'img-preview-edit' : 'img-preview-tambah' }}', '{{ $isEdit ? 'icon-placeholder-edit' : 'icon-placeholder-tambah' }}')"
                class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-primary hover:file:bg-pink-100 cursor-pointer">
            <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maks. 5MB). File disimpan sebagai blob di database.</p>
            @if($isEdit)
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">Biarkan kosong jika tetap menggunakan gambar yang sudah ada.</p>
            @endif
        </div>
    </div>
    @error('image')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Produk Unggulan --}}
<div class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
    <input type="hidden" name="is_featured" value="0">
    <input type="checkbox" name="is_featured" id="{{ $isEdit ? 'is-featured-edit' : 'is-featured-tambah' }}" value="1"
        {{ old('is_featured') ? 'checked' : '' }}
        class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer">
    <label for="{{ $isEdit ? 'is-featured-edit' : 'is-featured-tambah' }}" class="text-xs font-semibold text-amber-800 cursor-pointer">
        ★ Tampilkan sebagai Produk Unggulan di halaman utama
    </label>
</div>
