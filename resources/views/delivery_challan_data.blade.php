@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table striped-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Challan PO</th>
                                <th scope="col">Brand-Category-Model No</th>
                                <th scope="col">Classification Summary</th>
                                <th scope="col">Serial Numbers</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Challan Date</th>
                                <th scope="col">Customer Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedInvoices as $key => $invoices)
                                @php
                                    // Split the group key back into individual components
                                    $parts = explode('|', $key);
                                    $category = $parts[0];
                                    $brand = $parts[1];
                                    $model_no = $parts[2];
                                    $classification = $parts[3];

                                    // Combine serial numbers and calculate total quantity
                                    $allSerialNumbers = [];
                                    foreach ($invoices as $invoice) {
                                        $serialNos = is_string($invoice->item->serial_no)
                                            ? explode(',', $invoice->item->serial_no)
                                            : [$invoice->item->serial_no];
                                        $allSerialNumbers = array_merge($allSerialNumbers, $serialNos);
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $invoices[0]->invoice_number }}</td> <!-- Displaying first invoice's Challan PO -->
                                    <td>{{ $brand }} - {{ $category }} - {{ $model_no }}</td>
                                    <td>{{ $classification }}</td>
                                    <td>
                                        @foreach (array_unique($allSerialNumbers) as $serial_no) <!-- Displaying unique serial numbers -->
                                            {{ $serial_no }}@if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td>{{ count($allSerialNumbers) }}</td> <!-- Display total quantity (serial numbers) -->
                                    <td>{{ $invoices[0]->created_at->format('d-m-Y') }}</td> <!-- Displaying date from first invoice -->
                                    <td>{{ $invoices[0]->customer_address }}</td> <!-- Display customer address from first invoice -->
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
