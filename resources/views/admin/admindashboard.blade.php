@php use Illuminate\Support\Facades\Auth; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      width: 250px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding-top: 1rem;
      position: fixed;
    }
    .sidebar .nav-link {
      font-weight: 500;
      color: #6c757d;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      color: #4b00ff;
      background-color: #f0f4ff;
      font-weight: 600;
    }
    .logo-icon {
      background: linear-gradient(135deg, #7f00ff, #e100ff);
      border-radius: 12px;
      width: 36px;
      height: 36px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      margin-right: 10px;
    }
    .header {
      margin-left: 250px;
      background-color: #f4f7fe;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .search-box {
      position: relative;
      width: 300px;
    }
    .search-box input {
      border-radius: 12px;
      padding-left: 15px;
      padding-right: 35px;
    }
    .search-box i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #1f2d3d;
    }
    .header-icons img, .header-icons .icon-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 15px;
      background-color: #e8ecf4;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .icon-circle i {
      color: #4b00ff;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-3">
    <div class="d-flex align-items-center mb-4">
      <div class="logo-icon">🔵</div>
      <span class="fs-5 fw-bold">Book</span>
    </div>
    <ul class="nav nav-pills flex-column">
      <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-home"></i> Dashboard</a></li>
      <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link"><i class="fas fa-user"></i>User Profile</a></li>
      <li class="nav-item"><a href="{{ route('admin.categori') }}" class="nav-link"><i class="fas fa-table"></i> Add categories</a></li>
      <li class="nav-item"><i class="fas fa-lock"></i> 
        <form method="POST" action="{{ route('logout') }}"> 
          @csrf
          <button type="submit" class="dropdown-item">Logout</button>
        </form>
      </li>   
    </ul>
  </div>

  <!-- Header -->
  <div class="header">
    <div class="search-box">
  <form method="GET" action="{{ route('users.index') }}">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc email" value="{{ request('search') }}">
      <button class="btn btn-primary" type="submit">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </form>
</div>
    <div class="header-icons d-flex align-items-center">
      <div class="icon-circle"><img src="https://media.istockphoto.com/id/864417828/vi/vec-to/c%E1%BB%9D-vi%E1%BB%87t-nam.jpg?s=612x612&w=0&k=20&c=4wqSdySA6JkSO6Xw6m4255maL3mqQx4m0tTH3Q14u_U=" alt="EN"></div>
      <div class="dropdown">
  <a href="{{ route('admin.notification') }}" class="icon-circle position-relative" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-bell"></i>
    @php
      use App\Models\Contact;
      $contactCount = Contact::where('status','0')->count();
    @endphp
    @if($contactCount > 0)
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ $contactCount }}
      </span>
    @endif
      
  </a>
  {{-- <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px; max-height: 300px; overflow-y: auto;">
    <li class="fw-bold px-2 text-primary">Liên hệ gần đây:</li>
    @foreach(Contact::latest()->take(5)->get() as $contact)
      <li class="dropdown-item small text-wrap">
        <strong>{{ $contact->name }}</strong><br>
        <span class="text-muted">{{ $contact->email }}</span><br>
        <em class="text-secondary">{{ \Illuminate\Support\Str::limit($contact->message, 40) }}</em>
      </li>
      <hr class="my-1">
    @endforeach
    <li><a class="dropdown-item text-center text-primary" href="{{ route('contacts.index') }}">Xem tất cả</a></li>
  </ul> --}}
</div>
      <img src="{{ Auth::user()->avatar ?? 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/10/avatar-trang-4.jpg' }}" alt="User">
    </div>
  </div>

  <!-- Main content placeholder -->
   <div style="margin-left: 250px; padding: 2rem;">
    <main>
      @yield('main')
    </main>
  </div>
@yield('scripts')
</body>
</html>
