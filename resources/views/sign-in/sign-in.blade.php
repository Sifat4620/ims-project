<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('partials.head')

<body>
<section class="auth bg-base d-flex flex-wrap" style="min-height: 100vh;">
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <!-- Invisible circular container for balls -->
            <div id="ball-container"></div>
        </div>
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="{{ url('/') }}" class="mb-40 max-w-290-px">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
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

<style>
  /* Shared background for left and right */
  section.auth {
    background: #f0f2ff; /* example light blue */
  }

  /* Invisible circular container bounding box */
  #ball-container {
    position: relative;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    overflow: hidden;
    perspective: 900px;
    background: transparent;
    border: none;
  }

  /* Ball style */
  .ball {
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(145deg, #6a11cb, #2575fc);
    box-shadow:
      inset 0 6px 8px rgba(255, 255, 255, 0.4),
      0 10px 25px rgba(38, 57, 82, 0.3);
    cursor: pointer;
    transition:
      width 0.4s ease,
      height 0.4s ease,
      transform 0.4s ease,
      opacity 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
    will-change: transform, width, height, opacity, left, top;
    transform-style: preserve-3d;
  }

  /* Shrunk ball style after click */
  .ball.shrunk {
    width: 50px !important;
    height: 50px !important;
    opacity: 0.6;
    transform: scale(0.5) rotateX(15deg) rotateY(15deg) !important;
    pointer-events: none;
  }
</style>

<script>
  (function() {
    const container = document.getElementById('ball-container');
    const containerWidth = container.clientWidth;
    const containerHeight = container.clientHeight;

    class Ball {
      constructor(x, y, vx, vy, size = 100, shrunk = false) {
        this.size = size;
        this.shrunk = shrunk;
        this.x = x;
        this.y = y;
        this.vx = vx;
        this.vy = vy;

        this.el = document.createElement('div');
        this.el.className = 'ball';
        if (shrunk) {
          this.el.classList.add('shrunk');
        }
        container.appendChild(this.el);

        this.updatePosition();
        this.el.addEventListener('click', () => this.onClick());
      }

      updatePosition() {
        this.el.style.width = this.size + 'px';
        this.el.style.height = this.size + 'px';
        this.el.style.left = this.x + 'px';
        this.el.style.top = this.y + 'px';

        // 3D rotation for some subtle movement effect
        const rotX = Math.sin(Date.now() / 1000 + this.x) * 10;
        const rotY = Math.cos(Date.now() / 1000 + this.y) * 10;
        this.el.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
      }

      onClick() {
        if (this.shrunk) return; // ignore clicks on shrunk balls

        // Shrink current ball
        this.shrunk = true;
        this.size = 50;
        this.el.classList.add('shrunk');
        this.updatePosition();

        // Create new ball at center with random velocity
        const newBall = new Ball(
          (containerWidth - 100) / 2,
          (containerHeight - 100) / 2,
          (Math.random() * 4 + 1) * (Math.random() < 0.5 ? 1 : -1),
          (Math.random() * 4 + 1) * (Math.random() < 0.5 ? 1 : -1),
          100,
          false
        );
        balls.push(newBall);
      }

      move() {
        if (this.shrunk) {
          // Shrunk balls still bounce but slower
          this.x += this.vx * 0.5;
          this.y += this.vy * 0.5;
        } else {
          this.x += this.vx;
          this.y += this.vy;
        }

        // Bounce inside circle container boundary
        // We treat the container as circle of radius = containerWidth/2
        const radius = containerWidth / 2;
        // Ball center
        const cx = this.x + this.size / 2;
        const cy = this.y + this.size / 2;
        const centerX = radius;
        const centerY = radius;

        // Vector from center to ball center
        const dx = cx - centerX;
        const dy = cy - centerY;
        const dist = Math.sqrt(dx * dx + dy * dy);

        // Ball radius
        const ballRadius = this.size / 2;

        if (dist + ballRadius > radius) {
          // Reflect velocity vector

          // Normal vector (from center to ball)
          const nx = dx / dist;
          const ny = dy / dist;

          // Velocity dot normal
          const vDotN = this.vx * nx + this.vy * ny;

          // Reflect velocity
          this.vx = this.vx - 2 * vDotN * nx;
          this.vy = this.vy - 2 * vDotN * ny;

          // Push ball just inside circle boundary
          const overlap = dist + ballRadius - radius;
          this.x -= overlap * nx;
          this.y -= overlap * ny;
        }

        this.updatePosition();
      }
    }

    // Store all balls here
    const balls = [];

    // Initial ball center
    const startX = (containerWidth - 100) / 2;
    const startY = (containerHeight - 100) / 2;

    // Create initial ball with random velocity
    balls.push(new Ball(
      startX,
      startY,
      (Math.random() * 4 + 1) * (Math.random() < 0.5 ? 1 : -1),
      (Math.random() * 4 + 1) * (Math.random() < 0.5 ? 1 : -1)
    ));

    // Animation loop
    function animate() {
      balls.forEach(ball => ball.move());
      requestAnimationFrame(animate);
    }
    animate();

  })();
</script>

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
