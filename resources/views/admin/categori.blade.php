@extends('admin.admindashboard')

@section('main')
<div class="container">
    

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container">
    <h2>Danh sách thể loại sách</h2>
    @if($categories->isEmpty())
        <p>Chưa có thể loại nào.</p>
    @else
        <ul class="list-group mb-4">
            @foreach($categories as $category)
                <li class="list-group-item">{{ $category->name }}</li>
            @endforeach
        </ul>
    @endif
    <h2>Thêm thể loại sách</h2>
</div>
    {{--  --}}
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên thể loại</label>
            <input type="text" name="name" class="form-control" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-2">Thêm</button>
    </form>
</div>
@endsection