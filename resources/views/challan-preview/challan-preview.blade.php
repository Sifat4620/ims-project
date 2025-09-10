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



{{-- PDF Download script --}}
                     <script>
    const challanData = @json($challanJson);

    function printInvoice() {
        const data = challanData;

        const rowsHtml = data.items.map((item, index) => {
            // Page break row after every 6 items
             const isBreakPoint = ((index + 1) % 6 === 0) && ((index + 1) < data.items.length);
             const breakRow = isBreakPoint ? `<tr style="page-break-after:always;"></tr>` : "";


            return `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.part_no}</td>
                    <td>
                        Category: ${item.category}<br>
                        Brand: ${item.brand}<br>
                        Model No: ${item.model_no}<br>
                        Serial Nos: ${(item.serial_numbers || []).join(', ')}<br>
                        Specification: ${item.specification}
                    </td>
                    <td>Pcs</td>
                    <td>${item.quantity}</td>
                </tr>
                ${breakRow}
            `;
        }).join('');

        const content = `
        <html>
        <head>
            <title>Delivery Challan</title>
            <style>
                body { font-family: Arial; margin: 0; padding: 0; }

                /* Each page becomes a grid: header | content | footer */
                .page {
                    display: grid;
                    grid-template-rows: auto 1fr auto;
                    min-height: 100vh;
                    padding: 20px;
                    box-sizing: border-box;
                }

                .header {
                    text-align: center;
                    margin-bottom: 10px;
                }
                .header h2 {
                    border-bottom: 2px solid #000;
                    padding-bottom: 6px;
                    display: inline-block;
                    margin: 6px 0 0;
                }
                .meta {
                    display: flex;
                    justify-content: space-between;
                    font-size: 14px;
                    margin-top: 8px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 6px 8px;
                    vertical-align: top;
                }
                thead th {
                    background: #f2f2f2;
                }

                .note {
                    text-align: center;
                    font-weight: bold;
                    margin: 20px 0;
                    font-size: 12px;
                }

                .footer {
                    display: flex;
                    justify-content: space-between;
                    font-size: 14px;
                    margin-top: 20px;
                }

                @media print {
                    @page { margin: 12mm; }
                    .page { page-break-after: always; }
                    thead { display: table-header-group; }
                    tfoot { display: table-footer-group; }
                    tr { page-break-inside: avoid; }
                }
            </style>
        </head>
        <body>
            <div class="page">
                <!-- Header -->
                <div class="header">
                    <img src='{{ asset('assets/images/logo.png') }}' style="height: 50px;"><br>
                    <h2>Delivery Challan</h2>
                    <div class="meta">
                        <div>
                            <strong>Challan No:</strong> ${data.invoice_number}<br>
                            <strong>Customer Address:</strong><br>${data.customer_address}
                        </div>
                        <div>
                            <strong>Date Issued:</strong> ${data.date_issued}<br>
                            <strong>PO No.:</strong> ${data.po_number}<br>
                            <strong>PO Date:</strong> ${data.po_date}<br>
                            <strong>Transporter:</strong> Square Centre (11th Floor), 48, Mohakhali C/A, 1212
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Part No.</th>
                                <th>Items Description</th>
                                <th>UoM</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                    <div class="note">This delivery challan is generated from software and considered official.</div>
                </div>

                <!-- Footer -->
                <div class="footer">
                    <div>
                        <strong>Authorized Signature</strong><br>
                        Name: ${data.auth_name}<br>
                        Designation: ${data.auth_designation}<br>
                        Square InformatiX Ltd<br>
                        M: ${data.auth_mobile}
                    </div>
                    <div>
                        <strong>Recipient's Signature</strong><br>
                        Name: ${data.rec_name}<br>
                        Designation: ${data.rec_designation}<br>
                        Organization: ${data.rec_organization}
                    </div>
                </div>
            </div>
        </body>
        </html>
        `;

        const win = window.open('', '_blank');
        win.document.write(content);
        win.document.close();
        win.print();
    }
</script>

{{-- PDF Download script --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
