<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::UnderReview => 'Under Review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-warning text-dark',
            self::UnderReview => 'bg-info text-dark',
            self::Approved => 'bg-success',
            self::Rejected => 'bg-danger',
            self::Cancelled => 'bg-secondary',
        };
    }

    /**
     * Which statuses this one is allowed to move to. Once Rejected or
     * Cancelled, an application is terminal — a customer reapplies with a
     * new application rather than reviving an old one. Approved can still
     * be Cancelled (an admin correcting a mistake), per spec section 13.
     *
     * @return array<int, self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::UnderReview, self::Approved, self::Rejected, self::Cancelled],
            self::UnderReview => [self::Approved, self::Rejected, self::Cancelled],
            self::Approved => [self::Cancelled],
            self::Rejected, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }
}
