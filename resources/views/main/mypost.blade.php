@extends('main.main')
@section('content')

<!-- Nút mở modal -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách bài chờ duyệt</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
        + Thêm mới
    </button>
</div>

<!-- Modal Thêm Mới -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Thêm mới đối tượng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Lỗi trùng tiêu đề + tác giả -->
                        @if ($errors->has('duplicate'))
                        <div class="alert alert-danger">
                            {{ $errors->first('duplicate') }}
                        </div>
                        @endif

                        <!-- Tiêu đề -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tác giả -->
                        <div class="mb-3">
                            <label for="author" class="form-label">Tác giả</label>
                            <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
                            @error('author')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ảnh bìa -->
                        <div class="mb-3">
                            <label for="cover_img" class="form-label">Ảnh bìa (upload)</label>
                            <input type="file" name="cover_img" class="form-control mt-2" accept="image/*">
                            @error('cover_img')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File đính kèm -->
                        <div class="mb-3">
                            <label for="link" class="form-label">Tệp đính kèm (PDF, DOC,...)</label>
                            <input type="file" name="link" class="form-control" accept=".pdf,.doc,.docx">
                            @error('link')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thể loại -->
                        <div class="mb-3">
    <label class="form-label">Thể loại</label>
    <div class="d-flex flex-wrap gap-3">
        @foreach($categories as $category)
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="radio"
                    name="genre_id"
                    id="category_{{ $category->id }}"
                    value="{{ $category->id }}"
                    {{ old('genre_id') == $category->id ? 'checked' : '' }}
                    required
                >
                <label class="form-check-label" for="category_{{ $category->id }}">
                    {{ $category->name }}
                </label>
            </div>
        @endforeach
    </div>

    @error('genre_id')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
    </div>
</div>

<!-- Danh sách bài đăng chờ duyệt -->
<div class="row row-cols-1 row-cols-md-2 g-4">
    @if ($itemsWaits->isEmpty())
    <div class="col-12 px-0">
        <div class="alert alert-info text-center m-0 py-4" style="width: 100vw; font-size: 1.2rem;">
            Không có bài viết chờ duyệt.
        </div>
    </div>
    @else
    @foreach ($itemsWaits as $itemWait)
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $itemWait->title }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Tác giả: {{ $itemWait->author }}</h6>
                <p class="card-text">{{ $itemWait->description }}</p>
                <p><strong>Thể loại:</strong> {{ $itemWait->genre->name ?? 'Không có' }}</p>
                <p><strong>Id:</strong> {{ $itemWait->id }}</p>
                @if($itemWait->cover_img)
                <img src="{{ asset('storage/' . $itemWait->cover_img) }}" alt="Ảnh bìa" class="img-fluid my-2" style="max-height: 150px;">
                @endif
                @if($itemWait->link)
                <a href="{{ asset('storage/' . $itemWait->link) }}" class="btn btn-outline-secondary mt-2" target="_blank">📄 Xem tệp</a>
                @endif
                <h6 class="card-subtitle mb-2 text-muted">Trạng thái:
                    {{ $itemWait->status == 0 ? 'Chờ phê duyệt' : 'Đã duyệt' }}
                </h6>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

<!-- Tiêu đề danh sách bài đã đăng -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách bài đăng</h2>
</div>
<div class="row row-cols-1 row-cols-md-2 g-4">
    @if ($items->isEmpty())
    <div class="col-12 px-0">
        <div class="alert alert-info text-center m-0 py-4" style="width: 100vw; font-size: 1.2rem;">
            Không có bài viết.
        </div>
    </div>
    @else
    @foreach ($items as $item)
    <div class="col">
        <div class="card h-100 shadow-sm d-flex flex-column justify-content-between">
            <div class="card-body">
                <h5 class="card-title">{{ $item->title }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Tác giả: {{ $item->author }}</h6>
                <p class="card-text">{{ $item->description }}</p>
                <p><strong>Thể loại:</strong> {{ $item->genre->name ?? 'Không có' }}</p>
                <p><strong>Id:</strong> {{ $item->id }}</p>
                @if($item->cover_img)
                <img src="{{ asset('storage/' . $item->cover_img) }}" alt="Ảnh bìa" class="img-fluid my-2" style="max-height: 150px;">
                @endif
                @if($item->link)
                <a href="{{ asset('storage/' . $item->link) }}" class="btn btn-outline-secondary mt-2" target="_blank">📄 Xem tệp</a>
                @endif
            </div>

            <!-- Nút gỡ bài -->
            <div class="card-footer bg-transparent border-0 text-end">
                <form action="{{ route('booksUser.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn gỡ bài này không?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑 Gỡ bài</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

<!-- Mở lại modal nếu có lỗi -->
@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var addItemModal = new bootstrap.Modal(document.getElementById('addItemModal'));
        addItemModal.show();
    });
</script>
@endif

@endsection