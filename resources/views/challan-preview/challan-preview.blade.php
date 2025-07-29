@extends('partials.layouts.layoutTop')

@section('content')
    <div class="card">
        <!-- Buttons -->
        <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-danger radius-8 d-inline-flex align-items-center gap-1" onclick="printInvoice()">
                    <iconify-icon icon="basil:printer-outline" class="text-xl"></iconify-icon>
                    Print
                </button>
            </div>
        </div>

        
        <!-- Invoice Design -->
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
                                    <h3 class="fs-6 mb-1">Challan No : {{ $invoice_number ?? 'Default Value' }}</h3>
                                    <div class="border border-5 p-3 text-center">
                                        <h4 class="fs-6 mb-1">Customer Address</h4>
                                        <p class="mb-1 text-sm">{{ $customer_address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-6 mb-1">Date Issued: {{ $date_issued ?? 'N/A' }}</h3>
                                    <div class="border border-5 p-3 text-center">
                                        <p class="mb-1 text-sm">PO No.: {{ $po_number ?? 'N/A' }}</p>
                                        <p class="mb-1 text-sm">PO Date: {{ $po_date ?? 'N/A' }}</p>
                                        <hr class="my-2 border-3 border-dark">
                                        <p class="mb-0 fw-bold text-sm">Transporter Name: Square Centre (11th Floor), 48, Mohakhali C/A, 1212</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="py-28 px-20">
                            <div class="mt-24">
                                <div class="table-responsive scroll-sm">
                                    <table class="table bordered-table text-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">SL.</th>
                                                <th scope="col">Part No.</th>
                                                <th scope="col">Items Description</th>
                                                <th scope="col">UoM</th>
                                                <th scope="col">Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($selected_items))
                                                @foreach ($selected_items as $key => $items)
                                                    @php
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
                                                            Category: {{ $category }}<br>
                                                            Brand: {{ $brand }}<br>
                                                            Model No: {{ $model_no }}<br>
                                                            Serial Nos:
                                                            @foreach ($items as $item)
                                                                {{ $item->serial_no }}@if (!$loop->last), @endif
                                                            @endforeach
                                                            <br>
                                                            Specification: {{ $specification }}
                                                        </td>
                                                        <td>Pcs</td>
                                                        <td>{{ $items->count() }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No items found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- JSON Encode -->
                        @php
                            $challanJson = [
                                'invoice_number' => $invoice_number ?? 'N/A',
                                'customer_address' => $customer_address ?? 'N/A',
                                'date_issued' => $date_issued ?? 'N/A',
                                'po_number' => $po_number ?? 'N/A',
                                'po_date' => $po_date ?? 'N/A',
                                'auth_name' => $auth_name ?? 'N/A',
                                'auth_designation' => $auth_designation ?? 'N/A',
                                'auth_mobile' => $auth_mobile ?? 'N/A',
                                'rec_name' => $rec_name ?? 'N/A',
                                'rec_designation' => $rec_designation ?? 'N/A',
                                'rec_organization' => $rec_organization ?? 'N/A',
                                'items' => collect($selected_items ?? [])->map(function ($group, $key) {
                                    $parts = explode('|', $key);
                                    return [
                                        'part_no' => $group->first()->part_no ?? 'N/A',
                                        'category' => $parts[0] ?? 'N/A',
                                        'brand' => $parts[1] ?? 'N/A',
                                        'model_no' => $parts[2] ?? 'N/A',
                                        'specification' => $parts[3] ?? 'N/A',
                                        'serial_numbers' => $group->pluck('serial_no')->toArray(),
                                        'quantity' => $group->count()
                                    ];
                                })->values()
                            ];
                        @endphp

                                <script>
    function printInvoice() {
        const challanData = @json($challanJson);

        const itemsPerPage = 10;
        const itemChunks = [];
        for (let i = 0; i < challanData.items.length; i += itemsPerPage) {
            itemChunks.push(challanData.items.slice(i, i + itemsPerPage));
        }

        const win = window.open('', '_blank');
        win.document.write(`
            <html>
            <head>
                <title>Delivery Challan</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    .page {
                        width: 210mm;
                        height: 297mm;
                        padding: 20mm;
                        box-sizing: border-box;
                        position: relative;
                        page-break-after: always;
                        overflow: hidden;
                        border: 1px solid #ccc;
                    }
                    .header {
                        height: 50mm;
                        margin-bottom: 10mm;
                    }
                    .footer {
                        position: absolute;
                        bottom: 20mm;
                        left: 20mm;
                        right: 20mm;
                        display: flex;
                        justify-content: space-between;
                        font-size: 14px;
                    }
                    .table-container {
                        max-height: 170mm;
                        overflow: hidden;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 10px;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 5px;
                        font-size: 12px;
                        text-align: center;
                    }
                    .note {
                        text-align: center;
                        font-weight: bold;
                        margin-top: 10mm;
                        margin-bottom: 20mm;
                    }
                    @media print {
                        .page { page-break-after: always; }
                        .footer { position: absolute; bottom: 20mm; }
                    }
                </style>
            </head>
            <body>
        `);

        itemChunks.forEach((chunk, pageIndex) => {
            win.document.write(`
                <div class="page">
                    <div class="header">
                        <h1 style="text-align:center">Delivery Challan</h1>
                        <table style="width:100%; border: none; margin-bottom: 5px;">
                            <tr>
                                <td><strong>Challan No:</strong> ${challanData.invoice_no}</td>
                                <td><strong>Date:</strong> ${challanData.invoice_date}</td>
                            </tr>
                            <tr>
                                <td><strong>PO No:</strong> ${challanData.po_no}</td>
                                <td><strong>PO Date:</strong> ${challanData.po_date}</td>
                            </tr>
                            <tr>
                                <td><strong>Customer Address:</strong> ${challanData.customer_address}</td>
                                <td><strong>Transport:</strong> ${challanData.transport_info}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Part No.</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Serials</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
            `);

            chunk.forEach((item, index) => {
                win.document.write(`
                    <tr>
                        <td>${(pageIndex * itemsPerPage) + index + 1}</td>
                        <td>${item.part_number}</td>
                        <td>${item.category}</td>
                        <td>${item.brand}</td>
                        <td>${item.model_no}</td>
                        <td>${item.serials.join(', ')}</td>
                        <td>${item.serials.length}</td>
                    </tr>
                `);
            });

            win.document.write(`
                            </tbody>
                        </table>
                    </div>

                    <div class="note">We hereby certify that the goods mentioned above are in good condition and delivered as per your order.</div>

                    <div class="footer">
                        <div>
                            <p><strong>Authorised By</strong></p>
                            <p>Name: ${challanData.auth_name}</p>
                            <p>Mobile: ${challanData.auth_mobile}</p>
                        </div>
                        <div>
                            <p><strong>Received By</strong></p>
                            <p>Name: ${challanData.rec_name}</p>
                            <p>Mobile: ${challanData.rec_mobile}</p>
                        </div>
                    </div>
                </div>
            `);
        });

        win.document.write(`</body></html>`);
        win.document.close();
        setTimeout(() => win.print(), 500);  // Ensures full render before print
    }
</script>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
