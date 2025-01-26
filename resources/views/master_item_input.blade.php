@extends('partials.layouts.layoutTop')

@section('content')

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('coreinventory.store') }}" method="POST">
                        @csrf
                        <div class="row gy-3">
                            <!-- Product ID -->
                            <div class="col-md-3">
                                <label for="product_id" class="fw-semibold my-1">Product ID</label>
                                <input type="text" id="product_id" name="product_id" class="form-control" placeholder="Enter Product ID" required maxlength="255">
                                @error('product_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                    
                            <!-- Product Category -->
                            <div class="col-md-3">
                                <label for="category" class="fw-semibold my-1">Product Category</label>
                                <input type="text" id="category" name="category" class="form-control" placeholder="Enter Product Category" required maxlength="255">
                                @error('category')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                    
                            <!-- Product Brand -->
                            <div class="col-md-3">
                                <label for="product_brand" class="fw-semibold my-1">Product Brand</label>
                                <input type="text" id="product_brand" name="product_brand" class="form-control" placeholder="Enter Product Brand" required maxlength="255">
                                @error('product_brand')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                    
                            <!-- Entry Date -->
                            <div class="col-md-3">
                                <label for="entry_date" class="fw-semibold my-1">Entry Date</label>
                                <input type="hidden" id="entry_date" name="entry_date" value="{{ now()->toDateString() }}">
                                <input type="text" class="form-control" value="{{ now()->toDateString() }}" readonly>
                            </div>
                            
                    
                            <!-- Submit Button -->
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Submit</button>
                            </div>
                        </div>
                    </form>  
                </div>
            </div><!-- card end -->
            
        </div>

        
    </div>

 @endsection
