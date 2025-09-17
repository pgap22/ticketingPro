<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    public $timestamps = false;
    protected $fillable = ['name'];

    /** @return HasMany<User> */
    public function users() { return $this->hasMany(User::class); }
}
