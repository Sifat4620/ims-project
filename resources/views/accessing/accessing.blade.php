@extends('partials.layouts.layoutTop')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title text-center mb-4">Welcome to the Invoice Management System</h5>
        <p class="text-center mb-4">Please choose what you want to do:</p>

        <!-- Chatbot-style download interaction -->
        <div class="d-flex justify-content-center gap-4 mb-4">
            <button class="btn btn-success btn-lg" onclick="handleAction('download')">
                Download Procurement Documents
            </button>
        </div>

        <!-- Response message -->
        <div id="actionResponse" class="mt-4 text-center" style="display: none;">
            <p id="responseMessage" class="fw-semibold text-secondary-light" style="font-size: 16px;"></p>
        </div>

        <!-- Error message -->
        <div id="errorMessage" class="alert alert-danger text-center" style="display: none;">
            <p id="errorText" class="mb-0"></p>
        </div>

        <!-- Procurement Type Selection -->
        <div id="procurementPrompt" class="mt-4 text-center" style="display: none;">
            <p class="fw-semibold mb-2" style="font-size: 16px;">Choose Procurement Document Type:</p>
            <button class="btn btn-info btn-lg me-3" onclick="selectProcurement('local')">Local</button>
            <button class="btn btn-info btn-lg" onclick="selectProcurement('foreign')">Foreign</button>
        </div>

        <!-- LCPO Input -->
        <div id="lcpoPrompt" class="mt-4 text-center" style="display: none;">
            <p class="fw-semibold mb-2" style="font-size: 16px;">Enter the LC/PO Number:</p>
            <input type="text" id="lcpoNo" class="form-control w-auto mx-auto mb-3" style="max-width: 300px;" placeholder="Enter LC/PO Number">
            <button class="btn btn-primary btn-lg" onclick="submitLCPO()">Download Now</button>
        </div>

        <!-- Final Message -->
        <div id="finalMessage" class="mt-4 text-center" style="display: none;">
            <p id="finalMessageText" class="fw-semibold text-secondary-light" style="font-size: 16px;"></p>
        </div>
    </div>
</div>

<!-- Divider -->
<hr class="my-5">

<!-- Table: Show All Documents -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">All Uploaded Documents</h5>

        @if ($documents && $documents->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>LCPO No</th>
                        <th>Type</th>
                        <th>Part Shipment</th>
                        <th>Total Amount</th>
                        <th>LC Document</th>
                        <th>Requisition</th>
                        <th>Management Approval</th>
                        <th>Purchase Order</th>
                        <th>Regulatory Approval</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $doc)
                    <tr>
                        <td>{{ $doc->lcpo_no }}</td>
                        <td>{{ ucfirst($doc->type) }}</td>
                        <td>{{ ucfirst($doc->part_shipment) }}</td>
                        <td>{{ number_format($doc->total_amount, 2) }}</td>

                        <td>
                            @if($doc->lc_document)
                                <a href="{{ asset('storage/' . $doc->lc_document) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if($doc->requisition_document)
                                <a href="{{ asset('storage/' . $doc->requisition_document) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if($doc->management_approval_document)
                                <a href="{{ asset('storage/' . $doc->management_approval_document) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if($doc->purchase_order_document)
                                <a href="{{ asset('storage/' . $doc->purchase_order_document) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if($doc->regulatory_approval_document)
                                <a href="{{ asset('storage/' . $doc->regulatory_approval_document) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('access.download', $doc->lcpo_no) }}" class="btn btn-sm btn-success">
                                Download ZIP
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted text-center">No documents found.</p>
        @endif
    </div>
</div>

<script>
    function handleAction(action) {
        hideAll();

        if (action === 'download') {
            document.getElementById('responseMessage').textContent = 'Please select the procurement type.';
            document.getElementById('actionResponse').style.display = 'block';
            document.getElementById('procurementPrompt').style.display = 'block';
        }
    }

    function selectProcurement(type) {
        document.getElementById('procurementPrompt').style.display = 'none';
        document.getElementById('lcpoPrompt').style.display = 'block';
    }

    function submitLCPO() {
        const lcpoNo = document.getElementById('lcpoNo').value.trim();

        if (!lcpoNo) {
            document.getElementById('errorText').textContent = 'Please enter a valid LCPO number.';
            document.getElementById('errorMessage').style.display = 'block';
            return;
        }

        document.getElementById('errorMessage').style.display = 'none';
        document.getElementById('lcpoPrompt').style.display = 'none';
        document.getElementById('finalMessageText').textContent = `LCPO Number: ${lcpoNo}. Starting download...`;
        document.getElementById('finalMessage').style.display = 'block';

        window.location.href = `/accessing/download/${lcpoNo}`;
    }

    function hideAll() {
        document.getElementById('actionResponse').style.display = 'none';
        document.getElementById('procurementPrompt').style.display = 'none';
        document.getElementById('lcpoPrompt').style.display = 'none';
        document.getElementById('finalMessage').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'none';
    }
</script>
@endsection
