@extends('admin.layout')

@section('title', isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm')
@section('page-title', isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm')
@section('breadcrumb', 'VELOUR / Sản phẩm / ' . (isset($product) ? 'Chỉnh sửa' : 'Tạo mới'))

@section('topbar-actions')
<a href="{{ route('admin.products.index') }}" class="topbar-btn secondary">← Quay lại</a>
@endsection

@section('content')

<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product->productID) : route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

        {{-- Left column --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Thông tin cơ bản --}}
            <div class="table-card" style="padding:28px;">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:400;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                    Thông tin sản phẩm
                </h3>
                <div class="form-grid cols-1" style="gap:20px;">
                    <div class="form-group">
                        <label>Tên sản phẩm <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="productName" class="form-control @error('productName') error @enderror"
                               value="{{ old('productName', $product->productName ?? '') }}"
                               placeholder="VD: Áo sơ mi linen cao cấp" required>
                        @error('productName')<p class="form-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Danh mục <span style="color:var(--danger)">*</span></label>
                            <select name="categoryID" class="form-control" required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->categoryID }}"
                                        {{ old('categoryID', $product->categoryID ?? '') == $cat->categoryID ? 'selected' : '' }}>
                                        {{ $cat->categoryName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nhà cung cấp</label>
                            <select name="manufacturerID" class="form-control">
                                <option value="">Chọn nhà cung cấp</option>
                                @foreach($manufacturers as $mfr)
                                    <option value="{{ $mfr->manufacturerID }}"
                                        {{ old('manufacturerID', $product->manufacturerID ?? '') == $mfr->manufacturerID ? 'selected' : '' }}>
                                        {{ $mfr->manufacturerName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mô tả sản phẩm</label>
                        <textarea name="description" class="form-control" style="min-height:120px;"
                                  placeholder="Mô tả chi tiết về chất liệu, kiểu dáng, cách bảo quản...">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Biến thể sản phẩm --}}
            <div class="table-card" style="padding:28px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:400;">Biến thể (Size / Màu)</h3>
                    <button type="button" class="topbar-btn secondary" onclick="addVariant()" style="font-size:11px;padding:7px 12px;">
                        + Thêm biến thể
                    </button>
                </div>

                <div id="variants-container">
                    @if(isset($product) && $product->variants->isNotEmpty())
                        @foreach($product->variants as $i => $variant)
                        <div class="variant-row" style="display:grid;grid-template-columns:1fr 1fr 120px 40px;gap:10px;margin-bottom:10px;align-items:end;">
                            <div class="form-group">
                                @if($i === 0)<label>Màu sắc</label>@endif
                                <select name="variants[{{ $i }}][colorID]" class="form-control">
                                    <option value="">Chọn màu</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->colorID }}" {{ $variant->colorID == $color->colorID ? 'selected' : '' }}>
                                            {{ $color->colorName }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="variants[{{ $i }}][variantID]" value="{{ $variant->variantID }}">
                            </div>
                            <div class="form-group">
                                @if($i === 0)<label>Size</label>@endif
                                <select name="variants[{{ $i }}][sizeID]" class="form-control">
                                    <option value="">Chọn size</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->sizeID }}" {{ $variant->sizeID == $size->sizeID ? 'selected' : '' }}>
                                            {{ $size->sizeName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                @if($i === 0)<label>Tồn kho</label>@endif
                                <input type="number" name="variants[{{ $i }}][stock]" class="form-control"
                                       value="{{ $variant->stockQuantity }}" min="0" placeholder="0">
                            </div>
                            <button type="button" onclick="this.closest('.variant-row').remove()"
                                style="height:40px;border:1px solid var(--border);border-radius:4px;background:transparent;cursor:pointer;color:var(--muted);transition:all .15s;"
                                onmouseover="this.style.background='var(--danger)';this.style.color='#fff';this.style.borderColor='var(--danger)'"
                                onmouseout="this.style.background='transparent';this.style.color='var(--muted)';this.style.borderColor='var(--border)'">
                                ✕
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>

                <p class="form-hint" style="margin-top:8px;">Mỗi tổ hợp Size + Màu = 1 SKU riêng trong kho.</p>
            </div>
        </div>

        {{-- Right column --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Giá --}}
            <div class="table-card" style="padding:24px;">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:400;margin-bottom:20px;">Giá bán</h3>
                <div class="form-group">
                    <label>Giá gốc (VNĐ) <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="basePrice" class="form-control @error('basePrice') error @enderror"
                           value="{{ old('basePrice', $product->basePrice ?? '') }}"
                           placeholder="350000" min="0" step="1000" required>
                    @error('basePrice')<p class="form-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Ảnh sản phẩm --}}
            <div class="table-card" style="padding:24px;">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:400;margin-bottom:20px;">Hình ảnh</h3>

                @if(isset($product) && $product->images->isNotEmpty())
                <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">
                    Ảnh đầu tiên được chọn tự động làm <strong style="color:var(--gold);">★ Đại diện</strong>.
                </p>
                <div id="existing-images" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:16px;">
                    @foreach($product->images as $img)
                    <div id="img-wrapper-{{ $img->imageID }}"
                         style="position:relative;aspect-ratio:1;background:#f0ebe3;border:2px solid {{ $product->imageID === $img->imageID ? 'var(--gold)' : 'var(--border)' }};border-radius:4px;overflow:hidden;display:grid;place-items:center;">
                        <img src="{{ asset('storage/'.$img->imageURL) }}"
                             style="width:100%;height:100%;object-fit:cover;"
                             onerror="this.parentElement.innerHTML='<span style=\'color:var(--muted);font-size:11px;\'>Ảnh</span>'">

                        {{-- Cover badge: chỉ hiển thị tĩnh, ảnh đầu tiên được gán tự động --}}
                        @if($product->imageID === $img->imageID)
                            <div id="cover-badge-{{ $img->imageID }}"
                                 style="position:absolute;top:4px;left:4px;background:var(--gold);color:#fff;font-size:10px;padding:2px 6px;border-radius:10px;font-weight:600;">
                                ★ Đại diện
                            </div>
                        @endif

                        {{-- ✅ Nút xóa ảnh — KHÔNG dùng <form> lồng, dùng JS fetch thay thế --}}
                        <button type="button"
                                onclick="deleteImage({{ $img->imageID }}, this)"
                                title="Xóa ảnh"
                                style="position:absolute;top:4px;right:4px;width:20px;height:20px;background:var(--danger);border:none;border-radius:50%;color:#fff;font-size:10px;cursor:pointer;display:grid;place-items:center;line-height:1;">
                            ✕
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif

                <div style="border:2px dashed var(--border);border-radius:4px;padding:24px;text-align:center;">
                    <input type="file" name="images[]" id="imgInput" multiple accept="image/*"
                           style="display:none;" onchange="previewImages(this)">
                    <label for="imgInput" style="cursor:pointer;color:var(--muted);font-size:13px;">
                        <div style="margin-bottom:8px;color:var(--gold);">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <strong style="color:var(--ink);">Chọn ảnh</strong><br>
                        <span style="font-size:12px;">PNG, JPG tối đa 5MB mỗi ảnh</span>
                    </label>
                    <div id="img-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;justify-content:center;"></div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="table-card" style="padding:20px;">
                <button type="submit" class="topbar-btn" style="width:100%;justify-content:center;padding:14px;">
                    {{ isset($product) ? 'Cập nhật sản phẩm' : 'Tạo sản phẩm' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="topbar-btn secondary"
                   style="width:100%;justify-content:center;padding:14px;margin-top:8px;display:flex;">
                    Hủy
                </a>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
let variantCount = {{ isset($product) ? $product->variants->count() : 0 }};
const colors = @json($colors->pluck('colorName','colorID'));
const sizes  = @json($sizes->pluck('sizeName','sizeID'));

// ✅ Xóa ảnh bằng fetch — tránh lồng <form> bên trong form update
function deleteImage(imageId, btn) {
    if (!confirm('Xóa ảnh này?')) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`{{ url('admin/products/images') }}/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(res => {
        if (res.ok || res.status === 200 || res.status === 302) {
            // Xóa wrapper ảnh khỏi DOM
            const wrapper = document.getElementById('img-wrapper-' + imageId);
            if (wrapper) wrapper.remove();
        } else {
            alert('Xóa ảnh thất bại! (HTTP ' + res.status + ')');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi kết nối khi xóa ảnh!');
    });
}

function addVariant() {
    const container = document.getElementById('variants-container');
    const i = variantCount++;
    const colorOpts = Object.entries(colors).map(([id,name]) => `<option value="${id}">${name}</option>`).join('');
    const sizeOpts  = Object.entries(sizes).map(([id,name])  => `<option value="${id}">${name}</option>`).join('');

    const row = document.createElement('div');
    row.className = 'variant-row';
    row.style = 'display:grid;grid-template-columns:1fr 1fr 120px 40px;gap:10px;margin-bottom:10px;align-items:end;';
    row.innerHTML = `
        <div class="form-group">
            <select name="variants[${i}][colorID]" class="form-control">
                <option value="">Chọn màu</option>${colorOpts}
            </select>
        </div>
        <div class="form-group">
            <select name="variants[${i}][sizeID]" class="form-control">
                <option value="">Chọn size</option>${sizeOpts}
            </select>
        </div>
        <div class="form-group">
            <input type="number" name="variants[${i}][stock]" class="form-control" min="0" placeholder="0">
        </div>
        <button type="button" onclick="this.closest('.variant-row').remove()"
            style="height:40px;border:1px solid var(--border);border-radius:4px;background:transparent;cursor:pointer;color:var(--muted);transition:all .15s;"
            onmouseover="this.style.background='var(--danger)';this.style.color='#fff';this.style.borderColor='var(--danger)'"
            onmouseout="this.style.background='transparent';this.style.color='var(--muted)';this.style.borderColor='var(--border)'">✕</button>
    `;
    container.appendChild(row);
}

function previewImages(input) {
    const preview = document.getElementById('img-preview');
    preview.innerHTML = '';
    [...input.files].forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style = 'width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid var(--border);';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush