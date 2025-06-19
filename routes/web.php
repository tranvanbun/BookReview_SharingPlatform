<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showloginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| Giao diện người dùng (User Interface)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [BookController::class, 'featuredBooks'])->name('main');
    Route::get('/da', [BookController::class, 'featuredBooks'])->name('books.home');

    // Thông báo
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Hồ sơ và bài viết
    Route::get('/profile', fn() => view('main.profile'))->name('profile');
    Route::get('/dashboard/profile', fn() => view('main.profile'))->name('profile');
    Route::put('/user/{id}', [AuthController::class, 'update'])->name('user.update');
    Route::get('/dashboard/mypost', fn() => view('main.mypost'))->name('myPost');
    Route::get('/dashboard/contact', fn() => view('main.contact'))->name('contactMe');
    Route::post('/contact', [AuthController::class, 'submit'])->name('contact.submit');
});

/*
|--------------------------------------------------------------------------
| Sách (Books)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/readBook/{id}', [BookController::class, 'readBook'])->name('books.show');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('booksUser.destroy');

    // Hành động yêu thích và xem sau
    Route::post('/books/{book}/favorite', [BookController::class, 'toggleFavorite'])->name('books.favorite');
    Route::post('/books/{book}/watchlater', [BookController::class, 'toggleWatchLater'])->name('books.watchlater');

    // Sách cùng thể loại
    Route::get('/sameGenreBooks/{id}', [BookController::class, 'sameGenreBooks'])->name('sameGenreBooks');
});

/*
|--------------------------------------------------------------------------
| Bình luận (Comments)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/reply', [CommentController::class, 'reply'])->name('comments.reply');
});

/*
|--------------------------------------------------------------------------
| Tác giả & Theo dõi
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/authors/{id}', [AuthController::class, 'showProfile'])->name('authors.show');
    Route::post('/author/{id}/toggle-follow', [AuthController::class, 'toggleFollow'])->name('authors.toggleFollow');
});

/*
|--------------------------------------------------------------------------
| Quản trị viên (Admin Routes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin:admin'])->group(function () {
    // Trang chính quản trị
    Route::get('/admin/dashboard', [AdminController::class, 'bookIndex'])->name('admin.dashboard');
    Route::get('/admin/userManager', [AdminController::class, 'index'])->name('users.index');

    // Quản lý người dùng
    Route::delete('/admin/userManager/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::patch('/admin/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.updateRole');

    // Quản lý sách
    Route::put('/admin/approve/{id}', [AdminController::class, 'approve'])->name('books.approve');
    Route::delete('/admin/dashboard/{id}', [AdminController::class, 'destroyBook'])->name('books.destroy');

    // Quản lý liên hệ / thông báo
    Route::get('/admin/notification', [AdminController::class, 'indexContact'])->name('admin.notification');
    Route::post('/admin/notification/read/{id}', [AdminController::class, 'markAsRead'])->name('admin.contacts.markAsRead');
    Route::delete('/admin/notification/delete/{id}', [AdminController::class, 'deleteNotification'])->name('admin.contacts.destroy');

    // Quản lý thể loại
    Route::get('/admin/categori', [AdminController::class, 'showCategories'])->name('admin.categori');
    Route::post('/admin/addcategori', [AdminController::class, 'store'])->name('categories.store');
});
