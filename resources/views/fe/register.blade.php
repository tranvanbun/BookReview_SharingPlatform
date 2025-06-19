<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <title>Book Review & Sharing Platform</title>
  <style>
    body {
      background-color: #f5f5f5;
    }
    .container-custom {
      max-width: 1200px;
      margin: 0 auto;
    }
    .main-title {
      font-weight: 700;
      font-size: 2.5rem;
    }
    .highlight {
      color: #0d6efd;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <!-- Section: Design Block -->
  <section class="py-5">
    <div class="container container-custom">
      <div class="row align-items-start">
        <!-- Left Text -->
        <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
          <h1 class="main-title mb-4">
            Web<br />
            <span class="highlight">Book Review & Sharing Platform</span>
          </h1>
          <p class="text-muted">
            Tìm kiếm và đánh giá sách. Cùng chia sẻ cảm nhận và khám phá các đầu sách mới mỗi ngày.
          </p>
        </div>

        <!-- Right Form -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body p-4">

              <form action="{{ url('/register') }}" method="POST" enctype="multipart/form-data">
  @csrf

  {{-- Hiển thị lỗi tổng quát --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Hiển thị thông báo thành công --}}
  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Name -->
  <div class="mb-3">  
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name') }}" placeholder="Enter your name" required />
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <!-- Email -->
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email') }}" placeholder="Enter a valid email" required />
    @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <!-- Password -->
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
           placeholder="Enter your password" required />
    @error('password')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <!-- Confirm Password -->
  <div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
           placeholder="Re-enter your password" required />
  </div>

  <!-- Avatar Upload -->
  {{-- <div class="mb-3">
    <label for="avatar" class="form-label">Upload Avatar</label>
    <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror"
           accept="image/*" />
    @error('avatar')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div> --}}

  <!-- Bio -->
  <div class="mb-3">
    <label for="bio" class="form-label">About You</label>
    <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror"
              rows="3" placeholder="Tell us a bit about yourself...">{{ old('bio') }}</textarea>
    @error('bio')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <!-- Submit -->
  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg">Sign up</button>
  </div>

  <!-- Sign in link -->
  <div class="text-center">
    <p class="mb-0">
      or <a href="{{ route('login') }}" class="text-primary">sign in</a> if you already have an account
    </p>
  </div>
</form>

            </div>
          </div>
        </div>
        <!-- End Form -->
      </div>
    </div>
  </section>
  <!-- End Section -->
</body>
</html>
