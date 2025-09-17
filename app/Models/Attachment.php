<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $table = 'attachments';
    public $timestamps = false;
    protected $fillable = ['comment_id','file_path','original_name','mime_type','file_size_kb'];

    /** @return BelongsTo<Comment,Attachment> */
    public function comment() { return $this->belongsTo(Comment::class); }
}
