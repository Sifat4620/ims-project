@extends('partials.layouts.layoutTop')

@section('content')
    <form action="{{ route('logistics.invoicedownload') }}" method="post">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 d-inline-flex align-items-center gap-1">
                        <iconify-icon icon="simple-line-icons:check" class="text-xl"></iconify-icon>
                        Save
                    </button>
                </div>
            </div>

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
                                        <h3 class="fs-6 mb-1">
                                            Invoice Number: <span style="color: gray;">{{ $invoiceNumber ?? 'N/A' }}</span>
                                        </h3>
                                        <input type="hidden" name="invoice_number" value="{{ $invoiceNumber ?? 'N/A' }}">
                            
                                        <div class="border border-5 p-3 text-center">
                                            <h4 class="fs-6 mb-1">Customer Address</h4> 
                                            <input type="text" class="form-control text-sm mb-1 underline-input" name="customer_address" placeholder="Enter customer address">
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="fs-6 mb-1">Date Issued:</h3>
                                        <input type="date" class="form-control text-sm underline-input" name="date_issued" value="2020-08-25">
                                        <div class="border border-5 p-3 text-center"> 
                                            {{-- PO check --}}
                                            <p class="mb-1 text-sm">PO No.:</p>
                                            <input type="text" class="form-control text-sm mb-1 underline-input" name="po_number" placeholder="Enter PO number">
                                            {{-- PO check --}}
                                            <label class="form-label text-sm mb-1">PO Date:</label>
                                            <input type="date" class="form-control text-sm underline-input" name="po_date">
                                            <hr class="my-2 border-3 border-dark">
                                            <p class="mb-0 fw-bold text-sm">Transporter Name: Square Centre (11th Floor), 48, Mohakhali C/A, 1212</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                @forelse ($selectedItems ?? [] as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>-</td>
                                                    <td>
                                                        Category: {{ $item->category ?? 'N/A' }}<br>
                                                        Brand: {{ $item->brand ?? 'N/A' }}<br>
                                                        Model No: {{ $item->model_no ?? 'N/A' }}<br>
                                                        Serial No: {{ $item->serial_no ?? 'N/A' }}<br>
                                                        Specification: {{ $item->specification ?? 'N/A' }}
                                                    </td>
                                                    <td>Pcs</td>
                                                    <td>1</td>
                                                    <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5">No items selected.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Add New Item -->
                                <div>
                                    {{-- <button type="button" id="addRow" class="btn btn-sm btn-primary-600 radius-8 d-inline-flex align-items-center gap-1">
                                        <iconify-icon icon="simple-line-icons:plus" class="text-xl"></iconify-icon>
                                        Add New
                                    </button> --}}
                                    <script>
                                        document.getElementById('addRow').addEventListener('click', function() {
                                            const table = document.querySelector('table tbody');
                                            const newIndex = table.rows.length + 1; // Adjust index to match your list length
                                        
                                            const newRow = `<tr>
                                                <td>${newIndex}</td>
                                                <td><input type="text" name="part_no[]" class="form-control text-sm underline-input"></td>
                                                <td><input type="text" name="item_description[]" class="form-control text-sm underline-input"></td>
                                                <td><input type="text" name="uom[]" class="form-control text-sm underline-input"></td>
                                                <td><input type="number" name="quantity[]" class="form-control text-sm underline-input" value="1"></td>
                                            </tr>`;
                                        
                                            table.innerHTML += newRow; // Append the new row to the table
                                        });
                                        </script>
                                        
                                    
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-end mt-64">
                                    <div class="text-sm border-top d-inline-block px-12">
                                        Authorized Signature <br>
                                        <label for="auth_name">Name:</label>
                                        <input type="text" id="auth_name" name="auth_name" class="form-control text-sm mb-2 underline-input">
                                        
                                        <label for="auth_designation">Designation:</label>
                                        <input type="text" id="auth_designation" name="auth_designation" class="form-control text-sm mb-2 underline-input">
                                        
                                        <label for="auth_organization">Organization:</label>
                                        <input type="text" id="auth_organization" name="auth_organization" class="form-control text-sm mb-2 underline-input" value="Square InformatiX Ltd">
                                        
                                        M: <input type="text" id="auth_mobile" name="auth_mobile" class="form-control text-sm underline-input" value="+880 1787-691049">
                                    </div>
                                    <div class="text-sm border-top d-inline-block px-12">
                                        Recipient's Signature <br>
                                        <label for="rec_name">Name:</label>
                                        <input type="text" id="rec_name" name="rec_name" class="form-control text-sm mb-2 underline-input">
                                        
                                        <label for="rec_designation">Designation:</label>
                                        <input type="text" id="rec_designation" name="rec_designation" class="form-control text-sm mb-2 underline-input">
                                        
                                        <label for="rec_organization">Organization:</label>
                                        <input type="text" id="rec_organization" name="rec_organization" class="form-control text-sm underline-input">
                                    </div>
                                </div>
                                <style>
                                    .underline-input {
                                        border: none;
                                        border-bottom: 1px solid #ccc; /* Gray underline */
                                        border-radius: 0; /* Remove default border-radius to make it flat */
                                        box-shadow: none; /* Remove any default shadow if applicable */
                                    }
                                    .underline-input:focus {
                                        border-bottom: 2px solid #007bff; /* Darker or different color when focused */
                                        outline: none; /* Remove default focus outline */
                                    }
                                </style>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
            @endsection
            @section('scripts')
                <script src="{{ asset('assets/js/invoice.js') }}"></script>
            @endsection
            
</body>

</html>