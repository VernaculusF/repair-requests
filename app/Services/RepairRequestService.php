<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RequestStatus;
use App\Exceptions\RequestAlreadyTakenException;
use App\Http\Requests\StoreRepairRequestRequest;
use App\Models\RepairRequest;

class RepairRequestService
{
    public function __construct(
        private AuditService $auditService,
    ) {}

    /**
     * Create a new repair request.
     */
    public function create(StoreRepairRequestRequest $request): RepairRequest
    {
        $repairRequest = RepairRequest::create([
            'client_name' => $request->validated('client_name'),
            'phone' => $request->validated('phone'),
            'address' => $request->validated('address'),
            'problem_text' => $request->validated('problem_text'),
            'status' => RequestStatus::New,
        ]);

        $this->auditService->logSystemAction(
            $repairRequest,
            'created',
            oldStatus: null,
            newStatus: RequestStatus::New,
            comment: 'Request created via public form'
        );

        return $repairRequest;
    }

    /**
     * Assign a master to a repair request.
     *
     * @throws \Exception
     */
    public function assignMaster(RepairRequest $repairRequest, int $masterId): void
    {
        if ($repairRequest->status !== RequestStatus::New) {
            throw new \Exception('Can only assign requests with status "new".');
        }

        $oldStatus = $repairRequest->status;

        $repairRequest->update([
            'assigned_to' => $masterId,
            'status' => RequestStatus::Assigned,
        ]);

        $this->auditService->logAction(
            $repairRequest,
            'assigned',
            oldStatus: $oldStatus,
            newStatus: RequestStatus::Assigned,
        );
    }

    /**
     * Take (accept) a repair request by a master.
     * Uses atomic UPDATE to prevent race condition.
     *
     * @throws RequestAlreadyTakenException
     */
    public function take(RepairRequest $repairRequest, int $masterId): void
    {
        $affected = RepairRequest::where('id', $repairRequest->id)
            ->where('status', RequestStatus::Assigned->value)
            ->where('assigned_to', $masterId)
            ->update(['status' => RequestStatus::InProgress->value]);

        if ($affected === 0) {
            throw new RequestAlreadyTakenException('This request is already being worked on.');
        }

        // Refresh the model to get updated timestamp
        $repairRequest->refresh();

        $this->auditService->logAction(
            $repairRequest,
            'taken',
            oldStatus: RequestStatus::Assigned,
            newStatus: RequestStatus::InProgress,
        );
    }

    /**
     * Complete a repair request.
     *
     * @throws \Exception
     */
    public function complete(RepairRequest $repairRequest, int $masterId): void
    {
        if ($repairRequest->assigned_to !== $masterId) {
            throw new \Exception('You are not authorized to complete this request.');
        }

        if ($repairRequest->status !== RequestStatus::InProgress) {
            throw new \Exception('Only in-progress requests can be completed.');
        }

        $oldStatus = $repairRequest->status;

        $repairRequest->update([
            'status' => RequestStatus::Done,
        ]);

        $this->auditService->logAction(
            $repairRequest,
            'completed',
            oldStatus: $oldStatus,
            newStatus: RequestStatus::Done,
        );
    }

    /**
     * Cancel a repair request.
     *
     * @throws \Exception
     */
    public function cancel(RepairRequest $repairRequest): void
    {
        if ($repairRequest->status === RequestStatus::Done || $repairRequest->status === RequestStatus::Canceled) {
            throw new \Exception('Cannot cancel a request that is done or already canceled.');
        }

        $oldStatus = $repairRequest->status;

        $repairRequest->update([
            'status' => RequestStatus::Canceled,
        ]);

        $this->auditService->logAction(
            $repairRequest,
            'canceled',
            oldStatus: $oldStatus,
            newStatus: RequestStatus::Canceled,
        );
    }
}

