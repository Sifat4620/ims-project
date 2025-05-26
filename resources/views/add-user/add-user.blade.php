@extends('partials.layouts.layoutTop')

@section('content')
    <div class="card h-100 p-0 radius-12">
        <div class="card-body p-24">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-8 col-lg-10">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                            <!-- Form to create user -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                 <!-- Upload Image Start -->
                                <div class="mb-24 mt-16">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                            <input type="file" name="profile_image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                            <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview">
                                                <img id="previewImage" src="#" alt="Image preview" class="d-none" style="max-height: 150px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Upload Image End -->
                                <div class="mb-20">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control radius-8" name="full_name" id="name" placeholder="Enter Full Name" required>
                                    @error('full_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="employeeid" class="form-label fw-semibold text-primary-light text-sm mb-8">Employee ID<span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control radius-8" name="user_id" id="employeeid" placeholder="Enter ID" required>
                                    @error('user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                    <input type="email" class="form-control radius-8" name="email" id="email" placeholder="Enter email address" required>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span class="text-danger-600">*</span></label>
                                    <input type="password" class="form-control radius-8" name="password" id="password" placeholder="Enter Strong Password" required>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                    <input type="text" class="form-control radius-8" name="phone" id="number" placeholder="Enter phone number">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="depart" class="form-label fw-semibold text-primary-light text-sm mb-8">Department <span class="text-danger-600">*</span></label>
                                    <select class="form-control radius-8 form-select" name="department" id="depart" required>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Marketing">Solutions</option>
                                        <option value="HR">TSD</option>
                                    </select>
                                    @error('department')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="desig" class="form-label fw-semibold text-primary-light text-sm mb-8">Designation <span class="text-danger-600">*</span></label>
                                    <select class="form-control radius-8 form-select" name="title" id="desig" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-20">
                                    <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                                    <textarea name="description" class="form-control radius-8" id="desc" placeholder="Write description..."></textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
<script>
    // Show Image Preview on Image Upload
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const reader = new FileReader();
        const file = event.target.files[0];
        
        console.log(file); // Debug: Check the file object
        
        // Check if file is an image
        if (file && file.type.startsWith('image/')) {
            reader.onload = function(e) {
                const imagePreview = document.getElementById('previewImage');
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none'); // Show the preview
                console.log("Image loaded successfully");
            };
            reader.readAsDataURL(file);
        } else {
            alert('Please upload a valid image file (jpg, jpeg, png).');
        }
    });

    // To check the form data before submitting (for debugging)
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent form submission
        const formData = new FormData(this);
        
        // For debugging, log the form data to the console
        for (let [key, value] of formData.entries()) {
            console.log(key + ": " + value);
        }
        
        // Uncomment below line to submit after logging the data
        // this.submit(); 
    });
</script>
@endsection
