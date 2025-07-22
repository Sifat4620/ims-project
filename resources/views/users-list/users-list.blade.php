@extends('partials.layouts.layoutTop')

@section('content')

<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
            <form method="GET" action="{{ route('user.list') }}" class="d-flex align-items-center gap-3">
                <select name="perPage" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                    @foreach([10, 20, 30, 40, 50] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>

                <input type="text" name="search" class="bg-base h-40-px w-auto" value="{{ $search }}" placeholder="Search" onchange="this.form.submit()">
            </form>
        </div>
        <a href="{{ route('user.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add New User
        </a>                    
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="d-flex align-items-center gap-10">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border input-form-dark" type="checkbox" id="selectAll">
                                </div>
                                S.L
                            </div>
                        </th>
                        <th scope="col">Join Date</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Department</th>
                        <th scope="col">Designation</th>
                        <th scope="col" class="text-center">Status</th>
                        {{-- <th scope="col" class="text-center">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                        <td>{{ $user->user_id }}</td>


                        {{-- image --}}
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $imagePath = $user->image;

                                    // Remove prefix if it already contains "profile_images/"
                                    $imagePath = str_starts_with($imagePath, 'profile_images/')
                                        ? $imagePath
                                        : 'profile_images/' . $imagePath;

                                    $imageSrc = $user->image
                                        ? asset('storage/' . $imagePath)
                                        : asset('images/default-avatar.png');
                                @endphp

                                <img src="{{ $imageSrc }}" alt="User Image"
                                    class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">

                                <div class="flex-grow-1">
                                    <span class="text-md mb-0 fw-normal text-secondary-light">{{ $user->full_name }}</span>
                                </div>
                            </div>
                        </td>


                        <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $user->email }}</span></td>
                        <td>{{ $user->department }}</td>
                        <td>{{ $user->role_name ?? 'No role assigned' }}</td>
                        <td class="text-center">
                            <span class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-10 justify-content-center">
                                {{-- <a href="{{ route('user.show', $user->id) }}" 
                                   class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" 
                                   title="View">
                                    <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                </a> --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</span>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="checkbox"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
