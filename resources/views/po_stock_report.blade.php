@extends('partials.layouts.layoutTop')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0">
            <!-- Card Header: Filters and Controls -->
            <div class="card-header bg-light d-flex flex-wrap align-items-center justify-content-between gap-3 p-3">
                <!-- Items Per Page -->
                <form method="GET" action="{{ route('postockreport') }}" id="perPageForm" class="d-flex align-items-center gap-2">
                    <label for="perPage" class="form-label mb-0 fw-bold text-secondary">Show:</label>
                    <select name="perPage" id="perPage" class="form-select form-select-sm w-auto shadow-sm" onchange="this.form.submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('perPage') == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </form>
            
                <!-- Search Section -->
                <div class="ms-auto d-flex align-items-center gap-3">
                    <form method="GET" action="{{ route('postockreport') }}" id="searchForm" class="d-flex gap-2">
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
                    <table class="table table-hover align-middle">
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
                                    <td>{{ $item['lc_po_type'] }}</td>
                                    <td>{{ $item['brand'] }}</td>
                                    <td>{{ $item['category'] }}</td>
                                    <td>{{ $item['model_no'] }}</td>
                                    <td>{{ $item['serial_numbers'] }}</td>
                                    <td>{{ count(explode(', ', $item['serial_numbers'])) }}</td>
                                    <td>{{ $item['specification'] }}</td>
                                    <td>{{ $item['condition'] }}</td>
                                    <td>{{ $item['holding_location'] }}</td>
                                    <td>{{ $item['date'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary shadow-sm" onclick="previewReport(`{{ json_encode($item) }}`)">
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
                        Showing {{ $paginatedItems->firstItem() }} to {{ $paginatedItems->lastItem() }} of {{ $paginatedItems->total() }} entries
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
            const item = JSON.parse(itemJson);

            const today = new Date().toLocaleDateString();
            const content = `
                <div style="text-align: center;">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 50px; margin-bottom: 20px;">
                </div>
                <div style="text-align: left; margin-bottom: 20px;">
                    <strong>Date:</strong> ${today}
                </div>
                <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>PO Type</td><td>${item.lc_po_type}</td></tr>
                        <tr><td>Brand</td><td>${item.brand}</td></tr>
                        <tr><td>Category</td><td>${item.category}</td></tr>
                        <tr><td>Model No</td><td>${item.model_no}</td></tr>
                        <tr><td>Serial Numbers</td><td>${item.serial_numbers}</td></tr>
                        <tr><td>Total Quantity</td><td>${item.serial_numbers.split(', ').length}</td></tr>
                        <tr><td>Specification</td><td>${item.specification}</td></tr>
                        <tr><td>Condition</td><td>${item.condition}</td></tr>
                        <tr><td>Holding Location</td><td>${item.holding_location}</td></tr>
                        <tr><td>Date</td><td>${item.date}</td></tr>
                    </tbody>
                </table>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Report Preview</title>
                    <style>
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body>
                    ${content}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>

