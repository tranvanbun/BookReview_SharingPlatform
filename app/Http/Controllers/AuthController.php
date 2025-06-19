<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('fe.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'bio'      => 'nullable|string|max:1000',
        ], [
            'name.required'     => 'Vui lòng nhập họ tên.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không hợp lệ.',
            'email.unique'      => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự.',
            'avatar.image'      => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes'      => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif.',
            'avatar.max'        => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $avatarPath = '/storage/' . $path;
        }

        User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'avatar'   => $avatarPath,
            'bio'      => $validatedData['bio'] ?? null,
            'role'     => 'user', // Gán vai trò mặc định
        ]);

        return redirect()->back()->with('success', 'Đăng ký thành công!');
    }

    public function showLoginForm()
    {
        return view('fe.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Phân quyền theo vai trò
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'phone', 'contact', 'address', 'bio']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your message has been sent!');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Sửa lỗi ở đây

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    //vào phần trang cá nhân cuar người đăng bài
    public function showProfile($id)
    {
        $author = User::with('books')->findOrFail($id);
        $followerCount = $author->followers()->count();
        $totalViews = $author->books->sum('views');
        $isFollowing = false;
        if (Auth::check() && Auth::id() !== $author->id) {
            $isFollowing = Auth::user()->follows()->where('followed_user_id', $author->id)->exists();
        }

        return view('main.authProfile', compact('author', 'totalViews', 'isFollowing','followerCount'));
    }
    // theo dõi người dùng
    public function toggleFollow($id, Request $request)
    {
        $author = User::findOrFail($id);
        $user = Auth::user();

        if ($user->id == $author->id) {
            return response()->json(['message' => 'Không thể tự theo dõi mình'], 400);
        }

        $isFollowing = $user->follows()->where('followed_user_id', $author->id)->exists();

        if ($isFollowing) {
            $user->follows()->detach($author->id);
            return response()->json(['status' => 'unfollowed']);
        } else {
            $user->follows()->attach($author->id);
            return response()->json(['status' => 'followed']);
        }
    }

}
