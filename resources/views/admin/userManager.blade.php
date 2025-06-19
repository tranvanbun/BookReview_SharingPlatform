@php use Illuminate\Support\Facades\Auth; @endphp
@extends('admin.admindashboard')

@section('main')
<div class="container mt-4">
    <h3 class="mb-4">Danh sách người dùng</h3>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                </td>

                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="d-flex">
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá người dùng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection