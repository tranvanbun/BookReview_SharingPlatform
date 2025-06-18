BookReview_SharingPlatform
Họ tên sinh viên: Trần Văn Bun
Mã sinh viên: 23010370
Lớp: K17_CNTT-4
Môn học: Web nâng cao (TH4)

1. Giới thiệu Project
BookReview_SharingPlatform là một nền tảng chia sẻ và đánh giá sách trực tuyến. Ứng dụng cho phép người dùng:

Đăng ký/đăng nhập tài khoản.

Đăng tải sách.

Bình luận, trả lời bình luận.

Yêu thích và lưu sách để đọc sau.

Quản lý sách chờ duyệt (chỉ Admin).

Xem sách theo thể loại, sách nổi bật, sách mới.

Ứng dụng được xây dựng bằng Laravel kết hợp Blade Template, Bootstrap, và sử dụng MySQL để lưu trữ dữ liệu.

2. Các đối tượng chính trong hệ thống
Đối tượng	Mô tả
User	Người dùng hệ thống, có thể đăng ký, đăng nhập, bình luận, yêu thích.
Book	Sách đã được duyệt, hiển thị công khai cho mọi người.
Wait	Sách do người dùng đăng, chờ admin kiểm duyệt.
Comment	Bình luận và trả lời bình luận cho sách.
Category	Thể loại sách.
Contact	Form liên hệ/phản hồi người dùng gửi đến quản trị viên.

3. Sơ đồ lớp (Class Diagram)
mermaid
Sao chép
Chỉnh sửa
classDiagram
    User <|-- Book
    User <|-- Comment
    Book <|-- Comment
    Book <|-- Wait
    Book <|-- Category
    User "1" -- "*" Book : đăng
    User "1" -- "*" Comment : viết
    Book "1" -- "*" Comment : có
    Book "*" -- "1" Category : thuộc
    User "*" -- "*" Book : favorites
    User "*" -- "*" Book : watchLater
4. Sơ đồ hoạt động (Activity Diagram)
4.1. Hiển thị tất cả bình luận của một cuốn sách
mermaid
Sao chép
Chỉnh sửa
flowchart TD
    A[User chọn sách] --> B[Lấy ID sách]
    B --> C[Truy vấn tất cả comment theo book_id]
    C --> D[Hiển thị danh sách comment]
4.2. Tìm kiếm sách được yêu thích nhiều nhất
mermaid
Sao chép
Chỉnh sửa
flowchart TD
    A[User truy cập trang chủ] --> B[Lấy danh sách sách]
    B --> C[Đếm số lượt yêu thích mỗi sách]
    C --> D[Sắp xếp giảm dần]
    D --> E[Hiển thị sách có lượt yêu thích cao nhất]
5. Chức năng chính (Ảnh chụp màn hình)
(Lưu ý: bạn nên chèn ảnh minh họa từ giao diện thật vào file PDF hoặc Word nộp)

Giao diện đăng ký / đăng nhập.

Đăng sách mới.

Trang duyệt sách (Admin).

Giao diện bình luận / trả lời bình luận.

Danh sách sách yêu thích / xem sau.

Trang chủ: hiển thị sách nổi bật, sách mới đăng.

6. Code minh họa các phần chính
6.1. Model Book
php
Sao chép
Chỉnh sửa
// File: app/Models/Book.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'id_user',
        'title',
        'author',
        'description',
        'cover_img',
        'link',
        'genre_id',
        'views',
        'favorites'
    ];

    public function genre()
    {
        return $this->belongsTo(Category::class, 'genre_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
6.2. Model Comment
php
Sao chép
Chỉnh sửa
// File: app/Models/Comment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'book_id', 'parent_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
7. Công nghệ sử dụng
Công nghệ	Mô tả
Laravel 10	Backend PHP Framework chính.
Blade	Template engine dùng hiển thị.
Bootstrap 5	Giao diện responsive.
MySQL	Cơ sở dữ liệu quan hệ.
jQuery/AJAX	Tải động bình luận, yêu thích.
