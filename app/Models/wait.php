<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * 
 *
 * @property int $id
 * @property int $id_user
 * @property string $title
 * @property string $author
 * @property string $description
 * @property string $cover_img
 * @property string|null $link
 * @property int $genre_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $genre
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereCoverImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|wait whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class wait extends Model
{
    protected $table = 'waiting';
    protected $fillable = [
        'id_user',
        'title',
        'author',
        'description',
        'cover_img',
        'link',
        'genre_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function genre()
    {
        return $this->belongsTo(Category::class, 'genre_id');
    }
}
