<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'repair_request_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'comment',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_status' => RequestStatus::class,
            'new_status' => RequestStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the repair request this event belongs to.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class, 'repair_request_id');
    }

    /**
     * Get the user who triggered this event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'System',
        ]);
    }
}
