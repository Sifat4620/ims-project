@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">{{ $title }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table basic-table mb-0">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Category</th>
                                <th>Product Brand</th>
                                <th>Entry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td><a href="javascript:void(0)" class="text-primary-600">#{{ $product->product_id }}</a></td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ $product->product_brand }}</td>
                                    <td>{{ \Carbon\Carbon::parse($product->entry_date)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- card end -->
    </div>
</div>
@endsection

@section('extra-js')
<script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>
@endsection
