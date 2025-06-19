<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\wait;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewBookPosted;

class AdminController extends Controller
{
    //hiển thị danh sách các bài viết chờ phê duyệt
    public function bookIndex()
    {
        // $bookItemsWaits = wait::with('user')->where('status', 0)->get();
        $bookItemsWaits = wait::all();
        return view('admin.bookManager', compact('bookItemsWaits'));
    }

    //hiển thị các user đã đăng kí
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('id', '!=', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->get();

        return view('admin.userManager', compact('users', 'search'));
    }

    //cập nhật vai trò tài khoản
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    //lấy thông tin người dùng
    public function showUser($id)
    {
        $user = User::findOrFail($id);
    }

    //xóa user
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Không cho xoá chính mình
        if (auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Bạn không thể xoá chính mình.');
        }

        // Không cho xoá admin nếu cần
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Không thể xoá tài khoản admin.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Xoá người dùng thành công.');
    }

    //phê duyệt các bài viết
    // public function approve($id)
    // {
    //     // Lấy bài viết từ bảng waiting
    //     $waiting = wait::findOrFail($id);

    //     // Chuyển thông tin sang bảng books
    //     $book = new Book();
    //     $book->title = $waiting->title;
    //     $book->author = $waiting->author;
    //     $book->description = $waiting->description;
    //     $book->genre = $waiting->genre;
    //     $book->cover_img = $waiting->cover_img;
    //     $book->link = $waiting->link;
    //     $book->id_user = $waiting->id_user;
    //     $book->save();

    //     // Xoá bản ghi khỏi bảng waiting
    //     $waiting->delete();

    //     return redirect()->back()->with('success', 'Phê duyệt bài viết thành công.');
    // }
    public function approve($id)
    {
        // Lấy bài viết từ bảng waits
        $waiting = Wait::findOrFail($id);

        // Chuyển thông tin sang bảng books
        $book = new Book();
        $book->title = $waiting->title;
        $book->author = $waiting->author;
        $book->description = $waiting->description;
        $book->genre_id = $waiting->genre_id; // Sửa tên field
        $book->cover_img = $waiting->cover_img;
        $book->link = $waiting->link ?: null;;
        $book->id_user = $waiting->id_user;
        $book->save();

        // Xoá bản ghi khỏi bảng waits
        $waiting->delete();
        // Gửi thông báo đến người theo dõi
        $followers = Auth::user()->followers;
        foreach ($followers as $follower) {
            $follower->notify(new NewBookPosted($book));
        }

        return redirect()->back()->with('success', 'Phê duyệt bài viết thành công.');
    }


    //thực hiện xóa xbài viết chờ phê duyệt
    public function destroyBook($id)
    {
        $book = wait::findOrFail($id);
        // Xóa ảnh bìa nếu tồn tại
        if ($book->cover_img && Storage::disk('public')->exists($book->cover_img)) {
            Storage::disk('public')->delete($book->cover_img);
        }

        // Xóa file đính kèm nếu tồn tại
        if ($book->link && Storage::disk('public')->exists($book->link)) {
            Storage::disk('public')->delete($book->link);
        }

        // Xóa bài
        $book->delete();

        return redirect()->back()->with('success', 'Đã gỡ bài thành công!');
    }

    //xem bài viết phản hồi
    public function indexContact()
    {
        $contacts = \App\Models\Contact::latest()->paginate(10);
        return view('admin.notification', compact('contacts'));
    }

    //đánh giấu đã đọc thư báo cáo
    public function markAsRead($id)
    {
        $contact = Contact::findOrfail($id);
        if ($contact->status === '0') {
            $contact->status = '1';
            $contact->save();
        }
        return redirect()->back()->with('success', 'Đã đánh dấu là đã đọc.');
    }

    //xóa notification
    public function deleteNotification($id)
    {
        $contact = Contact::findOrfail($id);
        $contact->delete();
        return redirect()->back()->with('success', 'Xoá thư thành công.');
    }

    //thực hiện thêm thông tin vào phần hiển thị thể loại sách
    public function showCategories()
    {
        $categories = Category::all();
        return view('admin.categori', compact('categories'));
    }

    //thêm thể loại sách vào bảng
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Thêm thể loại thành công!');
    }
}
