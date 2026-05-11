@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0">{{ $title }}</h5>

                {{-- Search & Filter --}}
                {{-- <form method="GET" action="{{ route('pricing.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
                    <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="Search serial / model..."
                        value="{{ request('search') }}"
                        style="width: 200px;"
                    >
                    <select name="filter" class="form-select form-select-sm" style="width: 140px;">
                        <option value="">All Items</option>
                        <option value="priced"   {{ request('filter') === 'priced'   ? 'selected' : '' }}>Priced</option>
                        <option value="unpriced" {{ request('filter') === 'unpriced' ? 'selected' : '' }}>Unpriced</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary-600">Filter</button>
                    <a href="{{ route('pricing.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form> --}}
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table basic-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Serial No</th>
                                <th>Model No</th>
                                <th>LC / PO No</th>
                                <th>Category</th>
                                <th>Condition</th>
                                <th>Issued</th>
                                <th>Entry Date</th>
                                <th>Price (BDT)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="text-primary-600">
                                            {{ $item->serial_no }}
                                        </a>
                                    </td>
                                    <td>{{ $item->model_no }}</td>
                                    <td>{{ $item->lc_po_type }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>
                                        @if($item->condition === 'Good')
                                            <span class="badge bg-success-100 text-success-600 px-2 py-1">Good</span>
                                        @else
                                            <span class="badge bg-danger-100 text-danger-600 px-2 py-1">Faulty</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status === 'Yes')
                                            <span class="badge bg-warning-100 text-warning-600 px-2 py-1">Issued</span>
                                        @else
                                            <span class="badge bg-neutral-100 text-neutral-600 px-2 py-1">In Stock</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td>
                                        @if($item->pricing && $item->pricing->price !== null)
                                            <span class="text-success-600 fw-semibold">
                                                ৳ {{ number_format($item->pricing->price, 2) }}
                                            </span>
                                        @else
                                            <span class="text-neutral-400">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-primary-600"
                                            data-bs-toggle="modal"
                                            data-bs-target="#priceModal"
                                            data-item-id="{{ $item->id }}"
                                            data-serial="{{ $item->serial_no }}"
                                            data-price="{{ $item->pricing->price ?? '' }}"
                                        >
                                            {{ $item->pricing && $item->pricing->price !== null ? 'Edit Price' : 'Set Price' }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div> <!-- card end -->
    </div>
</div>


{{-- Price Modal --}}
<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('pricing.store') }}">
                @csrf
                <input type="hidden" name="item_id" id="modalItemId">

                <div class="modal-header">
                    <h6 class="modal-title" id="priceModalLabel">Set Price</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2 text-neutral-500 text-sm">
                        Serial No: <strong id="modalSerial" class="text-primary-600"></strong>
                    </p>
                    <label class="form-label fw-semibold">Price (BDT)</label>
                    <input
                        type="number"
                        name="price"
                        id="modalPrice"
                        class="form-control"
                        placeholder="Enter price"
                        min="0"
                        step="0.01"
                        required
                    >
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-600 btn-sm">Save Price</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    document.getElementById('priceModal').addEventListener('show.bs.modal', function (event) {
        const btn    = event.relatedTarget;
        const itemId = btn.getAttribute('data-item-id');
        const serial = btn.getAttribute('data-serial');
        const price  = btn.getAttribute('data-price');

        document.getElementById('modalItemId').value       = itemId;
        document.getElementById('modalSerial').textContent = serial;
        document.getElementById('modalPrice').value        = price || '';
    });
</script>
@endsection