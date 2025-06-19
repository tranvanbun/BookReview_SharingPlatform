@php use Illuminate\Support\Facades\Auth; @endphp
@extends('main.main')
@section('content')
<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('main') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Profile</li>
          </ol>
        </nav>
      </div>
    </div>

    <form method="POST" action="{{ route('user.update', Auth::user()->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body text-center">
              <img id="avatarPreview"
                   src="{{ Auth::user()->avatar ?? 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/10/avatar-trang-4.jpg' }}"
                   alt="avatar"
                   class="rounded-circle img-fluid"
                   style="width: 150px; height: 150px; object-fit: cover;">
              <div class="mt-3">
                <input type="file" name="avatar" class="form-control mt-2" accept="image/*" onchange="previewAvatar(event)" disabled>
              </div>
              <h5 class="my-3">{{ Auth::user()->name }}</h5>
              <p class="text-muted mb-1">{{ Auth::user()->bio }}</p>
              <p class="text-muted mb-4">{{ Auth::user()->contact }}</p>
              <div class="d-flex justify-content-center mb-2">
                <button type="button" class="btn btn-primary" disabled>Follow</button>
                <button type="button" class="btn btn-outline-primary ms-1" disabled>Message</button>
              </div>
            </div>
          </div>
        </div>  

        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-body">

              @php
                $user = Auth::user();
              @endphp

              <div class="row mb-3">
                <div class="col-sm-3"><label class="mb-0">Full Name</label></div>
                <div class="col-sm-9">
                  <input type="text" name="name" class="form-control" value="{{ $user->name }}" disabled>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-3"><label class="mb-0">Email</label></div>
                <div class="col-sm-9">
                  <input type="email" name="email" class="form-control" value="{{ $user->email }}" disabled>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-3"><label class="mb-0">Phone</label></div>
                <div class="col-sm-9">
                  <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" disabled>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-3"><label class="mb-0">Contact</label></div>
                <div class="col-sm-9">
                  <input type="text" name="contact" class="form-control" value="{{ $user->contact }}" disabled>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-3"><label class="mb-0">Address</label></div>
                <div class="col-sm-9">
                  <input type="text" name="address" class="form-control" value="{{ $user->address }}" disabled>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-3"><label class="mb-0">Bio</label></div>
                <div class="col-sm-9">
                  <textarea name="bio" class="form-control" rows="3" disabled>{{ $user->bio }}</textarea>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div> <!-- /.row -->

      <div class="row">
        <div class="col text-end">
          <button type="button" class="btn btn-warning me-2" onclick="enableEdit()">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button type="submit" class="btn btn-success" id="saveBtn" disabled>
            <i class="fas fa-save"></i> Save Changes
          </button>
        </div>
      </div>

    </form>
  </div>
</section>

<script>
  function previewAvatar(event) {
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('avatarPreview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }

  function enableEdit() {
    document.querySelectorAll('input, textarea').forEach(el => {
      el.removeAttribute('disabled');
    });
    // Cho phép upload avatar
    document.querySelector('input[type="file"][name="avatar"]').removeAttribute('disabled');
    // Bật nút lưu
    document.getElementById('saveBtn').removeAttribute('disabled');
  }
</script>
@endsection
