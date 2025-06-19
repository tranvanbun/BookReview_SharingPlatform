@extends('main.main')

@section('content')
    <!-- ===== FEATURED BOOKS ===== -->
    <section class="featured-books py-5 bg-white">
        <div class="container">
            <h2 class="fw-bold mb-4 text-primary">🌟 SÁCH NỔI BẬT</h2>

            <div class="row g-4">
                @forelse ($featuredBooks as $book)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card book-card border-0 rounded-4 shadow-sm h-100">
                            <a href="{{ route('books.show', $book->id) }}">
                                <img src="{{ asset('storage/' . $book->cover_img) }}"
                                    class="card-img-top book-cover rounded-top-4" alt="{{ $book->title }}">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-semibold text-dark mb-1">{{ $book->title }}</h5>
                                <p class="text-secondary mb-1 small">{{ $book->author }}</p>
                                <div class="text-muted mb-3 small">
                                    <i class="fas fa-heart text-danger me-1"></i>{{ $book->favorites }} yêu thích
                                </div>
                                <a href="{{ route('books.show', $book->id) }}"
                                    class="btn btn-sm btn-outline-secondary mt-auto rounded-pill mt-2">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Chưa có sách nổi bật nào.</p>
                @endforelse
            </div>

            @if ($featuredBooks->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $featuredBooks->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <!-- ===== NEW BOOKS ===== -->
    <section class="new-books py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold mb-4 text-primary">📚 SÁCH MỚI ĐĂNG</h2>

            <div class="row g-4">
                @forelse ($newestBooks as $book)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card book-card border-0 rounded-4 shadow-sm h-100">
                            <a href="{{ route('books.show', $book->id) }}">
                                <img src="{{ asset('storage/' . $book->cover_img) }}"
                                    class="card-img-top book-cover rounded-top-4" alt="{{ $book->title }}">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-semibold text-dark mb-1">{{ $book->title }}</h5>
                                <p class="text-secondary mb-3 small">{{ $book->author }}</p>
                                <a href="{{ route('books.show', $book->id) }}"
                                    class="btn btn-sm btn-outline-secondary mt-auto rounded-pill mt-2">
                                    Xem chi tiết
                                </a>

                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Chưa có sách mới nào.</p>
                @endforelse
            </div>

            @if ($newestBooks->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $newestBooks->appends(['featured_page' => request()->get('featured_page')])->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <!-- ===== MOST VIEWED BOOKS ===== -->
    <section class="most-viewed-books py-5 bg-white">
        <div class="container">
            <h2 class="fw-bold mb-4 text-primary">🔥 SÁCH ĐƯỢC XEM NHIỀU</h2>

            <div class="row g-4">
                @forelse ($mostViewedBooks as $book)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card book-card border-0 rounded-4 shadow-sm h-100">
                            <a href="{{ route('books.show', $book->id) }}">
                                <img src="{{ asset('storage/' . $book->cover_img) }}"
                                    class="card-img-top book-cover rounded-top-4" alt="{{ $book->title }}">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-semibold text-dark mb-1">{{ $book->title }}</h5>
                                <p class="text-secondary mb-1 small">{{ $book->author }}</p>
                                <small class="text-muted"><i class="fas fa-eye"></i> {{ $book->views }} lượt xem</small>
                                <a href="{{ route('books.show', $book->id) }}"
                                    class="btn btn-sm btn-outline-secondary mt-auto rounded-pill mt-2">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Chưa có sách nào được xem nhiều.</p>
                @endforelse
            </div>

            @if ($mostViewedBooks->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $mostViewedBooks->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .pagination-info,
        .pagination .d-flex.justify-content-between {
            display: none !important;
        }

        .pagination .page-link {
            border-radius: 8px;
            padding: 6px 12px;
            color: #007bff;
            border: 1px solid #dee2e6;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa;
            color: #0056b3;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .book-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .book-card .card-title {
            font-size: 1.05rem;
        }

        .book-card .btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
        }

        h2 {
            font-size: 1.6rem;
        }
    </style>
@endpush
