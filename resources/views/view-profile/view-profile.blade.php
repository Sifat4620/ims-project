@extends('partials.layouts.layoutTop')

@section('content')
<div class="row gy-4">
    <div class="col-lg-4">
    <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
        {{-- Background Banner --}}
        <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="Banner" class="w-100 object-fit-cover">

        @php
            // Profile image path check
            $imagePath = $user->image;
            $imagePath = str_starts_with($imagePath, 'profile_images/')
                ? $imagePath
                : 'profile_images/' . $imagePath;

            $imageSrc = ($user->image && Storage::disk('public')->exists($imagePath))
                ? asset('storage/' . $imagePath)
                : asset('images/default-avatar.png');

            // Designation title fallback
            $designationRole = collect($roles)->firstWhere('id', $user->designation);
            $designation = $designationRole->title ?? $designationRole->name ?? 'N/A';
        @endphp

        <div class="pb-24 ms-16 mb-24 me-16 mt--100">
            <div class="text-center border border-top-0 border-start-0 border-end-0">
                {{-- Profile Image --}}
                <img src="{{ $imageSrc }}" alt="User Image"
                     class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">

                {{-- Name & Email --}}
                <h6 class="mb-0 mt-16">{{ $user->full_name ?? 'User not found' }}</h6>
                <span class="text-secondary-light mb-16">{{ $user->email ?? 'Email not available' }}</span>
            </div>

            {{-- Personal Info --}}
            <div class="mt-24">
                <h6 class="text-xl mb-16">Personal Info</h6>
                <ul>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->full_name ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">ID</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->user_id ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->email ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">Phone Number</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->phone ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">Department</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->department ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">Designation</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $designation }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-1 mb-12">
                        <span class="w-30 text-md fw-semibold text-primary-light">Languages</span>
                        <span class="w-70 text-secondary-light fw-medium">: English</span>
                    </li>
                    <li class="d-flex align-items-start gap-1">
                        <span class="w-30 text-md fw-semibold text-primary-light">Bio</span>
                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->description ?? 'No bio available' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-24">
                <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                            Edit Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24" id="pills-change-password-tab" data-bs-toggle="pill" data-bs-target="#pills-change-password" type="button" role="tab" aria-controls="pills-change-password" aria-selected="false" tabindex="-1">
                            Role Summary
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    {{-- Edit Profile Tab --}}
                    <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab" tabindex="0">
                        {{-- <h6 class="text-md text-primary-light mb-16">Profile Image</h6> --}}
                        <!-- Upload Image Start -->
                        {{-- <div class="mb-24 mt-16">
                            <div class="avatar-upload">
                                <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                    <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                    <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                        <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                    </label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ $user->profile_photo_url ?? asset('assets/images/default-profile.png') }}');"></div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- Upload Image End -->

                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="name" name="name" value="{{ old('name', $user->full_name) }}" placeholder="Enter your full name" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="employeeid" class="form-label fw-semibold text-primary-light text-sm mb-8">ID <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="employeeid" placeholder="Enter ID" value="{{ $user->user_id }}" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                        <input type="email" class="form-control radius-8" id="email" placeholder="Enter email address" value="{{ $user->email }}" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="phone" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                        <input type="text" class="form-control radius-8" id="phone" name="phone" placeholder="Enter phone number" value="{{ old('phone', $user->phone) }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="desig" class="form-label fw-semibold text-primary-light text-sm mb-8">Designation <span class="text-danger-600">*</span></label>
                                        <select class="form-control radius-8" id="desig" name="designation" required>
                                            <option value="" disabled {{ old('designation', $user->designation) ? '' : 'selected' }}>Select Designation</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('designation', $user->designation) == $role->id ? 'selected' : '' }}>
                                                    {{ $role->title ?? $role->name ?? 'No Title' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="language" class="form-label fw-semibold text-primary-light text-sm mb-8">Language <span class="text-danger-600">*</span></label>
                                        <select class="form-control radius-8 form-select" id="language" name="language" required>
                                            <option value="English" {{ old('language', $user->language ?? '') == 'English' ? 'selected' : '' }}>English</option>
                                            <option value="Bangla" {{ old('language', $user->language ?? '') == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                                            <option value="Hindi" {{ old('language', $user->language ?? '') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                            <option value="Spanish" {{ old('language', $user->language ?? '') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                            <option value="Chinese" {{ old('language', $user->language ?? '') == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-20">
                                        <label for="description" class="form-label fw-semibold text-primary-light text-sm mb-8">Bio</label>
                                        <textarea class="form-control radius-8" id="description" name="description" rows="4" placeholder="Write your bio...">{{ old('description', $user->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- <button type="submit" class="btn btn-primary">Update Profile</button> --}}
                        </form>
                    </div>

                    {{-- System summary  --}}
                    <div class="tab-pane fade" id="pills-change-password" role="tabpanel" aria-labelledby="pills-change-password-tab" tabindex="0">

                        @php
                            // User designation (assume from $user->designation)
                            $designation = $user->designation ?? '';

                            // Sidebar menu items and their allowed roles
                            $sidebarItems = [
                                ['item' => 'Dashboard', 'roles' => ['All authenticated users']],
                                ['item' => 'Product Entry', 'roles' => ['Admin', 'Inventory Manager', 'Inventory Entry', 'Sales']],
                                ['item' => 'Product Issue', 'roles' => ['Admin', 'Inventory Manager', 'Inventory Entry', 'Sales']],
                                ['item' => 'Inventory Report', 'roles' => ['All authenticated users']],
                                ['item' => 'Procurement Document', 'roles' => ['Admin', 'Inventory Manager', 'Inventory Entry', 'Sales']],
                                ['item' => 'Master Data Entry', 'roles' => ['Admin', 'Inventory Entry']],
                                ['item' => 'Return Product Management', 'roles' => ['Admin', 'Inventory Manager']],
                                ['item' => 'Inventory Management', 'roles' => ['All authenticated users']],
                                ['item' => 'Accessing & Downloading Your Info', 'roles' => ['Admin']],
                                ['item' => 'Security', 'roles' => ['Admin']],
                                ['item' => 'System Monitoring', 'roles' => ['Admin']],
                            ];

                            // Check if designation is in allowed roles or if allowed is 'All authenticated users'
                            function hasAccess($roles, $designation) {
                                if (in_array('All authenticated users', $roles)) {
                                    return true; // everyone logged in has access
                                }
                                return in_array($designation, $roles);
                            }
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sidebar Menu Item</th>
                                        <th>Visible to Roles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sidebarItems as $item)
                                        @php
                                            $access = hasAccess($item['roles'], $designation);
                                        @endphp
                                        <tr @if($access) style="background-color: #d4edda;" @endif>
                                            <td>{{ $item['item'] }}</td>
                                            <td>{{ implode(', ', $item['roles']) }}</td>
                                        </tr>
                                    @endforeach
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

@push('scripts')
<script>
    // Optional: Script to preview profile image on upload
    document.getElementById('imageUpload').addEventListener('change', function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').style.backgroundImage = 'url('+e.target.result +')';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
