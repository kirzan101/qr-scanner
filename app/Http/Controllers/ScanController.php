<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanFormRequest;
use App\Interfaces\ScanProcessInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScanController extends Controller
{
    public function __construct(
        private ScanProcessInterface $scanProcess
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Scans', [
            'can' => []
        ]);
    }

    /**
     * Handle the scan action.
     */
    public function scan(ScanFormRequest $request)
    {
        $result = $this->scanProcess->processScan($request->validated());

        $status = $result['status'] ?? 'error';
        $message = $result['message'] ?? 'An error occurred';

        return redirect()->back()->with($status, $message);
    }
}
