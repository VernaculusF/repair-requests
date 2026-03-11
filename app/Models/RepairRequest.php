<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RequestStatus;
use Database\Factories\RepairRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairRequest extends Model
{
    /** @use HasFactory<RepairRequestFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_name',
        'phone',
        'address',
        'problem_text',
        'status',
        'assigned_to',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RequestStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the master assigned to this request.
     */
    public function assignedMaster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all events (audit log) for this request.
     */
    public function events(): HasMany
    {
        return $this->hasMany(RequestEvent::class)->latest('created_at');
    }

    /**
     * Scope: filter requests by status.
     */
    public function scopeByStatus($query, ?RequestStatus $status)
    {
        if (!$status) {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Scope: filter requests assigned to master.
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }
}
