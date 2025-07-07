@extends('partials.layouts.layoutTop')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">

        <!-- Card Header -->
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-3 p-3">

            <!-- Items Per Page -->
            <form method="GET" action="{{ route('barcode.index') }}" class="d-flex align-items-center gap-2 m-0">
                <label for="perPage" class="form-label mb-0 text-secondary fw-semibold">Show</label>
                <select name="perPage" id="perPage" class="form-select form-select-sm w-auto shadow-sm" onchange="this.form.submit()">
                    @foreach([10, 15, 20] as $size)
                        <option value="{{ $size }}" {{ request('perPage') == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Search Form -->
            <form method="GET" action="{{ route('barcode.index') }}" class="d-flex gap-2 align-items-center m-0">
                <select name="search_field" class="form-select form-select-sm w-auto shadow-sm">
                    <option value="all" {{ request('search_field') == 'all' ? 'selected' : '' }}>All Fields</option>
                    <option value="lc_po_type" {{ request('search_field') == 'lc_po_type' ? 'selected' : '' }}>LC/PO Type</option>
                    <option value="brand" {{ request('search_field') == 'brand' ? 'selected' : '' }}>Brand</option>
                    <option value="category" {{ request('search_field') == 'category' ? 'selected' : '' }}>Category</option>
                    <option value="model_no" {{ request('search_field') == 'model_no' ? 'selected' : '' }}>Model No</option>
                </select>
                <input type="text" name="search" class="form-control form-control-sm shadow-sm w-auto" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                    <i class="ri-search-line"></i> Search
                </button>
            </form>
        </div>

        <!-- Bulk Preview Form -->
        <form id="bulkPreviewForm" action="{{ route('barcode.bulkPdf') }}" method="POST" target="_blank" class="p-3">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning">
                <i class="ri-eye-line"></i> Download Selected Barcodes
            </button>

            <!-- Product Table -->
            <div class="card-body bg-white mt-3">
                <div class="table-responsive shadow-sm">
                    <table class="table table-hover align-middle">
                        <thead class="table-secondary">
                            <tr class="text-muted">
                                <th><input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleAllCheckboxes(this)"></th>
                                <th>S.L</th>
                                <th>LC/PO Type</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Model No</th>
                                <th>Serial No</th>
                                <th>Specification</th>
                                <th>Holding Location</th>
                                <th>Condition</th>
                                <th>Issued</th>
                                <th>Barcode</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_ids[]" class="form-check-input item-checkbox" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                    <td>{{ $item->lc_po_type }}</td>
                                    <td>{{ $item->brand }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->model_no }}</td>
                                    <td>{{ $item->serial_no }}</td>
                                    <td>{{ $item->specification }}</td>
                                    <td>{{ $item->holding_location }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->condition === 'Faulty' ? 'danger' : 'success' }}">
                                            {{ $item->condition }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->status === 'Confirmed' ? 'success' : 'secondary' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                            @if ($item->itemBarcode->isNotEmpty())
                                                @php $barcode = $item->itemBarcode->first(); @endphp
                                                <div>{!! DNS1D::getBarcodeSVG($barcode->barcode_string, 'C128', 2, 80) !!}</div>
                                            @else
                                                <form method="POST" action="{{ route('barcode.generate', $item->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success shadow-sm">
                                                        <i class="fa fa-barcode"></i> Generate
                                                    </button>
                                                </form>
                                            @endif
                                    </td>
                                    <td>
                                        @if ($item->itemBarcode && $item->itemBarcode->isNotEmpty())
                                            <a href="{{ route('barcode.download', $item->id) }}" class="btn btn-sm btn-success">
                                                SVG
                                            </a>
                                        @else
                                            <span class="text-muted small">No barcode</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($items->hasPages())
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                        <div class="text-secondary small">
                            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} items
                        </div>
                        <div>
                            {{ $items->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    function toggleAllCheckboxes(source) {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = source.checked);
    }
</script>
@endsection
