        @php use Illuminate\Support\Facades\Auth; @endphp
        @extends('main.main')

        @section('content')
            <section class="book-details py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <!-- Phần chính: ảnh bìa, bình luận, mô tả -->
                        <div class="col-md-9">
                            <div class="row">
                                <!-- Ảnh bìa và bình luận -->
                                <div
                                    class="col-md-4 text-center mb-4 mb-md-0 position-relative d-flex flex-column align-items-center">
                                    <img src="{{ asset('storage/' . $book->cover_img) }}" alt="{{ $book->title }}"
                                        class="img-fluid rounded shadow mb-3" style="max-height: 400px; object-fit: contain;">
                                    <div class="w-100">
                                        <!-- Phần bình luận -->
                                        <div class="card text-start shadow-sm mt-4">
                                            <div class="card-body p-3">
                                                <h5 class="fw-bold mb-3">Bình luận</h5>

                                                @auth
                                                    <form action="{{ route('comments.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                        <textarea name="content" rows="3" class="form-control mb-2" placeholder="Nhập bình luận của bạn..."></textarea>
                                                        <button type="submit" class="btn btn-sm btn-primary">Gửi</button>
                                                    </form>
                                                    <hr>
                                                @else
                                                    <p class="text-muted">Vui lòng <a href="{{ route('login') }}">đăng nhập</a>
                                                        để bình luận.</p>
                                                @endauth

                                                @foreach ($book->comments()->whereNull('parent_id')->latest()->get() as $comment)
                                                    <div class="mb-3 border-bottom pb-2">
                                                        <div class="fw-semibold">{{ $comment->user->name }}</div>
                                                        <div>{{ $comment->content }}</div>
                                                        <div class="small text-muted">
                                                            {{ $comment->created_at->diffForHumans() }}</div>

                                                        @auth
                                                            <a href="javascript:void(0);" class="text-primary small"
                                                                onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('d-none')">
                                                                ↩ Trả lời
                                                            </a>

                                                            <form action="{{ route('comments.reply') }}" method="POST"
                                                                class="mt-2 d-none" id="reply-form-{{ $comment->id }}">
                                                                @csrf
                                                                <input type="hidden" name="book_id"
                                                                    value="{{ $book->id }}">
                                                                <input type="hidden" name="parent_id"
                                                                    value="{{ $comment->id }}">
                                                                <textarea name="content" rows="2" class="form-control mb-2" placeholder="Nhập câu trả lời..."></textarea>
                                                                <button type="submit" class="btn btn-sm btn-secondary">Trả
                                                                    lời</button>
                                                            </form>
                                                        @endauth

                                                        <!-- Trả lời con -->
                                                        @foreach ($comment->replies as $reply)
                                                            <div class="mt-2 ms-4 border-start ps-3">
                                                                <div class="fw-semibold">{{ $reply->user->name }}</div>
                                                                <div>{{ $reply->content }}</div>
                                                                <div class="small text-muted">
                                                                    {{ $reply->created_at->diffForHumans() }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Thông tin sách -->
                                <div class="col-md-8">
                                    <h2 class="fw-bold text-primary">{{ $book->title }}</h2>
                                    <p class="text-muted mb-1">Tác giả: <strong>{{ $book->author }}</strong></p>
                                    <a href="{{ route("authors.show",$book->user->id ) }}" class="d-flex align-items-center mb-2 text-decoration-none text-dark">
                                        <img src="{{ $book->user->avatar ? asset($book->user->avatar) : asset('storage/images/default-avatar.png') }}"
                                            alt="{{ $book->user->name ?? 'Người dùng ẩn danh' }}"
                                        class="rounded-circle me-2 shadow-sm"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <div class="text-muted small">Người đăng</div>
                                            <strong>{{ $book->user->name }}</strong>
                                        </div>
                                    </a>
                                    <p class="text-muted mb-3">Thể loại: <span
                                            class="badge bg-secondary">{{ $book->genre->name }}</span></p>

                                    <div class="mb-3" style="max-width: 100%;">
                                        <h5 class="fw-semibold">Mô tả</h5>
                                        <p>{{ $book->description ?? 'Không có mô tả cho sách này.' }}</p>
                                    </div>

                                    <!-- Nút yêu thích và xem sau -->
                                    <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
                                        @auth
                                            @php
                                                $isFavorited = Auth::user()->favorites->contains($book->id);
                                                $isWatchLater = Auth::user()->watchlaters->contains($book->id);
                                            @endphp

                                            <!-- Nút Yêu thích -->
                                            <button class="favorite-btn border-0 bg-transparent"
                                                data-book-id="{{ $book->id }}"
                                                title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                                <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart text-danger fs-4"></i>
                                            </button>

                                            <!-- Nút Xem sau -->
                                            <button class="position-relative border-0 bg-transparent watchlater-btn"
                                                data-book-id="{{ $book->id }}"
                                                title="{{ $isWatchLater ? 'Bỏ khỏi xem sau' : 'Thêm vào xem sau' }}">
                                                <div class="icon-box">
                                                    <i class="fas fa-box-open"></i>
                                                    @if ($isWatchLater)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                            1
                                                        </span>
                                                    @endif
                                                </div>
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-warning">⭐ Thêm vào yêu thích</a>
                                            <a href="{{ route('login') }}" class="btn btn-info">📦 Xem sau</a>
                                        @endauth
                                    </div>

                                    @if ($book->link)
                                        <a href="{{ asset('storage/' . $book->link) }}"
                                            class="btn btn-outline-secondary mt-2" target="_blank">📄 Xem tệp</a>
                                    @endif
                                    <div>
                                        <a href="{{ route('books.home') }}" class="btn btn-outline-primary mt-2">← Quay
                                            lại</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar: Sách cùng thể loại -->
                        <div class="col-md-3">
                            <h5 class="fw-bold text-dark mb-3">📚 Sách cùng thể loại</h5>
                            @forelse ($sameGenreBooks as $related)
                                <div class="mb-3 p-2 border rounded bg-white shadow-sm">
                                    <a href="{{ route('books.show', $related->id) }}"
                                        class="text-decoration-none d-block text-dark">
                                        <img src="{{ asset('storage/' . $related->cover_img) }}"
                                            class="img-fluid rounded mb-1" style="max-height: 120px; object-fit: cover;">
                                        <div class="fw-semibold text-truncate">{{ $related->title }}</div>
                                    </a>
                                </div>
                            @empty
                                <p class="text-muted">Không có sách cùng thể loại.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        @endsection

        @push('styles')
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
            <style>
                .watchlater-btn .icon-box {
                    width: 40px;
                    height: 40px;
                    background-color: #007bff;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 10px;
                    position: relative;
                }

                .watchlater-btn i {
                    font-size: 18px;
                }

                .watchlater-btn .badge {
                    font-size: 10px;
                    padding: 4px 6px;
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const favBtn = document.querySelector('.favorite-btn');
                    if (favBtn) {
                        favBtn.addEventListener('click', function() {
                            const bookId = this.dataset.bookId;
                            const icon = this.querySelector('i');

                            fetch(`/books/${bookId}/favorite`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    icon.classList.toggle('fas', data.favorited);
                                    icon.classList.toggle('far', !data.favorited);
                                })
                                .catch(err => console.error('Lỗi:', err));
                        });
                    }

                    const watchBtn = document.querySelector('.watchlater-btn');
                    if (watchBtn) {
                        watchBtn.addEventListener('click', function() {
                            const bookId = this.dataset.bookId;
                            fetch(`/books/${bookId}/watchlater`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    const iconBox = watchBtn.querySelector('.icon-box');

                                    if (data.watchlater) {
                                        if (!iconBox.querySelector('.badge')) {
                                            const badge = document.createElement('span');
                                            badge.className =
                                                'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                                            badge.innerText = '1';
                                            iconBox.appendChild(badge);
                                        }
                                        watchBtn.setAttribute('title', 'Bỏ khỏi xem sau');
                                    } else {
                                        const badge = iconBox.querySelector('.badge');
                                        if (badge) badge.remove();
                                        watchBtn.setAttribute('title', 'Thêm vào xem sau');
                                    }
                                })
                                .catch(err => console.error('Lỗi:', err));
                        });
                    }
                });
                document.querySelectorAll('.reply-toggle').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const formId = 'reply-form-' + this.dataset.commentId;
                        const form = document.getElementById(formId);
                        if (form.classList.contains('d-none')) {
                            form.classList.remove('d-none');
                        } else {
                            form.classList.add('d-none');
                        }
                    });
                });
            </script>
        @endpush
