@extends('admin.layout')

@section('title', 'Sản phẩm')
@section('page-title', 'Sản phẩm')
@section('breadcrumb', 'VELOUR / Sản phẩm')

@section('topbar-actions')
<a href="{{ route('admin.products.create') }}" class="topbar-btn">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Thêm sản phẩm
</a>
@endsection

@push('styles')
<style>
    .variant-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 8px;
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 11.5px; color: var(--ink);
        white-space: nowrap;
    }
    .variant-pill .color-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,.12);
        flex-shrink: 0;
    }
    .variant-stock { font-size: 11px; font-weight: 500; color: var(--muted); }
    .variant-stock.low { color: var(--danger); font-weight: 600; }

    .low-stock-warn {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 8px;
        background: #fff5f5;
        border: 1px solid rgba(192,57,43,.25);
        border-radius: 4px;
        font-size: 11px; font-weight: 500;
        color: var(--danger);
        margin-top: 6px;
        animation: pulse-warn 2s ease infinite;
    }
    @keyframes pulse-warn { 0%,100%{opacity:1} 50%{opacity:.6} }

    .btn-restock {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px;
        background: transparent;
        border: 1px solid var(--gold);
        border-radius: 4px;
        font-family: 'DM Sans', sans-serif;
        font-size: 11px; font-weight: 500;
        letter-spacing: .06em;
        color: var(--gold);
        cursor: pointer;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }
    .btn-restock:hover { background: var(--gold); color: #fff; }

    .variants-cell { max-width: 220px; }
    .variants-list { display: flex; flex-direction: column; gap: 4px; }
    .variant-row-item { display: flex; align-items: center; justify-content: space-between; gap: 6px; }

    .restock-variant-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .restock-variant-row:last-child { border-bottom: none; }
    .restock-variant-info { display: flex; align-items: center; gap: 8px; font-size: 13.5px; }
    .restock-qty-input {
        width: 80px; padding: 6px 10px;
        border: 1px solid var(--border); border-radius: 4px;
        font-family: 'DM Sans', sans-serif; font-size: 13px;
        color: var(--ink); text-align: center; outline: none;
        transition: border-color .2s;
    }
    .restock-qty-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,149,90,.1); }
</style>
@endpush

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách sản phẩm ({{ $products->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex;gap:8px;">
                <select name="category" class="form-control" style="padding:8px 12px;font-size:13px;">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->categoryID }}" {{ request('category') == $cat->categoryID ? 'selected' : '' }}>
                            {{ $cat->categoryName }}
                        </option>
                    @endforeach
                </select>
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm sản phẩm..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="topbar-btn secondary" style="padding:8px 14px;">Lọc</button>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Nhà cung cấp</th>
                <th>Giá gốc</th>
                <th>Biến thể (Size / Màu)</th>
                <th>Tồn kho</th>
                <th>Ngày tạo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            @php
                $hasLowStock = $product->variants->contains(fn($v) => $v->stockQuantity < 5);
            @endphp
            <tr>
                {{-- # --}}
                <td class="td-muted">{{ $product->productID }}</td>

                {{-- Tên + ảnh --}}
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:44px;height:44px;border-radius:4px;border:1px solid var(--border);overflow:hidden;flex-shrink:0;background:#f0ebe3;display:grid;place-items:center;">
                            @if($product->coverImage)
                                <img src="{{ asset('storage/' . $product->coverImage->imageURL) }}"
                                     style="width:100%;height:100%;object-fit:cover;"
                                     onerror="this.style.display='none'">
                            @else
                                <svg width="16" height="16" fill="none" stroke="#c0b8ae" stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <strong>{{ $product->productName }}</strong>
                            @if(!$product->coverImage)
                                <div style="font-size:11px;color:var(--muted);margin-top:2px;">Chưa có ảnh</div>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Danh mục --}}
                <td class="td-muted">{{ $product->category->categoryName ?? '—' }}</td>

                {{-- Nhà cung cấp --}}
                <td class="td-muted">{{ $product->manufacturer->manufacturerName ?? '—' }}</td>

                {{-- Giá --}}
                <td>
                    <span style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:600;">
                        {{ number_format($product->basePrice, 0, ',', '.') }}đ
                    </span>
                </td>

                {{-- Biến thể: Size + Màu --}}
                <td class="variants-cell">
                    @if($product->variants->isEmpty())
                        <span class="td-muted">—</span>
                    @else
                        <div class="variants-list">
                            @foreach($product->variants as $variant)
                            <div class="variant-row-item">
                                <span class="variant-pill">
                                    @if($variant->color)
                                        <span class="color-dot" style="background:{{ $variant->color->colorCode ?? '#ccc' }};"></span>
                                    @endif
                                    {{ $variant->size->sizeName ?? '?' }}
                                    /
                                    {{ $variant->color->colorName ?? '?' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </td>

                {{-- Tồn kho --}}
                <td>
                    @if($product->variants->isEmpty())
                        <span class="td-muted">—</span>
                    @else
                        <div class="variants-list">
                            @foreach($product->variants as $variant)
                            <div class="variant-row-item">
                                <span class="variant-stock {{ $variant->stockQuantity < 5 ? 'low' : '' }}">
                                    {{ $variant->stockQuantity }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </td>

                {{-- Ngày tạo --}}
                <td class="td-muted">{{ $product->created_at ? $product->created_at->format('d/m/Y') : '—' }}</td>

                {{-- Actions --}}
                <td>
                    <div style="display:flex;flex-direction:column;align-items:flex-start;gap:6px;">

                        {{-- Sửa + Xóa --}}
                        <div class="action-btns">
                            <a href="{{ route('admin.products.edit', $product->productID) }}" class="btn-icon" title="Sửa">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product->productID) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-icon danger" title="Xóa"
                                    onclick="confirmDelete(this.closest('form'), '{{ addslashes($product->productName) }}')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- Nút Nhập hàng --}}
                        @if($product->variants->isNotEmpty())
                        <button class="btn-restock"
                            onclick="openRestockModal(
                                {{ $product->productID }},
                                '{{ addslashes($product->productName) }}',
                                {{ $product->variants->map(fn($v) => [
                                    'variantID'     => $v->variantID,
                                    'stockQuantity' => $v->stockQuantity,
                                    'sizeName'      => $v->size->sizeName  ?? '?',
                                    'colorName'     => $v->color->colorName ?? '?',
                                    'colorCode'     => $v->color->colorCode ?? '#ccc',
                                ])->toJson() }}
                            )">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M12 5v14M5 12l7-7 7 7"/>
                            </svg>
                            Nhập hàng
                        </button>
                        @endif

                        {{-- Cảnh báo tồn kho thấp --}}
                        @if($hasLowStock)
                        <div class="low-stock-warn">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            Số lượng tồn kho thấp
                        </div>
                        @endif

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        </svg>
                        <h3>Chưa có sản phẩm</h3>
                        <p>Thêm sản phẩm đầu tiên cho cửa hàng.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $products->firstItem() }}–{{ $products->lastItem() }} / {{ $products->total() }}</span>
        <div class="pagination">
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- ═══ MODAL NHẬP HÀNG ═══ --}}
<div class="modal-overlay" id="modal-restock">
    <div class="modal" style="width:520px;">
        <div class="modal-head">
            <div>
                <h3>Nhập hàng</h3>
                <div id="restock-product-name" style="font-size:13px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-restock')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" id="restock-form" action="">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div style="font-size:12px;color:var(--muted);margin-bottom:16px;padding:10px 14px;background:rgba(184,149,90,.06);border-left:3px solid var(--gold);border-radius:2px;">
                    Nhập số lượng cần <strong>bổ sung thêm</strong> cho từng biến thể. Để trống hoặc nhập 0 nếu không nhập biến thể đó.
                </div>
                <div style="display:flex;justify-content:space-between;padding:0 0 8px;border-bottom:1px solid var(--border);margin-bottom:4px;">
                    <span style="font-size:11px;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);">Biến thể</span>
                    <span style="font-size:11px;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);">Nhập thêm</span>
                </div>
                <div id="restock-variants-list"></div>
            </div>
            <div class="modal-foot">
                <button type="button" class="topbar-btn secondary" onclick="closeModal('modal-restock')">Hủy</button>
                <button type="submit" class="topbar-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-right:4px;">
                        <path d="M12 5v14M5 12l7-7 7 7"/>
                    </svg>
                    Xác nhận nhập hàng
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openRestockModal(productId, productName, variants) {
    document.getElementById('restock-product-name').textContent = productName;
    document.getElementById('restock-form').action = '/admin/products/' + productId + '/restock';

    const list = document.getElementById('restock-variants-list');
    list.innerHTML = '';

    variants.forEach(function(v) {
        const isLow = v.stockQuantity < 5;
        const row   = document.createElement('div');
        row.className = 'restock-variant-row';
        row.innerHTML = `
            <div class="restock-variant-info">
                <span style="width:10px;height:10px;border-radius:50%;background:${v.colorCode};border:1px solid rgba(0,0,0,.12);display:inline-block;"></span>
                <span>${v.sizeName} / ${v.colorName}</span>
                <span style="font-size:11px;padding:2px 7px;border-radius:10px;
                    background:${isLow ? '#fff5f5' : 'var(--cream)'};
                    color:${isLow ? 'var(--danger)' : 'var(--muted)'};
                    border:1px solid ${isLow ? 'rgba(192,57,43,.25)' : 'var(--border)'};">
                    ${isLow ? '⚠ ' : ''}Kho: ${v.stockQuantity}
                </span>
            </div>
            <input type="number"
                   name="restock[${v.variantID}]"
                   class="restock-qty-input"
                   min="0" placeholder="0"
                   style="${isLow ? 'border-color:rgba(192,57,43,.35);' : ''}">
        `;
        list.appendChild(row);
    });

    openModal('modal-restock');
}
</script>
@endpush