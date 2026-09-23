<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'parent_id'];

    /**
     * Only one level of nesting is supported — a sub-department's own
     * `parent()` is always a top-level department, never another
     * sub-department. Enforced in DepartmentForm, not here.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function isSubDepartment(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * "Sales" for a top-level department, "Sales — Retail" for a
     * sub-department — used anywhere a department needs to be identified
     * without also showing its parent as separate context (e.g. the
     * Officer list's Department column).
     */
    public function fullName(): string
    {
        return $this->isSubDepartment() && $this->parent
            ? "{$this->parent->name} — {$this->name}"
            : $this->name;
    }

    public function officers(): HasMany
    {
        return $this->hasMany(Officer::class);
    }
}
