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
                                <th scope="col" class="bg-base">Defect ID</th>
                                <th scope="col" class="bg-base">LC or PO Number</th>
                                <th scope="col" class="bg-base">Product Brand</th>
                                <th scope="col" class="bg-base">Product Category</th>
                                <th scope="col" class="bg-base">Issue Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic Data Loop -->
                            @foreach ($defectiveItems as $item)
                                <tr>
                                    <td class="bg-primary-light">#{{ $item->id }}</td>
                                    <td class="bg-primary-light">{{ $item->lc_po_type ?? 'N/A' }}</td>
                                    <td class="bg-primary-light">{{ $item->item->brand ?? 'Unknown Brand' }}</td>
                                    <td class="bg-primary-light">{{ $item->item->category ?? 'Uncategorized' }}</td>
                                    <td class="bg-primary-light">{{ $item->reported_date ? $item->reported_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                            <!-- No Data Case -->
                            @if ($defectiveItems->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No defective items found.</td>
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

