<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Đã gửi bình luận.');
    }

    public function reply(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'required|exists:comments,id',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Đã gửi trả lời.');
    }
}
