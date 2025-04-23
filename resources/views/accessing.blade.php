@extends('partials.layouts.layoutTop')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center mb-4">Welcome to the Invoice Management System</h5>
            <p class="text-center mb-4">How would you like to proceed?</p>

            <!-- Chatbot-like interface -->
            <div class="d-flex justify-content-center gap-4 mb-4">
                <button class="btn btn-primary btn-lg" onclick="handleAction('upgrade')">
                    Upgrade Information
                </button>
                <button class="btn btn-success btn-lg" onclick="handleAction('download')">
                    Download
                </button>
            </div>

            <!-- Display action based on user choice -->
            <div id="actionResponse" class="mt-4 text-center" style="display: none;">
                <p id="responseMessage" class="fw-semibold text-md text-secondary-light mb-2 my-1" style="font-size: 16px;"></p>
            </div>

            <!-- Prompt for procurement document type (appears after clicking 'Download') -->
            <div id="procurementPrompt" class="mt-4 text-center" style="display: none;">
                <p class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1" style="font-size: 16px;">Please choose the Procurement Document type:</p>
                <button class="btn btn-info btn-lg" onclick="selectProcurement('local')">Local</button>
                <button class="btn btn-info btn-lg" onclick="selectProcurement('foreign')">Foreign</button>
            </div>

            <!-- Prompt for LCPO No (appears after choosing Local or Foreign) -->
            <div id="lcpoPrompt" class="mt-4 text-center" style="display: none;">
                <p class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1" style="font-size: 16px;">Please enter the LC/PO Number:</p>
                <input type="text" id="lcpoNo" class="form-control w-auto mx-auto mb-4" style="max-width: 300px;" placeholder="Enter LC/PO Number">
                <button class="btn btn-primary btn-lg" onclick="submitLCPO()">Submit</button>
            </div>

            <!-- Final message after selecting procurement and entering LCPO No -->
            <div id="finalMessage" class="mt-4 text-center" style="display: none;">
                <p id="finalMessageText" class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1" style="font-size: 16px;"></p>
            </div>
        </div>
    </div>

    <script>
        // Function to handle the user's choice for upgrade or download
        function handleAction(action) {
            const responseMessage = document.getElementById('responseMessage');
            const actionResponse = document.getElementById('actionResponse');
            const procurementPrompt = document.getElementById('procurementPrompt');
            const lcpoPrompt = document.getElementById('lcpoPrompt');
            const finalMessage = document.getElementById('finalMessage');
            
            if (action === 'upgrade') {
                responseMessage.textContent = 'You chose to upgrade information. You can now edit or update any details.';
                procurementPrompt.style.display = 'none'; // Hide procurement prompt if upgrading
                lcpoPrompt.style.display = 'none'; // Hide LCPO prompt if upgrading
            } else if (action === 'download') {
                responseMessage.textContent = 'You chose to download the data. Please select the type of Procurement Document:';
                actionResponse.style.display = 'none'; // Hide previous response
                procurementPrompt.style.display = 'block'; // Show procurement selection
                lcpoPrompt.style.display = 'none'; // Hide LCPO prompt
                finalMessage.style.display = 'none'; // Hide final message
            }

            // Show the response section
            actionResponse.style.display = 'block';
        }

        // Function to handle the user’s choice for procurement document (Local or Foreign)
        function selectProcurement(type) {
            const finalMessageText = document.getElementById('finalMessageText');
            const finalMessage = document.getElementById('finalMessage');
            const lcpoPrompt = document.getElementById('lcpoPrompt');
            
            if (type === 'local') {
                finalMessageText.textContent = 'You have selected Local Procurement Document. Please enter the LCPO number.';
            } else if (type === 'foreign') {
                finalMessageText.textContent = 'You have selected Foreign Procurement Document. Please enter the LCPO number.';
            }

            // Hide procurement prompt and show LCPO number prompt
            document.getElementById('procurementPrompt').style.display = 'none';
            lcpoPrompt.style.display = 'block';
        }

        // Function to handle LCPO Number submission
        function submitLCPO() {
            const lcpoNo = document.getElementById('lcpoNo').value;
            const finalMessageText = document.getElementById('finalMessageText');
            const finalMessage = document.getElementById('finalMessage');

            if (lcpoNo.trim() === '') {
                alert('Please enter a valid LCPO number.');
                return;
            }

            // Display confirmation message
            finalMessageText.textContent = `You entered the LCPO Number: ${lcpoNo}. The download will start shortly.`;

            // Hide LCPO prompt and show the final message
            document.getElementById('lcpoPrompt').style.display = 'none';
            finalMessage.style.display = 'block';

            // Simulate download logic
            window.location.href = `/documents/${lcpoNo}/download`; // This will trigger the download route
        }
    </script>
@endsection


@section('extra-js')

@endsection
