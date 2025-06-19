<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Book;

class NewBookPosted extends Notification
{
    use Queueable;

    public $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function via($notifiable)
    {
        return ['database']; // lưu vào DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'book_id' => $this->book->id,
            'title' => $this->book->title,
            'author_id' => $this->book->id_user,
            'author_name' => $this->book->user->name,
        ];
    }
}

