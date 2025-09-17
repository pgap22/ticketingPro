<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = ['title', 'description', 'status', 'priority', 'user_id', 'agent_id', 'department_id', 'category_id'];

    /** @return BelongsTo<User,Ticket> */
    public function user() { return $this->belongsTo(User::class, 'user_id'); }

    /** @return BelongsTo<User,Ticket> */
    public function agent() { return $this->belongsTo(User::class, 'agent_id'); }

    /** @return BelongsTo<Department,Ticket> */
    public function department() { return $this->belongsTo(Department::class); }

    /** @return BelongsTo<Category,Ticket> */
    public function category() { return $this->belongsTo(Category::class); }

    /** @return HasMany<Comment> */
    public function comments() { return $this->hasMany(Comment::class); }
}
