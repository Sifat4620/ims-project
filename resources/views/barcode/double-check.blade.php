@extends('partials.layouts.layoutTop')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body bg-white">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Barcode Input Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white fw-bold">
                            <i class="fa fa-barcode me-2"></i> Scan Product Barcode
                        </div>
                        <div class="card-body">
                            <input type="text" id="barcodeInput" class="form-control" placeholder="Scan barcode and press Enter..." autofocus>
                            <small class="text-muted">Each scan will appear below. Duplicate scans will increase quantity.</small>
                        </div>
                    </div>

                    <!-- Scanned Items Table -->
                    <div class="card">
                        <div class="card-header bg-light fw-bold d-flex justify-content-between">
                            <span>Scanned Products</span>
                            <span>Total Quantity: <span id="totalQty" class="badge bg-success">0</span></span>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered text-center" id="scannedTable">
                                <thead class="table-secondary">
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
                                    <tr class="placeholder-row text-muted fst-italic">
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

{{-- ✅ Inline JS placed directly here for compatibility --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('barcodeInput');
    const tableBody = document.querySelector('#scannedTable tbody');
    const totalQtyDisplay = document.getElementById('totalQty');
    let scannedItems = [];
    let scannedCount = 0;

    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = input.value.trim();
            if (!code) return;

            input.value = '';
            input.disabled = true;

            fetch(`/barcode/ajax-check?barcode=${encodeURIComponent(code)}`)
                .then(res => res.json())
                .then(data => {
                    console.log('Fetched:', data);
                    scannedCount++;

                    const placeholder = document.querySelector('.placeholder-row');
                    if (placeholder) placeholder.remove();

                    if (data.success && data.item) {
                        const item = data.item;
                        const barcode = item.barcode_string;
                        const existing = scannedItems.find(i => i.barcode === barcode);

                        if (existing) {
                            existing.quantity++;
                            const qtyCell = document.querySelector(`#row-${existing.id} .qty`);
                            if (qtyCell) qtyCell.textContent = existing.quantity;
                        } else {
                            const id = scannedItems.length + 1;
                            scannedItems.push({ id, barcode, quantity: 1 });

                            const row = document.createElement('tr');
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
                                <td>${item.barcode_string}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="removeItem(${id})">Remove</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        }
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${scannedCount}</td>
                            <td colspan="9" class="text-danger">
                                <strong>No item found for barcode "${code}"</strong>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    }

                    updateTotalQuantity();
                    input.disabled = false;
                    input.focus();
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Error fetching barcode data.');
                    input.disabled = false;
                    input.focus();
                });
        }
    });

    window.removeItem = function (id) {
        const row = document.getElementById(`row-${id}`);
        if (row) row.remove();

        scannedItems = scannedItems.filter(i => i.id !== id);
        updateTotalQuantity();

        if (scannedItems.length === 0) {
            const placeholder = document.createElement('tr');
            placeholder.className = 'placeholder-row text-muted fst-italic';
            placeholder.innerHTML = `<td colspan="10">Scanned items will appear here.</td>`;
            tableBody.appendChild(placeholder);
        }
    };

    function updateTotalQuantity() {
        const total = scannedItems.reduce((sum, item) => sum + item.quantity, 0);
        totalQtyDisplay.textContent = total;
    }
});
</script>
@endsection
