<?php

declare(strict_types=1);

namespace App\Http\Controllers\Master;

use App\Enums\RequestStatus;
use App\Exceptions\RequestAlreadyTakenException;
use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use App\Services\RepairRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct(
        private RepairRequestService $service,
    ) {}

    /**
     * Display repair requests assigned to the authenticated master.
     */
    public function index(): View
    {
        $masterId = Auth::id();

        $requests = RepairRequest::where('assigned_to', $masterId)
            ->with('events')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('master.index', compact('requests'));
    }

    /**
     * Accept (take) a repair request to work on.
     */
    public function take(RepairRequest $repairRequest): RedirectResponse
    {
        $masterId = Auth::id();

        try {
            $this->service->take($repairRequest, $masterId);

            return back()->with('success', 'Request accepted! You can now start working on it.');
        } catch (RequestAlreadyTakenException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark a repair request as completed.
     */
    public function complete(RepairRequest $repairRequest): RedirectResponse
    {
        $masterId = Auth::id();

        try {
            $this->service->complete($repairRequest, $masterId);

            return back()->with('success', 'Request completed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
