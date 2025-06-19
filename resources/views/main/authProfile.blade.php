@extends('main.main')

@section('content')
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('books.home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="#">Tác giả</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $author->name }}</li>
                </ol>
            </nav>

            <div class="row mt-4">
                <!-- Thông tin tác giả -->
                <div class="col-md-3 text-center">
                    <img src="{{ $author->avatar ? asset($author->avatar) : asset('storage/images/default-avatar.png') }}"
                        alt="{{ $author->name }}" class="img-fluid rounded-circle shadow mb-3"
                        style="width: 150px; height: 150px; object-fit: cover;">

                    <h4 class="fw-bold">{{ $author->name }}</h4>
                    <p><strong>{{ $author->books->count() }}</strong> tác phẩm</p>
                    <p><strong id="follower-count">{{ $followerCount }}</strong> người theo dõi</p>
                    <p><strong>{{ $totalViews }}</strong> lượt xem</p>
                    <!-- Nút theo dõi / hủy theo dõi -->
                    @if (auth()->check() && auth()->id() !== $author->id)
                        <button id="follow-btn"
                            class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} btn-sm rounded-pill px-4 mt-2"
                            data-user-id="{{ $author->id }}">
                            {{ $isFollowing ? 'Hủy theo dõi' : '+ Theo dõi' }}
                        </button>
                    @endif
                </div>

                <!-- Danh sách sách -->
                <div class="col-md-9">
                    <h3 class="mb-4 border-bottom pb-2">Tác phẩm</h3>
                    <div class="row">
                        @forelse ($author->books as $book)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <a href="{{ route('books.show', $book->id) }}">
                                        <img src="{{ asset('storage/' . $book->cover_img) }}"
                                            class="card-img-top rounded-top" alt="{{ $book->title }}"
                                            style="height: 250px; object-fit: cover;">
                                    </a>
                                    <div class="card-body">
                                        <h6 class="card-title fw-semibold mb-2">{{ $book->title }}</h6>
                                        <p class="card-text text-muted small">{{ Str::limit($book->description, 80) }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    Tác giả chưa có tác phẩm nào.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const followBtn = document.getElementById('follow-btn');
            const followerCountEl = document.getElementById('follower-count');

            followBtn?.addEventListener('click', function() {
                const userId = this.dataset.userId;

                fetch(`/author/${userId}/toggle-follow`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'followed') {
                            followBtn.textContent = 'Hủy theo dõi';
                            followBtn.classList.remove('btn-primary');
                            followBtn.classList.add('btn-secondary');

                            // Tăng số lượng người theo dõi
                            const count = parseInt(followerCountEl.textContent);
                            followerCountEl.textContent = count + 1;

                        } else if (data.status === 'unfollowed') {
                            followBtn.textContent = '+ Theo dõi';
                            followBtn.classList.remove('btn-secondary');
                            followBtn.classList.add('btn-primary');

                            // Giảm số lượng người theo dõi
                            const count = parseInt(followerCountEl.textContent);
                            followerCountEl.textContent = count - 1;
                        }
                    })
                    .catch(error => console.error('Lỗi:', error));
            });
        });
    </script>
@endpush

