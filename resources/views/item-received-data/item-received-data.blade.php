@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $title }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-primary-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">LC / PO</th>
                                <th scope="col">Category</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Model NO</th>
                                <th scope="col">Serial NO</th> 
                                <th scope="col">Specification</th>
                                <th scope="col">Item Condition</th>
                                <th scope="col">Issued</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">
                                            {{ $loop->iteration }}
                                        </label>
                                    </div>
                                </td>
                                <td>#{{ $item->lc_po_type }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->brand }}</td>
                                <td>{{ $item->model_no }}</td>

                                <!-- ✅ Serial No + Barcode -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ $item->serial_no }}</span>
                                        <br>
                                        @if ($item->itemBarcode->isNotEmpty())
                                            @php $barcode = $item->itemBarcode->first(); @endphp
                                            <div class="mt-2">{!! DNS1D::getBarcodeSVG($barcode->barcode_string, 'C128', 1.5, 40) !!}</div>
                                        @else
                                            <span class="text-muted small mt-2">No barcode</span>
                                        @endif
                                    </div>
                                </td>

                                <td>{{ $item->specification }}</td>
                                <td>
                                    <span class="bg-{{ $item->condition == 'Faulty' ? 'danger' : 'success' }}-focus text-{{ $item->condition == 'Faulty' ? 'danger' : 'success' }}-main px-32 py-4 rounded-pill fw-medium text-sm">
                                        {{ $item->condition }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-{{ $item->status == 'Yes' ? 'warning' : 'success' }}-focus text-{{ $item->status == 'Yes' ? 'warning' : 'success' }}-main px-32 py-4 rounded-pill fw-medium text-sm">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
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
