@extends('admin.admindashboard')

@section('main')

<div class="container py-4">
    <h4 class="mb-4">📩 Thông báo Liên hệ</h4>

@foreach($contacts as $contact)
    <div class="card mb-3 shadow-sm rounded-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold mb-0 text-primary">{{ $contact->name }}</h5>
                <small class="text-muted">{{ $contact->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <p class="text-muted mb-2" style="font-size: 0.9rem;">📧 {{ $contact->email }}</p>
            <div class="bg-light p-3 rounded border mb-3">
                {{ $contact->message }}
            </div>

            <div class="d-flex gap-2">
                @if($contact->status === '0')
                {{-- {{ route('admin.contacts.markAsRead', $contact->id) }} --}}
                    <form action="{{ route('admin.contacts.markAsRead', $contact->id) }}" method="POST">
                        @csrf

                        <button class="btn btn-sm btn-success" type="submit">✔ Đánh dấu đã đọc</button>
                    </form>
                @else
                    <span class="badge bg-secondary">Đã đọc</span>
                @endif
                    {{-- {{ route('admin.contacts.destroy', $contact->id) }} --}}
                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit">🗑 Xóa</button>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection