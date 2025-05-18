@extends('partials.layouts.layoutTop')

@section('content')

    <form method="POST" action="{{ route('upgrade.info.store') }}" id="upgradeForm">
        @csrf
        <div class="card">
            <!-- Card Header -->
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Items per page -->
                    <div class="d-flex align-items-center gap-2">
                        <span>Show</span>
                        <form method="GET" action="{{ route('upgrade.info') }}">
                            <select name="perPage" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>
                    </div>

                    <!-- Search Section -->
                    <div class="icon-field">
                        <form method="GET" action="{{ route('upgrade.info') }}" class="d-flex gap-2 align-items-center">
                            <select name="search_field" class="form-select form-select-sm w-auto">
                                <option value="all" {{ $searchField == 'all' ? 'selected' : '' }}>All Fields</option>
                                <option value="lc_po_type" {{ $searchField == 'lc_po_type' ? 'selected' : '' }}>LC/PO Type</option>
                                <option value="brand" {{ $searchField == 'brand' ? 'selected' : '' }}>Brand</option>
                                <option value="category" {{ $searchField == 'category' ? 'selected' : '' }}>Category</option>
                                <option value="model_no" {{ $searchField == 'model_no' ? 'selected' : '' }}>Model No</option>
                            </select>

                            <input type="text" name="search" class="form-control form-control-sm w-auto" 
                                placeholder="Search..." value="{{ $search }}">

                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-search-line"></i> Search
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filters and Buttons -->
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Condition Filter -->
                    <form method="GET" action="{{ route('upgrade.info') }}">
                        <select name="condition_filter" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="" {{ $conditionFilter == '' ? 'selected' : '' }}>Condition</option>
                            <option value="Good" {{ $conditionFilter == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Faulty" {{ $conditionFilter == 'Faulty' ? 'selected' : '' }}>Faulty</option>
                        </select>
                    </form>

                    <a href="{{ route('dataentry.index') }}" class="btn btn-sm btn-primary-600">
                        <i class="ri-add-line"></i> Add New Item
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>S.L</th>
                                <th>LC/PO ID</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Model No</th>
                                <th>Serial No</th>
                                <th>Specification</th>
                                <th>Holding Location</th>
                                <th>Condition</th>
                                <th>Status</th>
                                <th>Action</th> <!-- Edit & Delete Buttons -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
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
                                        <span class="bg-{{ $item->status == 'Yes' ? 'danger' : 'success' }}-focus text-{{ $item->status == 'Yes' ? 'danger' : 'success' }}-main px-32 py-2 rounded-pill fw-medium text-sm">
                                            {{ $item->status }}
                                        </span>
                                    </td>

                                    <td class="d-flex gap-2">
                                        <a href="{{ route('upgrade.info.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>

                                        <form action="{{ route('upgrade.info.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-24">
                    <span>Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} entries</span>
                    <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
                        {{ $items->links() }}
                    </ul>
                </div>
            </div>

        </div>
    </form>

@endsection

@section('extra-js')
<script>
    // Add any extra JS if needed
</script>
@endsection
