@extends('partials.layouts.layoutTop')

@section('content')
{{-- CSRF for AJAX (in case your layout doesn’t already include it) --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body bg-white">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Barcode Input Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white fw-bold d-flex align-items-center">
                            <i class="fa fa-barcode me-2"></i>
                            <span>Scan Product Barcode</span>
                        </div>
                        <div class="card-body">
                            <input
                                type="text"
                                id="barcodeInput"
                                class="form-control"
                                placeholder="Scan barcode and press Enter..."
                                autofocus
                                autocomplete="off"
                                inputmode="none">
                            <small class="text-muted">Each scan will appear below. Duplicate scans will increase quantity.</small>
                        </div>
                    </div>

                    <!-- Scanned Items Table -->
                    <div class="card">
                        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                            <span>Scanned Products</span>
                            <div class="d-flex align-items-center gap-3">
                                <span>Total Quantity: <span id="totalQty" class="badge bg-success">0</span></span>
                                <button class="btn btn-sm btn-outline-secondary" id="clearScans" type="button">
                                    <i class="fa fa-trash me-1"></i> Clear
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered text-center align-middle mb-0" id="scannedTable">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>LC/PO Type</th>
                                        <th>Serial</th>
                                        <th>Condition</th>
                                        <th>Status</th>
                                        <th style="width:120px">Quantity</th>
                                        <th>Barcode</th>
                                        <th style="width:110px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="placeholder-row text-muted fst-italic">
                                        <td colspan="10">Scanned items will appear here.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Save Button in Card Footer -->
                        <div class="card-footer text-center">
                            <button class="btn btn-primary fw-bold" id="saveScans" type="button">
                                <i class="fa fa-save me-1"></i> Save Scanned Data
                            </button>
                        </div>
                    </div>

                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
</div> <!-- /.container-fluid -->


{{-- ✅ Inline JS placed directly here for compatibility --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('barcodeInput');
    const tableBody = document.querySelector('#scannedTable tbody');
    const totalQtyDisplay = document.getElementById('totalQty');
    const saveBtn = document.getElementById('saveScans');
    const clearBtn = document.getElementById('clearScans');

    // Keep a clean source of truth for scanned items
    // Structure: [{ id, barcode, quantity }]
    let scannedItems = [];
    let scannedCount = 0;

    // Utility: Focus input quickly after actions
    const focusInput = () => setTimeout(() => input.focus(), 0);

    // Utility: Insert placeholder when table is empty
    function ensurePlaceholder() {
        if (scannedItems.length === 0 && !document.querySelector('.placeholder-row')) {
            const placeholder = document.createElement('tr');
            placeholder.className = 'placeholder-row text-muted fst-italic';
            placeholder.innerHTML = `<td colspan="10">Scanned items will appear here.</td>`;
            tableBody.appendChild(placeholder);
        }
    }

    // Update total quantity badge
    function updateTotalQuantity() {
        const total = scannedItems.reduce((sum, item) => sum + item.quantity, 0);
        totalQtyDisplay.textContent = total;
    }

    // Re-index the "#" column after removals
    function reindexRows() {
        const rows = tableBody.querySelectorAll('tr[id^="row-"]');
        let idx = 1;
        rows.forEach(tr => {
            tr.querySelector('td:first-child').textContent = idx++;
        });
    }

    // Remove by local id
    window.removeItem = function (id) {
        const row = document.getElementById(`row-${id}`);
        if (row) row.remove();
        scannedItems = scannedItems.filter(i => i.id !== id);
        reindexRows();
        updateTotalQuantity();
        ensurePlaceholder();
        focusInput();
    };

    // Clear all scans
    clearBtn.addEventListener('click', () => {
        scannedItems = [];
        tableBody.innerHTML = `
            <tr class="placeholder-row text-muted fst-italic">
                <td colspan="10">Scanned items will appear here.</td>
            </tr>`;
        updateTotalQuantity();
        focusInput();
    });

    // Handle scanner input (Enter key)
    input.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        const code = input.value.trim();
        if (!code) return;

        input.value = '';
        input.disabled = true;

        fetch(`/barcode/ajax-check?barcode=${encodeURIComponent(code)}`)
            .then(res => res.json())
            .then(data => {
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
                        const id = (scannedItems.length ? Math.max(...scannedItems.map(i => i.id)) : 0) + 1;
                        scannedItems.push({ id, barcode, quantity: 1 });

                        const row = document.createElement('tr');
                        row.id = `row-${id}`;
                        row.innerHTML = `
                            <td>${id}</td>
                            <td>${item.brand ?? ''}</td>
                            <td>${item.model_no ?? ''}</td>
                            <td>${item.lc_po_type ?? ''}</td>
                            <td>${item.serial_no ?? ''}</td>
                            <td>${item.condition ?? ''}</td>
                            <td class="status-cell">${item.status ?? ''}</td>
                            <td class="qty">1</td>
                            <td class="barcode-cell">${barcode}</td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="removeItem(${id})">
                                    <i class="fa fa-times"></i> Remove
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    }
                } else {
                    // Show a single-line error for unknown barcode (doesn't count in total qty)
                    const row = document.createElement('tr');
                    row.classList.add('table-warning');
                    row.innerHTML = `
                        <td>${scannedCount}</td>
                        <td colspan="9" class="text-danger">
                            <strong>No item found for barcode "${code}"</strong>
                        </td>
                    `;
                    tableBody.appendChild(row);
                }

                updateTotalQuantity();
            })
            .catch(err => {
                console.error('Fetch error:', err);
                alert('Error fetching barcode data.');
            })
            .finally(() => {
                input.disabled = false;
                focusInput();
            });
    });

    // Save button → send scanned barcodes to backend to flip status to "Delivery"
    saveBtn.addEventListener('click', async function () {
        if (!scannedItems || scannedItems.length === 0) {
            alert('No scanned items to save.');
            focusInput();
            return;
        }

        // Payload for backend: [{ barcode, quantity }]
        const payload = {
            items: scannedItems.map(i => ({ barcode: i.barcode, quantity: i.quantity }))
        };

        // CSRF token
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

        try {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Saving...';

            const res = await fetch("{{ route('barcode.bulkStatus') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!data.success) {
                alert(data.message || 'Failed to save statuses.');
                return;
            }

            // Update UI: set status to "Delivery" for rows whose barcode was in scannedItems
            const scannedCodes = new Set(scannedItems.map(s => s.barcode));
            const rows = document.querySelectorAll('#scannedTable tbody tr[id^="row-"]');

            rows.forEach(tr => {
                const barcodeCell = tr.querySelector('.barcode-cell');
                const statusCell  = tr.querySelector('.status-cell');
                if (!barcodeCell || !statusCell) return;

                const code = barcodeCell.textContent.trim();
                if (scannedCodes.has(code)) {
                    statusCell.textContent = 'Processing';
                    statusCell.classList.add('text-success', 'fw-bold');
                    statusCell.classList.remove('text-danger');
                }
            });

            const r = data.result || {};
            alert(`Saved!\nUpdated: ${r.updated ?? 0}\nMatched: ${r.total_matched ?? 0}\nAlready Delivery: ${r.already_delivery ?? 0}`);

        } catch (e) {
            console.error(e);
            alert('Network or server error while saving.');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fa fa-save me-1"></i> Save Scanned Data';
            focusInput();
        }
    });
});
</script>
@endsection
