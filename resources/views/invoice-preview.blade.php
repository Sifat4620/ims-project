@extends('partials.layouts.layoutTop')
@section('content')
    <div class="card">
        <!-- Buttons -->
        <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                <a href="javascript:void(0)" class="btn btn-sm btn-primary-600 radius-8 d-inline-flex align-items-center gap-1">
                    <iconify-icon icon="pepicons-pencil:paper-plane" class="text-xl"></iconify-icon>
                    Send Invoice
                </a>                
                <button type="button" class="btn btn-sm btn-danger radius-8 d-inline-flex align-items-center gap-1" onclick="printInvoice()">
                    <iconify-icon icon="basil:printer-outline" class="text-xl"></iconify-icon>
                    Print
                </button>
            </div>
        </div>

        <!-- Invoice Design-->
        <div class="card-body py-40">
            <div class="row justify-content-center" id="invoice">
                <div class="col-lg-8">
                    <div class="shadow-4 border radius-8">
                        <!-- Header -->
                        <div class="p-20 d-flex flex-column align-items-center gap-3 border-bottom">
                            <div>
                                <img src="{{ asset('assets/images/logo.png') }}" alt="image" class="light-logo">
                            </div>
                            <div>
                                <h3 class="text-xl border-bottom pb-2">Delivery Challan</h3>
                            </div>
                            <div class="d-flex justify-content-center gap-5 align-items-start">
                                <div>
                                    <h3 class="fs-6 mb-1">Invoice :{{ $invoice_number ?? 'Default Value' }}</h3>
                                    <div class="border border-5 p-3 text-center">
                                        <h4 class="fs-6 mb-1">Customer Address</h4> 
                                        <p class="mb-1 text-sm">{{ $customer_address ?? 'N/A' }}</p>
                                       
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-6 mb-1">Date Issued: {{ $date_issued ?? 'N/A' }}</h3>
                                    <div class="border border-5 p-3 text-center"> 
                                        <p class="mb-1 text-sm">PO No.:{{ $po_number ?? 'N/A' }}</p>
                                        <p class="mb-1 text-sm">PO Date:{{ $po_date ?? 'N/A' }}</p>
                                        <hr class="my-2 border-3 border-dark">
                                        <p class="mb-0 fw-bold text-sm">Transporter Name: Square Centre (11th Floor), 48, Mohakhali C/A, 1212</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Body -->
                        <div class="py-28 px-20">
                            <!-- Issued Items List -->
                            <div class="mt-24">
                                <div class="table-responsive scroll-sm">
                                    <table class="table bordered-table text-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-sm">SL.</th>
                                                <th scope="col" class="text-sm">Part No.</th>
                                                <th scope="col" class="text-sm">Items Description</th>
                                                <th scope="col" class="text-sm">UoM</th>
                                                <th scope="col" class="text-sm">Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($selected_items))
                                                @foreach ($selected_items as $key => $items)
                                                    @php
                                                        // Split the group key back into individual components
                                                        $parts = explode('|', $key);
                                                        $category = $parts[0];
                                                        $brand = $parts[1];
                                                        $model_no = $parts[2];
                                                        $specification = $parts[3];
                                                    @endphp
                                        
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $items->first()->part_no ?? 'N/A' }}</td>
                                                        <td>
                                                            Category: {{ $category ?? 'N/A' }} <br>
                                                            Brand: {{ $brand ?? 'N/A' }} <br>
                                                            Model No: {{ $model_no ?? 'N/A' }} <br>
                                                            Serial Nos: 
                                                            @foreach ($items as $item)
                                                                {{ $item->serial_no }}@if(!$loop->last), @endif
                                                            @endforeach
                                                            <br>
                                                            Specification: {{ $specification ?? 'N/A' }}
                                                        </td>
                                                        <td>Pcs</td>
                                                        <td>{{ $items->count() }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">No items found.</td>
                                                </tr>
                                            @endif  
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                         
                            {{-- Footer --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-end mt-64">
                                <div class="text-sm border-top d-inline-block px-12">
                                    Authorized Signature <br>
                                    Name: {{ $auth_name ?? 'N/A' }}<br>
                                    Designation: {{ $auth_designation ?? 'N/A' }}<br>
                                    Square InformatiX Ltd <br>
                                    M: {{ $auth_mobile ?? 'N/A' }}
                                </div>
                                <div class="text-sm border-top d-inline-block px-12">
                                    Recipient's Signature <br>
                                    Name: {{ $rec_name ?? 'N/A' }}<br>
                                    Designation: {{ $rec_designation ?? 'N/A' }}<br>
                                    Organization: {{ $rec_organization ?? 'N/A' }}<br>
                                    <br>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function printInvoice() {
            var printContents = document.getElementById("invoice").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    