@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $title ?? 'Delivery Challan Download (Demo)' }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-primary-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Challan No</th>
                                <th scope="col">Client</th>
                                <th scope="col">Delivery Date</th>
                                <th scope="col">Refer</th>
                                <th scope="col">Model Number</th>
                                <th scope="col">Serial Count</th>
                                <th scope="col">Status</th>
                                <th scope="col">Delivery Place</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $demoChallans = [
                                    [
                                        'challan_no' => 'CH-1001',
                                        'client' => 'Acme Corp',
                                        'delivery_date' => '2025-11-15',
                                        'refer' => 'LC-001',
                                        'model_number' => 'MD-1001',
                                        'serial_count' => 10,
                                        'status' => 'Delivered',
                                        'delivery_place' => 'Warehouse A',
                                    ],
                                    [
                                        'challan_no' => 'CH-1002',
                                        'client' => 'Beta Ltd',
                                        'delivery_date' => '2025-11-16',
                                        'refer' => 'PO-002',
                                        'model_number' => 'MD-2002',
                                        'serial_count' => 5,
                                        'status' => 'Pending',
                                        'delivery_place' => 'Warehouse B',
                                    ],
                                    [
                                        'challan_no' => 'CH-1003',
                                        'client' => 'Gamma Inc',
                                        'delivery_date' => '2025-11-17',
                                        'refer' => 'LC-003',
                                        'model_number' => 'MD-3003',
                                        'serial_count' => 8,
                                        'status' => 'Delivered',
                                        'delivery_place' => 'Warehouse C',
                                    ],
                                ];
                            @endphp

                            @foreach ($demoChallans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['challan_no'] }}</td>
                                <td>{{ $item['client'] }}</td>
                                <td>{{ $item['delivery_date'] }}</td>
                                <td>{{ $item['refer'] }}</td>
                                <td>{{ $item['model_number'] }}</td>
                                <td>{{ $item['serial_count'] }}</td>
                                <td>
                                    <span class="bg-{{ $item['status'] == 'Delivered' ? 'success' : 'warning' }}-focus text-{{ $item['status'] == 'Delivered' ? 'success' : 'warning' }}-main px-32 py-4 rounded-pill fw-medium text-sm">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td>{{ $item['delivery_place'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fa-solid fa-download"></i> Download
                                    </button>
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
