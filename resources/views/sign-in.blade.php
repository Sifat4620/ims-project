<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('partials.head')

<body>
<section class="auth bg-base d-flex flex-wrap">
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
        </div>
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="{{ url('/') }}" class="mb-40 max-w-290-px">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="">
                </a>
                {{-- <h4 class="mb-12">Sign In to your Account</h4> --}}
                <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your details</p>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('auth.signin') }}" method="POST">
                @csrf
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="bi:person-check"></iconify-icon>
                    </span>
                    <input type="text" name="user_id" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Employee ID" value="{{ old('user_id') }}">
                </div>
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="bi:lock"></iconify-icon>
                        </span>
                        <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Password">
                    </div>
                </div>
                <div class="text-center mb-16">
                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</section>


    @include('partials.scripts')

    <script>
            // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle('.toggle-password');
    </script>
    <script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>

</body>

</html>
