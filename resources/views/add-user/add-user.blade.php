@extends('partials.layouts.layoutTop')

@section('content')
    <div class="card h-100 p-0 radius-12">
        <div class="card-body p-24">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-8 col-lg-10">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>

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

                                <!-- Image Upload with Preview -->
                                <div class="mb-24 mt-16">
                                    <label for="imageUpload" class="form-label fw-semibold text-primary-light text-sm mb-8">Upload Profile Image</label>
                                    <input type="file" name="profile_image" id="imageUpload" class="form-control" accept="image/*">

                                    <div class="mt-3">
                                        <img id="previewImage" src="" alt="Preview will show here" class="border radius-8 d-none" style="max-height: 150px;">
                                    </div>

                                    @error('profile_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

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
                                        <option value="Solutions">Solutions</option>
                                        <option value="TSD">TSD</option>
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
                                    <a href="javascript:history.back()" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 text-decoration-none">
                                        Cancel
                                    </a>
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

    <!-- JS Preview Script -->
    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            if (file && file.type.startsWith('image/')) {
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                alert('Please select a valid image (JPG, PNG, JPEG)');
            }
        });
    </script>
@endsection
