<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepairRequestRequest;
use App\Services\RepairRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RepairRequestController extends Controller
{
    public function __construct(
        private RepairRequestService $service,
    ) {}

    /**
     * Show the form to create a new repair request.
     */
    public function create(): View
    {
        return view('requests.create');
    }

    /**
     * Store the repair request.
     */
    public function store(StoreRepairRequestRequest $request): RedirectResponse
    {
        $repairRequest = $this->service->create($request);

        return redirect('/requests/success')
            ->with('request_id', $repairRequest->id)
            ->with('success', 'Your repair request has been submitted successfully!');
    }

    /**
     * Show success page after request creation.
     */
    public function success(): View
    {
        return view('requests.success');
    }
}
