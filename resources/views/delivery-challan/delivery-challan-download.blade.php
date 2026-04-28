@extends('partials.layouts.layoutTop')

@section('content')
<div class="container">
    <h4>{{ $title }}</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Challan Date</th>
                    <th>Challan Number</th>
                    <th>Brand - Category - Model No</th>
                    <th>Serial Numbers</th>
                    <th>Serial Qty</th>
                    <th>Classification Summary</th>
                    <th>Authorized Person</th>
                    <th>Customer Name</th>
                    <th>Customer Address</th>
                    <th>Recipient Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->date_issued ?? '-' }}</td>
                        <td>{{ $invoice->invoice_number ?? '-' }}</td>
                        <td>
                            {{ $invoice->item->brand ?? '-' }} - 
                            {{ $invoice->item->category ?? '-' }} - 
                            {{ $invoice->item->model_no ?? '-' }}
                        </td>
                        <td>{{ $invoice->serial_number ?? '-' }}</td>
                        <td>1</td>
                        <td>{{ $invoice->item->specification ?? '-' }}</td>
                        <td>
                            {{ $invoice->authorized_name ?? '-' }} /
                            {{ $invoice->authorized_designation ?? '-' }} /
                            {{ $invoice->authorized_mobile ?? '-' }}
                        </td>
                        <td>{{ $invoice->recipient_name ?? 'N/A' }}</td>
                        <td>{{ $invoice->customer_address ?? '-' }}</td>
                        <td>
                            {{ $invoice->recipient_name ?? '-' }} /
                            {{ $invoice->recipient_designation ?? '-' }} /
                            {{ $invoice->recipient_organization ?? '-' }}
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
