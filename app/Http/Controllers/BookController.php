<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\wait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Notifications\NewBookPosted;

class BookController extends Controller
{
    //hiển thị ở phần bài đăng,và phần bài đang được chờ phê duyệt
    public function index()
    {
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy các bài viết của người dùng đó
        $items = Book::where('id_user', $userId)->get();
        $itemsWaits = wait::where('id_user', $userId)->get();
        $categories = Category::all();

        return view('main.mypost', compact('items', 'itemsWaits', 'categories'));
    }

    //đẩy dữ liệu lên bảng chờ duyệt

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string',
    //         'author' => 'required|string',
    //         'description' => 'required|string',
    //         'cover_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'link' => 'nullable|file|mimes:pdf,doc,docx|max:102400',
    //         'genre' => 'nullable|string',
    //     ]);

    //     // Kiểm tra trùng lặp trong bảng waiting
    //     $existsInWaiting = Wait::where('title', $validated['title'])
    //         ->where('author', $validated['author'])
    //         ->exists();

    //     // Kiểm tra trùng lặp trong bảng books
    //     $existsInBooks = Book::where('title', $validated['title'])
    //         ->where('author', $validated['author'])
    //         ->exists();

    //     if ($existsInWaiting || $existsInBooks) {
    //         return redirect()->back()->withErrors([
    //             'duplicate' => 'Tác phẩm với tên và tác giả này đã tồn tại trong hệ thống hoặc đang chờ duyệt.'
    //         ])->withInput();
    //     }

    //     // Xử lý ảnh bìa
    //     if ($request->hasFile('cover_img')) {
    //         $coverPath = $request->file('cover_img')->store('covers', 'public');
    //         $validated['cover_img'] = $coverPath;
    //     }

    //     // Xử lý file đính kèm
    //     if ($request->hasFile('link')) {
    //         $filePath = $request->file('link')->store('attachments', 'public');
    //         $validated['link'] = $filePath;
    //     }

    //     $validated['id_user'] = Auth::id();

    //     // Tạo bản ghi
    //     Wait::create($validated);

    //     return redirect()->back()->with('success', 'Đã thêm đối tượng thành công!');
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required|string',
            'cover_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|file|mimes:pdf,doc,docx|max:102400',
            'genre_id' => 'required|exists:categories,id', // chỉ một thể loại, đã chỉnh từ 'genre'
        ]);

        // Kiểm tra trùng lặp trong bảng waits
        $existsInWaiting = Wait::where('title', $validated['title'])
            ->where('author', $validated['author'])
            ->exists();

        // Kiểm tra trùng lặp trong bảng books
        $existsInBooks = Book::where('title', $validated['title'])
            ->where('author', $validated['author'])
            ->exists();

        if ($existsInWaiting || $existsInBooks) {
            return redirect()->back()->withErrors([
                'duplicate' => 'Tác phẩm với tên và tác giả này đã tồn tại trong hệ thống hoặc đang chờ duyệt.'
            ])->withInput();
        }

        // Xử lý ảnh bìa
        if ($request->hasFile('cover_img')) {
            $coverPath = $request->file('cover_img')->store('covers', 'public');
            $validated['cover_img'] = $coverPath;
        }

        // Xử lý file đính kèm
        if ($request->hasFile('link')) {
            $filePath = $request->file('link')->store('attachments', 'public');
            $validated['link'] = $filePath;
        }

        // Gán ID người dùng hiện tại
        $validated['id_user'] = Auth::id();

        // Tạo bản ghi chờ duyệt
        Wait::create($validated);

        return redirect()->back()->with('success', 'Đã thêm đối tượng thành công!');
    }


    //người dùng xóa bài viết

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Kiểm tra quyền người dùng
        if ($book->id_user != Auth::id()) {
            abort(403, 'Bạn không có quyền xóa bài này.');
        }

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

    //hiển thị sách ở trang chủ
    public function featuredBooks(Request $request)
    {
        // Sách được yêu thích
        $books = Book::orderByDesc('favorites')->take(8)->get();
        $currentPage = $request->get('page', 1);
        $perPage = 4;
        $currentItems = $books->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $featuredBooks = new LengthAwarePaginator(
            $currentItems,
            $books->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Sách mới nhất
        $newBooks = Book::orderByDesc('created_at')->take(8)->get();
        $currentNewestPage = $request->get('newest_page', 1);
        $newestItems = $newBooks->slice(($currentNewestPage - 1) * $perPage, $perPage)->values();

        $newestBooks = new LengthAwarePaginator(
            $newestItems,
            $newBooks->count(),
            $perPage,
            $currentNewestPage,
            ['path' => $request->url(), 'pageName' => 'newest_page']
        );

        // Sách được xem nhiều
        $mostViewed = Book::orderByDesc('views')->take(8)->get();
        $currentViewedPage = $request->get('viewed_page', 1);
        $viewedItems = $mostViewed->slice(($currentViewedPage - 1) * $perPage, $perPage)->values();

        $mostViewedBooks = new LengthAwarePaginator(
            $viewedItems,
            $mostViewed->count(),
            $perPage,
            $currentViewedPage,
            ['path' => $request->url(), 'pageName' => 'viewed_page']
        );

        return view('main.home', compact('featuredBooks', 'newestBooks', 'mostViewedBooks'));
    }

    //Xem bài viết đã được đăng + hiển thị sách cùng thể loại
    public function readBook($id)
    {
        $book = Book::with(['genre', 'user'])->findOrFail($id);
        // cập nhật lượt xem sách
        $viewKey = 'viewed_book_' . $book->id;
        if (!session()->has($viewKey)) {
            $book->increment('views');
            session()->put($viewKey, true);
        }
        // Lấy sách cùng thể loại
        $sameGenreBooks = Book::where('genre_id', $book->genre_id)
            ->where('id', '!=', $id)
            ->latest()
            ->take(5)
            ->get();

        return view('main.bookShow', compact('book', 'sameGenreBooks'));
    }

    //thêm sách vào phần yêu thích
    public function toggleFavorite(Book $book)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Người dùng không tồn tại'], 404);
        }

        if ($user->favorites()->where('book_id', $book->id)->exists()) {
            // Hủy yêu thích → detach và giảm lượt
            $user->favorites()->detach($book->id);
            if ($book->favorites > 0) {
                $book->decrement('favorites');
            }

            return response()->json([
                'favorited' => false,
                'favorites_count' => $book->fresh()->favorites,
            ]);
        } else {
            // Thêm yêu thích → attach và tăng lượt
            $user->favorites()->attach($book->id);
            $book->increment('favorites');

            return response()->json([
                'favorited' => true,
                'favorites_count' => $book->fresh()->favorites,
            ]);
        }
    }
    //thêm sách vào phần xem sau
    public function toggleWatchLater(Book $book)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user->watchLaterBooks->contains($book->id)) {
            $user->watchLaterBooks()->detach($book->id);
            return response()->json(['watch_later' => false]);
        } else {
            $user->watchLaterBooks()->attach($book->id);
            return response()->json(['watch_later' => true]);
        }
    }
}
