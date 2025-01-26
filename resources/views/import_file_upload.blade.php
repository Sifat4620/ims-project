@extends('partials.layouts.layoutTop')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100 m-0">
        <div class="col-md-12 p-0 h-100">
            <div class="card h-100" style="margin: 0; border: none; border-radius: 0;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Overseas Supplier Purchase Document Upload Form</h5>

                    <!-- Hidden Import Data -->
                    <input type="hidden" name="data_source" id="dataSourceInput" value="Import">
                </div>

                <div class="card-body h-100 overflow-auto">
                    <form action="{{ route('upload.storeImportFileUpload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Section 1: LC Details -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="po_ref" class="form-label">LC Reference</label>
                                <input type="text" class="form-control" id="po_ref" name="po_ref" placeholder="Enter LC Reference" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_amount" class="form-label">Total Amount</label>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Enter Total Amount" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="partShipment" class="form-label">Part Shipment</label>
                                <select class="form-control" id="partShipment" name="partShipment" required>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
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

                                <!-- LC Document -->
                                <div class="col-md-6">
                                    <label for="lcDocument" class="form-label">LC Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="lcDocument" name="lcDocument" onchange="showFileName(this, 'lcFileName')" required>
                                        <span id="lcFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>

                                <!-- Requisition Document -->
                                <div class="col-md-6">
                                    <label for="requisitionDocument" class="form-label">Requisition Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="requisitionDocument" name="requisitionDocument" onchange="showFileName(this, 'requisitionFileName')" required>
                                        <span id="requisitionFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>

                                <!-- Management Approval Document -->
                                <div class="col-md-6">
                                    <label for="managementApprovalDocument" class="form-label">Management Approval Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="managementApprovalDocument" name="managementApprovalDocument" onchange="showFileName(this, 'approvalFileName')" required>
                                        <span id="approvalFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>

                                <!-- Purchase Document -->
                                <div class="col-md-6">
                                    <label for="purchaseDocument" class="form-label">Purchase Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="purchaseDocument" name="purchaseDocument" onchange="showFileName(this, 'purchaseFileName')" required>
                                        <span id="purchaseFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>

                                <!-- Regulatory Approval Document -->
                                <div class="col-md-6">
                                    <label for="regulatoryApprovalDocument" class="form-label">Regulatory Approval Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="regulatoryApprovalDocument" name="regulatoryApprovalDocument" onchange="showFileName(this, 'regulatoryFileName')" required>
                                        <span id="regulatoryFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>

                                <!-- Invoice Document -->
                                <div class="col-md-6">
                                    <label for="invoiceDocument" class="form-label">Invoice Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="invoiceDocument" name="invoiceDocument" onchange="showFileName(this, 'invoiceFileName')" required>
                                        <span id="invoiceFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>

                                <!-- Customs Document -->
                                <div class="col-md-6">
                                    <label for="customsDocument" class="form-label">Customs Document</label>
                                    <div class="file-upload-wrapper">
                                        <button class="file-upload-button" type="button">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Upload
                                        </button>
                                        <input type="file" class="file-upload-input" id="customsDocument" name="customsDocument" onchange="showFileName(this, 'customsFileName')" required>
                                        <span id="customsFileName" class="file-name">No file chosen</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Remarks -->
                        <div class="mb-4">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here"></textarea>
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
    function showFileName(input, fileNameId) {
        const fileName = input.files[0] ? input.files[0].name : "No file chosen";
        document.getElementById(fileNameId).textContent = fileName;
    }
</script>
</div>
@endsection

