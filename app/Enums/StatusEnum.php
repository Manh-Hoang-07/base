<?php

namespace App\Enums;

enum StatusEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    /**
     * Get all status options for select
     */
    public static function options(): array
    {
        return [
            ['value' => self::INACTIVE->value, 'label' => 'Không hoạt động'],
            ['value' => self::ACTIVE->value, 'label' => 'Hoạt động'],
        ];
    }

    /**
     * Get status label
     */
    public function label(): string
    {
        return match($this) {
            self::INACTIVE => 'Không hoạt động',
            self::ACTIVE => 'Hoạt động',
        };
    }

    /**
     * Get status badge class
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::INACTIVE => 'badge bg-danger',
            self::ACTIVE => 'badge bg-success',
        };
    }

    /**
     * Get status icon
     */
    public function icon(): string
    {
        return match($this) {
            self::INACTIVE => 'fas fa-times-circle',
            self::ACTIVE => 'fas fa-check-circle',
        };
    }

    /**
     * Check if status is active
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if status is inactive
     */
    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    /**
     * Get opposite status
     */
    public function toggle(): self
    {
        return match($this) {
            self::INACTIVE => self::ACTIVE,
            self::ACTIVE => self::INACTIVE,
        };
    }

    /**
     * Create from boolean
     */
    public static function fromBoolean(bool $active): self
    {
        return $active ? self::ACTIVE : self::INACTIVE;
    }

    /**
     * Convert to boolean
     */
    public function toBoolean(): bool
    {
        return $this === self::ACTIVE;
    }
}
