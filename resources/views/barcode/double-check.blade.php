@extends('partials.layouts.layoutTop')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body bg-white">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Scan Section -->
                    <div class="card shadow-sm border rounded-3 mb-4">
                        <div class="card-header bg-secondary text-white fw-bold d-flex align-items-center">
                            <i class="fa fa-barcode me-2"></i> Scan Product Barcode
                        </div>
                        <div class="card-body">
                            <label for="barcodeInput" class="form-label fw-semibold">Scan Barcode</label>
                            <input
                                type="text"
                                id="barcodeInput"
                                class="form-control shadow-sm"
                                placeholder="Scan barcode and press Enter..."
                                autocomplete="off"
                                autofocus
                            >
                            <small class="text-muted fst-italic">Each scan will appear below. Duplicate scans will increase quantity.</small>
                        </div>
                    </div>

                    <!-- Scanned Product Table -->
                    <div class="card shadow-sm border rounded-3">
                        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
                            <span><i class="fa fa-list-check me-2"></i> Scanned Products</span>
                            <span>Total Quantity: <span id="totalQty" class="badge bg-success">0</span></span>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-sm align-middle text-center" id="scannedTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>LC/PO Type</th>
                                        <th>Serial</th>
                                        <th>Condition</th>
                                        <th>Status</th>
                                        <th>Quantity</th>
                                        <th>Barcode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-muted fst-italic placeholder-row">
                                        <td colspan="10">Scanned items will appear here.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    let scannedItems = [];
    let scannedCount = 0;

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('barcodeInput');
        const tableBody = document.querySelector('#scannedTable tbody');
        const totalQtyDisplay = document.getElementById('totalQty');

        // Optional: Fetch barcode passed from backend (via controller)
        const autoBarcode = @json(request('barcode', ''));

        if (autoBarcode) {
            fetchBarcodeAndAdd(autoBarcode);
        }

        input.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const code = input.value.trim();
                if (!code) return;
                input.value = '';
                input.disabled = true;
                fetchBarcodeAndAdd(code);
            }
        });

        function fetchBarcodeAndAdd(code) {
            fetch(`/barcode/ajax-check?barcode=${encodeURIComponent(code)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                scannedCount++;

                // Remove placeholder if exists
                const placeholder = tableBody.querySelector('.placeholder-row');
                if (placeholder) placeholder.remove();

                const row = document.createElement('tr');

                if (data.success) {
                    const item = data.item;

                    const existing = scannedItems.find(i => i.barcode === item.barcode_string);
                    if (existing) {
                        existing.quantity++;
                        const qtyCell = document.querySelector(`#row-${existing.id} .qty`);
                        qtyCell.textContent = existing.quantity;
                    } else {
                        const id = scannedItems.length + 1;
                        scannedItems.push({
                            id: id,
                            barcode: item.barcode_string,
                            quantity: 1
                        });

                        row.id = `row-${id}`;
                        row.innerHTML = `
                            <td>${id}</td>
                            <td>${item.brand}</td>
                            <td>${item.model_no}</td>
                            <td>${item.lc_po_type}</td>
                            <td>${item.serial_no}</td>
                            <td>${item.condition}</td>
                            <td>${item.status}</td>
                            <td class="qty">1</td>
                            <td>
                                <div>${item.barcode_svg}</div>
                                <small class="text-monospace">${item.barcode_string}</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="removeItem(${id})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    }

                    updateTotalQuantity();
                } else {
                    row.innerHTML = `
                        <td>${scannedCount}</td>
                        <td colspan="9">
                            <div class="alert alert-warning mb-0 py-1 px-2 text-center">
                                No item found for <strong>${code}</strong>.
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                }

                input.disabled = false;
                input.focus();
            })
            .catch(() => {
                alert('Error fetching barcode info.');
                input.disabled = false;
                input.focus();
            });
        }

        window.removeItem = function(id) {
            const row = document.getElementById(`row-${id}`);
            if (row) row.remove();
            scannedItems = scannedItems.filter(i => i.id !== id);
            updateTotalQuantity();

            if (scannedItems.length === 0) {
                const placeholder = document.createElement('tr');
                placeholder.className = 'text-muted fst-italic placeholder-row';
                placeholder.innerHTML = `<td colspan="10">Scanned items will appear here.</td>`;
                tableBody.appendChild(placeholder);
            }
        }

        function updateTotalQuantity() {
            const total = scannedItems.reduce((sum, item) => sum + item.quantity, 0);
            totalQtyDisplay.textContent = total;
        }
    });
</script>

<style>
    #barcodeInput:focus {
        border-color: #198754 !important;
        box-shadow: 0 0 6px #19875466;
        outline: none;
    }
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endsection
