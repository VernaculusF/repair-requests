<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use App\Models\RequestEvent;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an action on a repair request.
     */
    public function logAction(
        RepairRequest $repairRequest,
        string $action,
        ?RequestStatus $oldStatus = null,
        ?RequestStatus $newStatus = null,
        ?string $comment = null,
    ): RequestEvent {
        return RequestEvent::create([
            'repair_request_id' => $repairRequest->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'old_status' => $oldStatus?->value,
            'new_status' => $newStatus?->value,
            'comment' => $comment,
        ]);
    }

    /**
     * Log a system action (no authenticated user).
     */
    public function logSystemAction(
        RepairRequest $repairRequest,
        string $action,
        ?RequestStatus $oldStatus = null,
        ?RequestStatus $newStatus = null,
        ?string $comment = null,
    ): RequestEvent {
        return RequestEvent::create([
            'repair_request_id' => $repairRequest->id,
            'user_id' => null,
            'action' => $action,
            'old_status' => $oldStatus?->value,
            'new_status' => $newStatus?->value,
            'comment' => $comment,
        ]);
    }
}
