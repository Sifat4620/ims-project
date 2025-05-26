<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delivery Challan - {{ $invoice_number ?? 'N/A' }}</title>
    <style>
        /* Basic PDF-friendly styles */
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }
        h3 {
            margin: 0;
            padding: 0;
        }
        .header, .footer {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .invoice-title {
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 48%;
            border: 1px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }
        .info-box h4 {
            margin: 0 0 5px 0;
            font-weight: bold;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-center {
            text-align: center;
        }
        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            font-size: 11px;
        }
        .signature-box {
            width: 48%;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/images/logo.png') }}" alt="Company Logo" />
        <h3 class="invoice-title">Delivery Challan</h3>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h4>Invoice : {{ $invoice_number ?? 'N/A' }}</h4>
            <strong>Customer Address</strong>
            <p>{{ $customer_address ?? 'N/A' }}</p>
        </div>
        <div class="info-box">
            <h4>Date Issued: {{ $date_issued ?? 'N/A' }}</h4>
            <p>PO No.: {{ $po_number ?? 'N/A' }}</p>
            <p>PO Date: {{ $po_date ?? 'N/A' }}</p>
            <hr>
            <p><strong>Transporter Name:</strong> Square Centre (11th Floor), 48, Mohakhali C/A, 1212</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">SL.</th>
                <th style="width:15%;">Part No.</th>
                <th style="width:50%;">Items Description</th>
                <th style="width:10%;" class="text-center">UoM</th>
                <th style="width:10%;" class="text-center">Qty</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($selected_items) && $selected_items->count())
                @foreach ($selected_items as $key => $items)
                    @php
                        $parts = explode('|', $key);
                        $category = $parts[0] ?? 'N/A';
                        $brand = $parts[1] ?? 'N/A';
                        $model_no = $parts[2] ?? 'N/A';
                        $specification = $parts[3] ?? 'N/A';
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $items->first()->part_no ?? 'N/A' }}</td>
                        <td>
                            <strong>Category:</strong> {{ $category }}<br>
                            <strong>Brand:</strong> {{ $brand }}<br>
                            <strong>Model No:</strong> {{ $model_no }}<br>
                            <strong>Serial Nos:</strong>
                            @foreach ($items as $item)
                                {{ $item->serial_no }}@if(!$loop->last), @endif
                            @endforeach
                            <br>
                            <strong>Specification:</strong> {{ $specification }}
                        </td>
                        <td class="text-center">Pcs</td>
                        <td class="text-center">{{ $items->count() }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No items found.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-section">
        <div class="signature-box">
            <strong>Authorized Signature</strong><br>
            Name: {{ $auth_name ?? 'N/A' }}<br>
            Designation: {{ $auth_designation ?? 'N/A' }}<br>
            Square InformatiX Ltd<br>
            Mobile: {{ $auth_mobile ?? 'N/A' }}
        </div>
        <div class="signature-box">
            <strong>Recipient's Signature</strong><br>
            Name: {{ $rec_name ?? 'N/A' }}<br>
            Designation: {{ $rec_designation ?? 'N/A' }}<br>
            Organization: {{ $rec_organization ?? 'N/A' }}
        </div>
    </div>

</body>
</html>
