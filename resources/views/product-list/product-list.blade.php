@extends('partials.layouts.layoutTop')

@section('content')

    <form method="POST" action="{{ route('logistics.invoiceconfirm.store') }}" id="invoiceForm">
        @csrf
        <div class="card">
            <!-- Card Header -->
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Items per page -->
                    <div class="d-flex align-items-center gap-2">
                        <span>Show</span>
                        <form method="GET" action="{{ route('logistics.invoicelist') }}">
                            {{-- <select name="perPage" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select> --}}
                        </form>
                    </div>

                    <!-- Search Section -->
                    <div class="icon-field">
                        <div class="d-flex align-items-center gap-2">
                            <!-- Dropdown for selecting search field -->
                            <form method="GET" action="{{ route('logistics.invoicelist') }}" class="d-flex gap-2">
                                <select name="search_field" class="form-select form-select-sm w-auto">
                                    <option value="all" {{ $searchField == 'all' ? 'selected' : '' }}>All Fields</option>
                                    <option value="lc_po_type" {{ $searchField == 'lc_po_type' ? 'selected' : '' }}>LC/PO Type</option>
                                    <option value="brand" {{ $searchField == 'brand' ? 'selected' : '' }}>Brand</option>
                                    <option value="category" {{ $searchField == 'category' ? 'selected' : '' }}>Category</option>
                                    <option value="model_no" {{ $searchField == 'model_no' ? 'selected' : '' }}>Model No</option>
                                </select>

                                <!-- Search input -->
                                <input type="text" name="search" class="form-control form-control-sm w-auto" 
                                    placeholder="Search..." value="{{ $search }}">

                                <!-- Search button -->
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="ri-search-line"></i> Search
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Filters and Buttons -->
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Issue Status Filter -->
                    <form method="GET" action="{{ route('logistics.invoicelist') }}">
                        <select name="issue_status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="" {{ $issueStatus == '' ? 'selected' : '' }}>Issue</option>
                            <option value="Yes" {{ $issueStatus == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $issueStatus == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </form>

                    <!-- Create Invoice Button -->
                    <button type="submit" class="btn btn-sm btn-primary-600" form="invoiceForm">
                        <i class="ri-add-line"></i> Create Challan
                    </button>
                </div>
            </div>

           <!-- Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleAllCheckboxes(this)">
                                </div>
                            </th>
                            <th scope="col">S.L</th>
                            <th scope="col">LC/PO ID</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Category</th>
                            <th scope="col">Model No</th>
                            <th scope="col">Serial No</th>
                            <th scope="col">Specification</th>
                            <th scope="col">Holding Location</th>
                            <th scope="col">Condition</th>
                            <th scope="col">Issued</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input 
                                            class="form-check-input item-checkbox" 
                                            type="checkbox" 
                                            value="{{ $item->id }}" 
                                            id="checkItem{{ $item->id }}" 
                                            name="selected_items[]">
                                        <label class="form-check-label" for="checkItem{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{-- <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-24">
                <span>Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} entries</span>
                <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
                    {{ $items->links() }}
                </ul>
            </div> --}}
        </div>

        </div>
    </form>



@endsection

@section('extra-js')
<script>
    // Toggle all checkboxes
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }
</script>
@endsection
