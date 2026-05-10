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
                $statusEnum = RequestStatus::from($status);
                $query = $query->byStatus($statusEnum);
            } catch (\ValueError) {
                // Invalid status, ignore filter
            }
        }

        $requests = $query->latest()->paginate(15);
        $masters = User::where('role', 'master')->get();
        $statuses = RequestStatus::cases();

        $stats = [
            'total' => RepairRequest::count(),
            'new' => RepairRequest::where('status', RequestStatus::New)->count(),
            'assigned' => RepairRequest::where('status', RequestStatus::Assigned)->count(),
            'in_progress' => RepairRequest::where('status', RequestStatus::InProgress)->count(),
            'done' => RepairRequest::where('status', RequestStatus::Done)->count(),
        ];

        return view('dispatcher.index', compact('requests', 'masters', 'statuses', 'stats'));
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
