<?php

namespace App\Models;

use App\Enums\IdeaState;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Idea extends Model
{
    use HasFactory;

    protected $casts = [
        'state' => IdeaState::class,
    ];

    protected $fillable = [
        'title',
        'description',
        'state',
        'user_id',
    ];

    protected function color(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->state->color(),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
