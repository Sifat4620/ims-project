@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table colored-row-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="bg-base">Return ID</th>
                                <th scope="col" class="bg-base">LC or PO Number</th>
                                <th scope="col" class="bg-base">Product Brand</th>
                                <th scope="col" class="bg-base">Product Category</th>
                                <th scope="col" class="bg-base">Issue Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic Data Loop -->
                            @foreach ($returns as $return)
                                <tr>
                                    <td class="bg-primary-light">#{{ $return->id }}</td>
                                    <td class="bg-primary-light">{{ $return->lc_po_type ?? 'N/A' }}</td>
                                    <td class="bg-primary-light">
                                        <div class="d-flex align-items-center">
                                            <h6 class="text-md mb-0 fw-medium flex-grow-1">{{ $return->item->brand ?? 'Unknown Brand' }}</h6>
                                        </div>
                                    </td>
                                    <td class="bg-primary-light">{{ $return->item->category ?? 'Uncategorized' }}</td>
                                    <td class="bg-primary-light">{{ $return->created_at->format('d M Y') }}</td>
                                    
                                </tr>
                            @endforeach
                            <!-- No Data Case -->
                            @if ($returns->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No returnable items found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- card end -->
    </div>
</div>


@endsection            
            
@section('extra-js')
<script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>
@endsection

