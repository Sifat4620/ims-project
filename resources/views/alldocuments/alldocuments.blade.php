@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">

    <div class="col-lg-12">

        <div class="card h-100">

            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Items per page -->
                    <div class="d-flex align-items-center gap-2">
                        <span>Show</span>
                        <form method="GET" action="{{ route('logistics.alldocuments') }}">
                            <select name="perPage" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>
                    </div>

                    <!-- Search Section -->
                    <div class="icon-field">
                        <div class="d-flex align-items-center gap-2">
                            <form method="GET" action="{{ route('logistics.alldocuments') }}" class="d-flex gap-2">
                                <select name="search_field" class="form-select form-select-sm w-auto">
                                    <option value="all" {{ $searchField == 'all' ? 'selected' : '' }}>All Fields</option>
                                    <option value="lcpo_no" {{ $searchField == 'lcpo_no' ? 'selected' : '' }}>LCPO No</option>
                                    <option value="type" {{ $searchField == 'type' ? 'selected' : '' }}>Document Type</option>
                                    <option value="remarks" {{ $searchField == 'remarks' ? 'selected' : '' }}>Remarks</option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm w-auto" placeholder="Search..." value="{{ $search }}">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="ri-search-line"></i> Search
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Document Type</th>
                                <th>LCPO No</th>
                                <th>Part Shipment</th>
                                <th>Total Amount</th>
                                <th>LC Document</th>
                                <th>Requisition Document</th>
                                <th>Management Approval Document</th>
                                <th>Purchase Order Document</th>
                                <th>Regulatory Approval Document</th>
                                <th>Invoice Document</th>
                                <th>Customs Document</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td>#{{ $document->id }}</td>
                                    <td>{{ $document->type }}</td>
                                    <td>{{ $document->lcpo_no }}</td>
                                    <td>{{ ucfirst($document->part_shipment) }}</td>
                                    <td>{{ number_format($document->total_amount, 2) }}</td>
                                    <td>{{ $document->lc_document }}</td>
                                    <td>{{ $document->requisition_document }}</td>
                                    <td>{{ $document->management_approval_document }}</td>
                                    <td>{{ $document->purchase_order_document }}</td>
                                    <td>{{ $document->regulatory_approval_document }}</td>
                                    <td>{{ $document->invoice_document }}</td>
                                    <td>{{ $document->customs_document }}</td>
                                    <td>{{ $document->remarks }}</td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $documents->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection

@section('extra-js')
<script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>
<script>
    // Enable tooltips for any buttons or elements if needed
    var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endsection
