@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">{{ $title }}</h5>
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
                                <th>Actions</th>
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
                                    <td>
                                        <!-- Edit Button -->
                                        {{-- <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Document">
                                            Edit
                                        </a> --}}
                                    </td>
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
    // Enable tooltips for the edit buttons
    var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endsection
