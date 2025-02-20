@extends('partials.layouts.layoutTop')

@section('content')

<div class="row gy-4">
    <!-- Left Section: Data Entry Form -->
    <div class="col-md-12" id="left-section">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="text-white" style="font-family: 'Roboto', sans-serif;">Data Entry Form</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('dataentry.store') }}" method="POST" id="item-received-form">
                    @csrf

                    <!-- Select L/C or PO Type -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="lc-dropdown" class="form-label">Select L/C or PO Type</label>
                            <select id="lc-dropdown" class="form-select" name="lc_po_type">
                                <option value="" disabled selected>Select an option</option>
                                @foreach($lcpoTypes as $item)
                                    <option value="{{ $item->lcpo_no }}">{{ $item->type }} - {{ $item->lcpo_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Category, Brand, and Model in the Same Row -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="product_category" class="form-label">Category</label>
                            <select class="form-select" id="product_category" name="category">
                                <option value="" disabled selected>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="product_brand" class="form-label">Brand</label>
                            <select class="form-select" id="product_brand" name="brand">
                                <option value="" disabled selected>Select a brand</option>
                                <!-- Brand options will be dynamically populated -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="model-no" class="form-label">Model No</label>
                            <input type="text" id="model-no" class="form-control" name="model_no" placeholder="Enter model number">
                        </div>
                    </div>

                    <!-- Serial No in Full Row -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="serial-input" class="form-label">Serial No(s)</label>
                            <textarea id="serial-input" class="form-control" name="serial_no" rows="1" placeholder="Enter serial numbers, separated by commas or line breaks"></textarea>
                        </div>
                    </div>

                    <!-- Condition, Status, Holding Location, and Date in One Row -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="item-condition" class="form-label">Condition</label>
                            <select class="form-select" id="item-condition" name="condition">
                                <option value="Good">Good</option>
                                <option value="Faulty">Faulty</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="issue-status" class="form-label">Issued</label>
                            <select class="form-select" id="issue-status" name="status">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="holding-location" class="form-label">Holding Location</label>
                            <select class="form-select" id="holding-location" name="holding_location">
                                <option value="Floor 13">Floor 13</option>
                                <option value="Floor 14">Floor 14</option>
                                <option value="Basement">Basement</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" id="date" class="form-control" name="date">
                        </div>
                        
                        <script>
                            // Get today's date
                            const today = new Date();
                            const formattedDate = today.toISOString().split('T')[0]; // Format date as yyyy-mm-dd
                        
                            // Set the default value of the input field
                            document.getElementById('date').value = formattedDate;
                        </script>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="specification-textarea" class="form-label">Specification</label>
                                <textarea id="specification-textarea" class="form-control" name="specification" rows="3" placeholder="Enter specifications"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Add Button -->
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="add-entry-btn">Add Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{--  --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryDropdown = document.getElementById('product_category');
            const brandDropdown = document.getElementById('product_brand');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
            categoryDropdown.addEventListener('change', function () {
                const selectedCategory = this.value;
    
                // Clear the Brand dropdown
                brandDropdown.innerHTML = '<option value="" disabled selected>Select a brand</option>';
    
                // Fetch related brands via AJAX
                fetch('{{ route("get.brands") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ category: selectedCategory })
                })
                .then(response => response.json())
                .then(data => {
                    // Populate the Brand dropdown with the fetched brands
                    data.forEach(brand => {
                        const option = document.createElement('option');
                        option.value = brand.product_brand;
                        option.textContent = brand.product_brand;
                        brandDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching brands:', error));
            });
        });
    </script>
    
    {{--  --}}
    <!-- Right Section: Summary Table -->
    <div class="col-md-12 d-none" id="right-section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Item Summary</h5>
                <div class="table-responsive">
                    <table class="table vertical-striped-table mb-0" id="entry-table">
                        <thead>
                            <tr>
                                <th scope="col">LC Number</th>
                                <th scope="col">Category & Brand</th>
                                <th scope="col">Model</th>
                                <th scope="col">Serial(s)</th>
                                <th scope="col" class="text-center">Condition & Status</th>
                                <th scope="col" class="text-center">Specification</th>
                                <th scope="col" class="text-center">Holding Location</th>
                                <th scope="col" class="text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be added here via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <!-- Submit All Entries Button -->
                <div class="col-12 mt-4">
                    <button type="button" class="btn btn-success text-sm btn-sm px-12 py-16 w-100 radius-12" id="submit-all-entries">Submit All Entries</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script to handle dynamic table updates -->
<script>
    // Event listener for the "Add Entry" button
    document.getElementById('add-entry-btn').addEventListener('click', function() {
        var lcPoType = document.getElementById('lc-dropdown').value;
        var category = document.getElementById('product_category').value;
        var brand = document.getElementById('product_brand').value;
        var modelNo = document.getElementById('model-no').value;
        var serialNo = document.getElementById('serial-input').value;
        var specification = document.getElementById('specification-textarea').value;  // Get specification value
        var condition = document.getElementById('item-condition').value;
        var status = document.getElementById('issue-status').value;
        var holdingLocation = document.getElementById('holding-location').value;
        var date = document.getElementById('date').value;

        // Validate inputs including specification
        if (!lcPoType || !category || !brand || !modelNo || !serialNo || !specification || !condition || !status || !holdingLocation || !date) {
            alert('Please fill all fields!');
            return;
        }

        // Split serial numbers by either commas or line breaks
        var serialArray = serialNo.split(/[\s,]+/);

        // Create a new row and add it to the summary table
        var table = document.getElementById('entry-table').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow();

        newRow.innerHTML = `
            <td>${lcPoType}</td>
            <td>${category} & ${brand}</td>
            <td>${modelNo}</td>
            <td>${serialArray.join(', ')}</td>
            <td class="text-center">${condition} & ${status}</td>
            <td>${specification}</td> <!-- Added specification -->
            <td>${holdingLocation}</td>
            <td>${date}</td>
        `;

        // Clear the form fields after adding the entry
        document.getElementById('item-received-form').reset();
    });

    // Event listener for the "Submit All Entries" button
    document.getElementById('submit-all-entries').addEventListener('click', function() {
        // Prepare an array to store all the entries
        const entries = [];

        // Get all the table rows containing the entries
        const rows = document.querySelectorAll('#entry-table tbody tr');

        rows.forEach(row => {
            const lcPoType = row.querySelector('td:nth-child(1)').innerText.trim();
            const categoryBrand = row.querySelector('td:nth-child(2)').innerText.trim();
            const modelNo = row.querySelector('td:nth-child(3)').innerText.trim();
            const serialNo = row.querySelector('td:nth-child(4)').innerText.trim().split(',').map(s => s.trim());
            const conditionStatus = row.querySelector('td:nth-child(5)').innerText.trim();
            const specification = row.querySelector('td:nth-child(6)').innerText.trim();  // Get specification text
            const holdingLocation = row.querySelector('td:nth-child(7)').innerText.trim();
            const date = row.querySelector('td:nth-child(8)').innerText.trim();

            entries.push({
                lc_po_type: lcPoType,
                category: categoryBrand.split(' & ')[0],  // Split category & brand
                brand: categoryBrand.split(' & ')[1],
                model_no: modelNo,
                serial_no: serialNo,
                condition_status: conditionStatus,
                specification: specification,  // Include specification in the submission data
                holding_location: holdingLocation,
                date: date
            });
        });

        // If no entries found, alert the user
        if (entries.length === 0) {
            alert('No data to submit.');
            return;
        }

        // Create a hidden form element to submit the data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/data-entry'; // Your form's POST route

        // Add CSRF token (assuming Blade is rendering the token)
        const csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = '_token';
        csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token from meta tag
        form.appendChild(csrfTokenInput);

        // Add the entries array to the form as JSON
        const entriesInput = document.createElement('input');
        entriesInput.type = 'hidden';
        entriesInput.name = 'entries'; // This will be your 'entries[]' in Laravel
        entriesInput.value = JSON.stringify(entries); // Convert entries array to JSON
        form.appendChild(entriesInput);

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    });
</script>
<!-- Include this in your Blade or HTML file -->
<style>
    /* General Transition for Smooth Animations */
    #left-section,
    #right-section {
        transition: all 0.5s ease; /* Smooth transition for all properties */
        opacity: 1; /* Fully visible by default */
        visibility: visible; /* Fully visible by default */
        height: auto; /* Auto height for smooth expansion */
    }

    /* Hidden State */
    .hidden {
        opacity: 0; /* Fade out */
        visibility: hidden; /* Make invisible */
        height: 0; /* Collapse height */
        overflow: hidden; /* Hide overflowing content */
    }

    /* Visible State */
    .visible {
        opacity: 1; /* Fully visible */
        visibility: visible; /* Visible */
        height: auto; /* Expand to full height */
    }

    /* Utility Classes */
    .d-none {
        display: none !important; /* Completely hide the element */
    }

    /* Responsive Utility */
    @media (max-width: 768px) {
        #left-section,
        #right-section {
            transition: all 0.5s ease; /* Maintain smooth animations on smaller screens */
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addEntryBtn = document.getElementById('add-entry-btn'); // Button to add entry
        const leftSection = document.getElementById('left-section'); // Left section (data entry form)
        const rightSection = document.getElementById('right-section'); // Right section (summary table)

        addEntryBtn.addEventListener('click', function () {
            // Hide the left section
            leftSection.classList.add('hidden');
            leftSection.classList.remove('visible');
            setTimeout(() => {
                leftSection.classList.add('d-none');
            }, 500); // Match the animation duration

            // Show the right section
            rightSection.classList.remove('d-none');
            setTimeout(() => {
                rightSection.classList.add('visible');
                rightSection.classList.remove('hidden');
            }, 10); // Slight delay to allow the transition
        });
    });
</script>


    
@endsection


@section('extra-js')
<script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>
@endsection
