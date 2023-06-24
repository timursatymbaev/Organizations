<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ministry extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'ministry_name'
    ];

    public function committee(): HasMany
    {
        return $this->hasMany(Committee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
