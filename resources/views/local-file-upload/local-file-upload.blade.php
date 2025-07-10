@extends('partials.layouts.layoutTop')

@section('content')
    <div class="container-fluid h-100">
        <div class="row h-100 m-0">
            <div class="col-md-12 p-0 h-100">
                <div class="card h-100" style="margin: 0; border: none; border-radius: 0;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="text-black" style="font-family: 'Roboto', sans-serif;">Local Purchase Document Upload Form</h4>
                        <!-- Hidden Local Data -->
                        <input type="hidden" name="data_source" id="dataSourceInput" value="Local">
                    </div>

                    <div class="card-body h-100 overflow-auto">
                        <!-- Success or Error Message -->
                        @if(session('success'))
                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                        @elseif(session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('upload.storeLocalFileUpload') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Section 1: LC Details -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="po_ref" class="form-label">PO Reference</label>
                                    <input type="text" class="form-control @error('po_ref') is-invalid @enderror" id="po_ref" name="po_ref" placeholder="Enter PO Reference" value="{{ old('po_ref') }}" required>
                                    @error('po_ref')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount(TK)</label>
                                    <input type="number" step="any" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" placeholder="Enter Total Amount" value="{{ old('total_amount') }}" required>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section 2: Document Upload -->
                            <div class="mb-4">
                                <div class="row g-3">
                                    <style>
                                        .file-upload-wrapper {
                                            position: relative;
                                            display: flex;
                                            align-items: center;
                                        }
                                        .file-upload-input {
                                            position: absolute;
                                            left: 0;
                                            top: 0;
                                            opacity: 0;
                                            cursor: pointer;
                                            width: 100%;
                                            height: 100%;
                                        }
                                        .file-upload-button {
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            background-color: #007bff;
                                            color: #fff;
                                            border: none;
                                            padding: 10px 20px;
                                            border-radius: 5px;
                                            cursor: pointer;
                                            font-size: 14px;
                                        }
                                        .file-upload-button i {
                                            margin-right: 5px;
                                        }
                                        .file-name {
                                            margin-left: 10px;
                                            font-size: 14px;
                                            color: #6c757d;
                                        }
                                    </style>

                                    <!-- Requisition Document -->
                                    <div class="col-md-6">
                                        <label for="requisition_document" class="form-label">Requisition Document</label>
                                        <div class="file-upload-wrapper">
                                            <button class="file-upload-button" type="button">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                            </button>
                                            <input type="file" class="file-upload-input @error('requisition_document') is-invalid @enderror" id="requisition_document" name="requisition_document" onchange="showFileName(this, 'requisitionFileName')" required>
                                            <span id="requisitionFileName" class="file-name">No file chosen</span>
                                        </div>
                                        @error('requisition_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Management Approval Document -->
                                    <div class="col-md-6">
                                        <label for="management_approval_document" class="form-label">Management Approval Document</label>
                                        <div class="file-upload-wrapper">
                                            <button class="file-upload-button" type="button">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                            </button>
                                            <input type="file" class="file-upload-input @error('management_approval_document') is-invalid @enderror" id="management_approval_document" name="management_approval_document" onchange="showFileName(this, 'approvalFileName')" required>
                                            <span id="approvalFileName" class="file-name">No file chosen</span>
                                        </div>
                                        @error('management_approval_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Purchase Order Document -->
                                    <div class="col-md-6">
                                        <label for="purchase_order_document" class="form-label">SIL Document</label>
                                        <div class="file-upload-wrapper">
                                            <button class="file-upload-button" type="button">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                            </button>
                                            <input type="file" class="file-upload-input @error('purchase_order_document') is-invalid @enderror" id="purchase_order_document" name="purchase_order_document" onchange="showFileName(this, 'purchaseOrderFileName')" required>
                                            <span id="purchaseOrderFileName" class="file-name">No file chosen</span>
                                        </div>
                                        @error('purchase_order_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Invoice Document -->
                                    <div class="col-md-6">
                                        <label for="invoice_document" class="form-label">Customer Purchase Order</label>
                                        <div class="file-upload-wrapper">
                                            <button class="file-upload-button" type="button">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                            </button>
                                            <input type="file" class="file-upload-input @error('invoice_document') is-invalid @enderror" id="invoice_document" name="invoice_document" onchange="showFileName(this, 'invoiceFileName')" required>
                                            <span id="invoiceFileName" class="file-name">No file chosen</span>
                                        </div>
                                        @error('invoice_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Remarks -->
                            <div class="mb-4">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- JavaScript -->
    <script>
        // Show selected file name
        function showFileName(input, fileNameId) {
            const fileName = input.files[0] ? input.files[0].name : "No file chosen";
            document.getElementById(fileNameId).textContent = fileName;
        }
    </script>
@endsection
