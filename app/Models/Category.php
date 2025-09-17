<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $fillable = ['name', 'department_id'];

    /** @return BelongsTo<Department,Category> */
    public function department() { return $this->belongsTo(Department::class); }

    /** @return HasMany<Ticket> */
    public function tickets() { return $this->hasMany(Ticket::class); }
}
