<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    protected $table = 'departments';
    public $timestamps = false;
    protected $fillable = ['name'];

    /** @return HasMany<Category> */
    public function categories() { return $this->hasMany(Category::class); }

    /** @return BelongsToMany<User> */
    public function agents() { return $this->belongsToMany(User::class, 'department_user', 'department_id', 'user_id'); }
}
