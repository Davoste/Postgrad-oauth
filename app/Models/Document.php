<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class Document extends Model
{
    protected $fillable = [
        'documentable_type', 'documentable_id', 'uploaded_by', 'category',
        'title', 'path', 'original_name', 'mime_type', 'size',
    ];

    public function documentable(): MorphTo { return $this->morphTo(); }
    public function uploadedBy(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }

    public function getHumanSizeAttribute(): string
    {
        $b = (int) $this->size;
        if ($b < 1024) return $b . ' B';
        if ($b < 1048576) return round($b / 1024, 1) . ' KB';
        return round($b / 1048576, 1) . ' MB';
    }
}
