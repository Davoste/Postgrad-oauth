<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class School extends Model
{
    protected $fillable = ['name', 'code', 'dean_id'];
    public function dean(): BelongsTo { return $this->belongsTo(User::class, 'dean_id'); }
    public function departments(): HasMany { return $this->hasMany(Department::class); }
}
