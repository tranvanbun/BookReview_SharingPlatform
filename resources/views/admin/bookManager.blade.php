@php use Illuminate\Support\Facades\Auth; @endphp
@extends('admin.admindashboard')

@section('main')
<div class="row row-cols-1 row-cols-md-2 g-4">
    @foreach ($bookItemsWaits as $bookItemsWait)
    <div class="col">
        <div class="card h-100 shadow-sm">
            <!-- Sử dụng position-relative trong card-body để định vị phần tử tuyệt đối bên trong -->
            <div class="card-body position-relative">
                <!-- Thông tin chung của bài viết -->
                <h5 class="card-title">{{ $bookItemsWait->title }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Tác giả: {{ $bookItemsWait->author }}</h6>
                <p class="card-text">{{ $bookItemsWait->description }}</p>
                <p><strong>Thể loại:</strong> {{ $bookItemsWait->genre->name ?? 'Không có' }}</p>
                <p><strong>Id:</strong> {{ $bookItemsWait->id }}</p>

                @if($bookItemsWait->cover_img)
                <img src="{{ asset('storage/' . $bookItemsWait->cover_img) }}" alt="Ảnh bìa" class="img-fluid my-2" style="max-height: 150px;">
                @endif
                @if($bookItemsWait->link)
                <a href="{{ asset('storage/' . $bookItemsWait->link) }}" class="btn btn-outline-secondary mt-2" target="_blank">📄 Xem tệp</a>
                @endif

                @if($bookItemsWait->status == 0)
                <h6 class="card-subtitle mb-2 text-muted">Trạng thái: Chờ phê duyệt</h6>
                @else
                <h6 class="card-subtitle mb-2 text-muted">Trạng thái: Đã duyệt</h6>
                @endif
                <!-- Hiển thị người đăng ở góc dưới bên phải, phía trên các nút -->
                <div class="position-absolute text-muted text-end" style="bottom: 50px; right: 10px;">
                    <small>Người đăng: {{ $bookItemsWait->user->name ?? 'Không rõ' }}</small><br>
                    <small>ID người đăng: {{ $bookItemsWait->user->id ?? 'N/A' }}</small>
                </div>

                <!-- Nút duyệt/xóa ở dưới -->
                @if($bookItemsWait->status == 0)
                <div class="position-absolute d-flex gap-2" style="bottom: 10px; right: 10px;">
                    <!-- Nút Phê duyệt -->
                    <form action="{{ route('books.approve', $bookItemsWait->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm">Phê duyệt</button>
                    </form>

                    <!-- Nút Xóa -->
                    <form action="{{ route('books.destroy', $bookItemsWait->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection