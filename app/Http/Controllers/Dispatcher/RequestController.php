<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dispatcher;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignMasterRequest;
use App\Models\RepairRequest;
use App\Models\User;
use App\Services\RepairRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct(
        private RepairRequestService $service,
    ) {}

    /**
     * Display a paginated list of all repair requests with optional status filter.
     */
    public function index(): View
    {
        $status = request()->query('status');

        $query = RepairRequest::with('assignedMaster');

        if ($status) {
            try {
                $status = RequestStatus::from($status);
                $query = $query->byStatus($status);
            } catch (\ValueError) {
                // Invalid status, ignore filter
            }
        }

        $requests = $query->paginate(15);
        $masters = User::where('role', 'master')->get();
        $statuses = RequestStatus::cases();

        return view('dispatcher.index', compact('requests', 'masters', 'statuses'));
    }

    /**
     * Assign a master to a repair request.
     */
    public function assign(AssignMasterRequest $request, RepairRequest $repairRequest): RedirectResponse
    {
        try {
            $this->service->assignMaster(
                $repairRequest,
                (int) $request->validated('master_id')
            );

            return back()->with('success', 'Request assigned to master successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a repair request.
     */
    public function cancel(RepairRequest $repairRequest): RedirectResponse
    {
        try {
            $this->service->cancel($repairRequest);

            return back()->with('success', 'Request canceled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
