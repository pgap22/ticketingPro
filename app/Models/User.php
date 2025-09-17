<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role_id'];
    protected $hidden = ['password'];

    /** @return BelongsTo<Role,User> */
    public function role() { return $this->belongsTo(Role::class); }

    /** @return HasMany<Ticket> */
    public function tickets() { return $this->hasMany(Ticket::class, 'user_id'); }

    /** @return HasMany<Ticket> */
    public function assignedTickets() { return $this->hasMany(Ticket::class, 'agent_id'); }

    /** @return BelongsToMany<Department> */
    public function departments() { return $this->belongsToMany(Department::class, 'department_user', 'user_id', 'department_id'); }
}
