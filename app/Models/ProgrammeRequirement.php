<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgrammeRequirement extends Model
{
    protected $fillable = ['programme_id', 'requirement', 'sort_order'];
    public function programme(): BelongsTo { return $this->belongsTo(Programme::class); }
}
