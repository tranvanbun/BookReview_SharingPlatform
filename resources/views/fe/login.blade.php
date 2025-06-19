<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <title>Login Page</title>
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    .wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .login-container {
      max-width: 1200px;
      width: 100%;
    }

    .bg-primary {
      background-color: #0d6efd !important;
    }

    .login-form {
      max-width: 400px;
      width: 100%;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    header {
      background-color: #f8f9fa;
      padding: 1rem 2rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    header h1 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 600;
      color: #333;
    }
    .highlight {
      color: #0d6efd;
    }
  </style>
</head>

<body>
  <div class="wrapper">

    <!-- Header -->
    <header>
      <h1 class="main-title mb-4">
            <span class="highlight">Book Review & Sharing Platform</span>
          </h1>
    </header>

    <!-- Main content -->
    <section class="content">
      <div class="login-container row g-0">
        <div class="col-md-6 d-flex align-items-center justify-content-center p-3">
          <img src="https://images.ctfassets.net/szez98lehkfm/56vYjD120Ksv6DQ9f5o0Y7/44b8144e012f8e93a0a53a4ce2fb1e2c/MyIC_Inline_63746"
            class="img-fluid" alt="Sample image" />
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center p-3">
          <form class="login-form" method="POST" action="{{ url('/login') }}">
    @csrf

    <div class="mb-4">
        <label class="form-label" for="form3Example3">Email address</label>
        <input type="email" name="email" id="form3Example3" class="form-control form-control-lg"
            placeholder="Enter a valid email address" value="{{ old('email') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="form3Example4">Password</label>
        <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
            placeholder="Enter password" />
    </div>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="form2Example3" />
            <label class="form-check-label" for="form2Example3">Remember me</label>
        </div>
        <a href="#!" class="text-body">Forgot password?</a>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Login</button>
        <p class="small fw-bold">
            Don't have an account?
            <a href="{{ route('register') }}" class="link-danger">Register</a>
        </p>
    </div>
</form>
        </div>
      </div>
    </section>
  </div>
</body>

</html>

