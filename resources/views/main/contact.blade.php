@extends('main.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">📬 Contact With Me</h3>
                    {{-- {{ route('contact.submit') }} --}}
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control" required placeholder="Your message..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
