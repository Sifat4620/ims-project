@extends('partials.layouts.layoutTop')

@section('content')
    <style>
        .search-bar-container {
            background-color: #f0f2f5;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .input-group {
            max-width: 400px;
        }
        input[type="text"].form-control {
            border: 2px solid #ced4da;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        input[type="text"].form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
        }
        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }
        .card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .modal-footer {
            justify-content: center;
        }
        .d-flex.gap-2 > button {
            margin: 0 5px;
        }
        @media (max-width: 768px) {
            .search-bar-container {
                flex-direction: column;
                align-items: stretch;
            }
            .input-group {
                width: 100%;
                margin-top: 10px;
            }
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>

    <div class="pb-8">
        <!-- Search Section -->
        <div class="search-bar-container bg-light p-4 mb-5 rounded shadow-sm d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-primary mb-0">Return Management</h4>
            <form method="GET" action="" class="search-form w-50">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control shadow-sm rounded-pill" 
                    placeholder="Search and press Enter..." 
                    value="{{ old('search', $search) }}" 
                    aria-label="Search">
            </form>
        </div>

        <!-- Kanban Columns -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($sections as $key => $section)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm radius-12">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark">Challan PO: #{{ $section['invoice_number'] }}</h6>
                            <hr class="text-muted">
                            <p class="text-secondary">
                                <strong>LC/PO Type:</strong> {{ $section['item_details']['lc_po_type'] }}<br>
                                <strong>Brand:</strong> {{ $section['item_details']['brand'] }}<br>
                                <strong>Category:</strong> {{ $section['item_details']['category'] }}<br>
                                <strong>Model No:</strong> {{ $section['item_details']['model_no'] }}<br>
                                <strong>Serial Nos:</strong>
                                <span class="d-block mt-2">
                                    @foreach($section['serial_numbers'] as $serial_no)
                                        <a href="#" 
                                        class="badge bg-info text-dark shadow-sm me-1" 
                                        data-serial="{{ trim($serial_no) }}" 
                                        data-full="{{ json_encode($section) }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#serialModal">
                                            {{ trim($serial_no) }}
                                        </a>
                                    @endforeach
                                </span>
                                <br>
                                <strong>Specification:</strong> {{ $section['item_details']['specification'] }}<br>
                                <strong>Customer Address:</strong> {{ $section['customer_address'] }}<br>
                                <strong>Date:</strong> {{ $section['item_details']['date'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- First Modal -->
    <div class="modal fade" id="serialModal" tabindex="-1" aria-labelledby="serialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="serialModalLabel">Serial Number Action</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p class="mb-3">Choose the action for the serial number: <strong id="serialNo"></strong></p>
            <input type="hidden" id="fullData" value="">
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-danger" id="faultyButton" onclick="openSecondModal('Faulty')">Faulty</button>
                <button type="button" class="btn btn-primary" id="returnButton" onclick="openSecondModal('Return')">Return</button>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Second Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p class="mb-3">You selected <strong id="selectedAction"></strong> for the serial number: <strong id="confirmSerialNo"></strong>.</p>
            <p>Do you want to proceed?</p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button type="button" class="btn btn-success" onclick="confirmAction()">Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not Yet</button>
        </div>
    </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var serialModal = document.getElementById('serialModal');

        serialModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var serialNo = button.getAttribute("data-serial"); // Extract the serial number
            var fullData = button.getAttribute("data-full"); // Extract the full JSON object as string

            // Update modal content
            document.getElementById("serialNo").textContent = serialNo;
            document.getElementById("fullData").value = fullData;
        });
        });

        function openSecondModal(action) {
        var serialNo = document.getElementById("serialNo").textContent;

        // Close the first modal
        const serialModal = bootstrap.Modal.getInstance(document.getElementById("serialModal"));
        serialModal.hide();

        // Set details in the second modal
        document.getElementById("selectedAction").textContent = action;
        document.getElementById("confirmSerialNo").textContent = serialNo;

        // Open the second modal
        const confirmationModal = new bootstrap.Modal(document.getElementById("confirmationModal"));
        confirmationModal.show();
        }

        

        function confirmAction() {
            var action = document.getElementById("selectedAction").textContent;
            var serialNo = document.getElementById("confirmSerialNo").textContent;
            var fullData = JSON.parse(document.getElementById("fullData").value);

            // Update the JSON with the selected action
            fullData.selected_action = {
                serial_number: serialNo,
                status: action,
            };

            // Send the updated data to the server to update the database
            updateItemStatus(fullData, function(success) {
                if (success) {
                    // Close the confirmation modal only if the update was successful
                    const confirmationModal = bootstrap.Modal.getInstance(document.getElementById("confirmationModal"));
                    confirmationModal.hide();
                }
            });
        }






        function updateItemStatus(updatedData, callback) {
            fetch('/logistics/return-status-log/update-item-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(updatedData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Response from server:", data);
                if (data.status === 'success') {
                    // Redirect immediately on success without showing an alert
                    window.location.href = '/logistics/returnable'; // Redirect to the desired page
                    callback(true); // Indicate success to the callback
                } else {
                    // Display the error message if the update is not successful
                    alert('Failed to update status: ' + data.message);
                    callback(false); // Indicate failure to the callback
                }
            })
            .catch(error => {
                console.error('Error updating item status:', error);
                alert('Failed to update status: ' + error.message);
                callback(false); // Indicate failure to the callback
            });
        }





    </script>

@endsection
