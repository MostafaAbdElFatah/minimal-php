<?php

namespace App\Enums;

enum IdeaState: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case COMPLETE = 'complete';
    case INCOMPLETE = 'incomplete';
    case DRAFT = 'draft';
    case PAUSED = 'paused';
    case CANCELLED = 'cancelled';
    case ARCHIVED = 'archived';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::ACTIVE => 'green',
            self::COMPLETE => 'blue',
            self::INCOMPLETE => 'orange',
            self::DRAFT => 'gray',
            self::PAUSED => 'amber',
            self::CANCELLED => 'red',
            self::ARCHIVED => 'purple',
        };
    }
}