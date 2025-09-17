<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = ['ticket_id', 'user_id', 'body'];

    /** @return BelongsTo<Ticket,Comment> */
    public function ticket() { return $this->belongsTo(Ticket::class); }

    /** @return BelongsTo<User,Comment> */
    public function user() { return $this->belongsTo(User::class); }

    /** @return HasMany<Attachment> */
    public function attachments() { return $this->hasMany(Attachment::class); }
}
