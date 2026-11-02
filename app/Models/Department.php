<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Department extends Model
{
    protected $fillable = ['school_id', 'name', 'code', 'hod_id'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function hod(): BelongsTo { return $this->belongsTo(User::class, 'hod_id'); }
    public function programmes(): HasMany { return $this->hasMany(Programme::class); }
}
