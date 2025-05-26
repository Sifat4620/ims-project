@extends('partials.layouts.layoutTop')

@section('content')
<div class="container-fluid">
    {{-- <form method="POST" action="{{ route('barcode.bulkAction') }}" id="barcodeForm"> --}}
        @csrf
        <div class="card shadow-sm border-0">

            <!-- Filters & Controls -->
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

                <!-- Search Field Selector + Search Input -->
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

            <!-- Product Table -->
            <div class="card-body bg-white">
                <div class="table-responsive shadow-sm">
                    <table class="table table-hover align-middle">
                        <thead class="table-secondary">
                            <tr class="text-muted">
                                <th>
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleAllCheckboxes(this)">
                                    </div>
                                </th>
                                <th scope="col">S.L</th>
                                <th scope="col">LC/PO Type</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Category</th>
                                <th scope="col">Model No</th>
                                <th scope="col">Serial No</th>
                                <th scope="col">Specification</th>
                                <th scope="col">Holding Location</th>
                                <th scope="col">Condition</th>
                                <th scope="col">Issued</th>
                                <th scope="col">Download Barcode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="item{{ $item->id }}">
                                        </div>
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
                                        <span class="bg-{{ $item->condition == 'Faulty' ? 'danger' : 'success' }}-focus text-{{ $item->condition == 'Faulty' ? 'danger' : 'success' }}-main px-32 py-4 rounded-pill fw-medium text-sm">
                                            {{ $item->condition }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="bg-{{ $item->status == 'Confirmed' ? 'danger' : 'success' }}-focus text-{{ $item->status == 'Confirmed' ? 'danger' : 'success' }}-main px-32 py-2 rounded-pill fw-medium text-sm">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('barcode.download', $item->id) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                            <i class="fa-solid fa-barcode"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($items->hasPages())
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                        <div class="text-secondary small">
                            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} products
                        </div>
                        <div>
                            {{ $items->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    {{-- </form> --}}
</div>
@endsection

@section('extra-js')
<script>
    // Toggle all checkboxes when master checkbox is clicked
    function toggleAllCheckboxes(source) {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = source.checked);
    }
</script>
@endsection
