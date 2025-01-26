@extends('partials.layouts.layoutTop')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <!-- Card Header: Filters and Controls -->
        <div class="card-header bg-light d-flex flex-wrap align-items-center justify-content-between gap-3 p-3">
            <!-- Items Per Page -->
            <form method="GET" action="{{ route('defectiveitems') }}" id="perPageForm" class="d-flex align-items-center gap-2">
                <label for="perPage" class="form-label mb-0 fw-bold text-secondary">Show:</label>
                <select name="perPage" id="perPage" class="form-select form-select-sm w-auto shadow-sm" onchange="this.form.submit()">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('perPage') == 15 ? 'selected' : '' }}>15</option>
                    <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                </select>
            </form>

            <!-- Search Section -->
            <div class="ms-auto d-flex align-items-center gap-3">
                <form method="GET" action="{{ route('defectiveitems') }}" id="searchForm" class="d-flex align-items-center gap-2">
                    <select name="search_field" class="form-select form-select-sm w-auto shadow-sm">
                        <option value="all" {{ request('search_field') == 'all' ? 'selected' : '' }}>All Fields</option>
                        <option value="lc_po_type" {{ request('search_field') == 'lc_po_type' ? 'selected' : '' }}>PO Type</option>
                        <option value="brand" {{ request('search_field') == 'brand' ? 'selected' : '' }}>Brand</option>
                        <option value="category" {{ request('search_field') == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="model_no" {{ request('search_field') == 'model_no' ? 'selected' : '' }}>Model No</option>
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm w-auto shadow-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                        <i class="ri-search-line"></i> Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Card Body: Data Table -->
        <div class="card-body bg-white">
            <div class="table-responsive shadow-sm">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-secondary">
                        <tr class="text-secondary">
                            <th scope="col">S.L</th>
                            <th scope="col">PO Type</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Category</th>
                            <th scope="col">Model No</th>
                            <th scope="col">Serial Numbers</th>
                            <th scope="col">Total Qty</th>
                            <th scope="col">Specification</th>
                            <th scope="col">Condition</th>
                            <th scope="col">Holding Location</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paginatedItems as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($paginatedItems->currentPage() - 1) * $paginatedItems->perPage() }}</td>
                            <td>{{ $item->lc_po_type ?? 'N/A' }}</td>
                            <td>{{ $item->brand ?? 'N/A' }}</td>
                            <td>{{ $item->category ?? 'N/A' }}</td>
                            <td>{{ $item->model_no ?? 'N/A' }}</td>
                            <td>{{ $item->serial_numbers ?? 'N/A' }}</td>
                            <td>{{ isset($item->serial_no) && $item->serial_no ? count(explode(',', $item->serial_no)) : 0 }}</td>

                            <td>{{ $item->specification ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-danger">Faulty</span>
                            </td>
                            <td>{{ $item->holding_location ?? 'Unknown' }}</td>
                            <td>{{ $item->date ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary shadow-sm" title="Download Report" onclick="previewReport('{{ json_encode($item, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}')">
                                    <i class="fa-solid fa-download"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">No records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-secondary">
                    Showing {{ $paginatedItems->firstItem() ?? 0 }} to {{ $paginatedItems->lastItem() ?? 0 }} of {{ $paginatedItems->total() ?? 0 }} entries
                </div>
                <div>
                    {{ $paginatedItems->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewReport(itemJson) {
        try {
            const item = JSON.parse(itemJson);

            const today = new Date().toLocaleDateString();
            const content = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 50px;">
                    <h2>Defective Product Report</h2>
                </div>
                <div style="margin-bottom: 20px;">
                    <strong>Date:</strong> ${today}
                </div>
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.entries(item).map(([key, value]) => `
                            <tr>
                                <td>${key}</td>
                                <td>${value || 'N/A'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(content);
            printWindow.document.close();
            printWindow.print();
        } catch (error) {
            console.error('Error generating the report:', error);
            alert('An error occurred. Please try again.');
        }
    }
</script>

