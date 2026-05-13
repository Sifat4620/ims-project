@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">{{ $title }}</h5>
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
                                            class="btn btn-sm btn-primary-600 open-price-modal"
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
        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {

        var modalEl  = document.getElementById('priceModal');
        var itemInput = document.getElementById('modalItemId');
        var serialEl  = document.getElementById('modalSerial');
        var priceInput = document.getElementById('modalPrice');

        document.querySelectorAll('.open-price-modal').forEach(function (btn) {
            btn.addEventListener('click', function () {

                var itemId = btn.getAttribute('data-item-id');
                var serial = btn.getAttribute('data-serial');
                var price  = btn.getAttribute('data-price');

                console.log('Clicked - item_id:', itemId, 'serial:', serial, 'price:', price);

                itemInput.value       = itemId;
                serialEl.textContent  = serial;
                priceInput.value      = price || '';

                // Try Bootstrap 5 first, fallback to jQuery
                if (typeof bootstrap !== 'undefined') {
                    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                } else if (typeof $ !== 'undefined') {
                    $('#priceModal').modal('show');
                } else {
                    console.error('Bootstrap JS not loaded');
                }
            });
        });

    });
</script>

@endsection


