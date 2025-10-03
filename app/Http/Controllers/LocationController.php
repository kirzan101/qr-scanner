<?php

namespace App\Http\Controllers;

use App\Interfaces\LocationInterface;
use App\Interfaces\PropertyInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function __construct(
        private LocationInterface $location
    ) {}

    /**
     * Display locations
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('System/Locations', [
            'can' => []
        ]);
    }

    /**
     * Store Property
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $result = $this->location->storeLocation($request->all());

        $status = $result['status'] ?? 'error';
        $message = $result['message'] ?? 'An error occurred';

        return redirect()->back()->with($status, $message);
    }

    /**
     * Update property
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {

        $result = $this->location->updateLocation($id, $request->all());

        $status = $result['status'] ?? 'error';

        $message = $result['message'] ?? 'An error occurred';

        return redirect()->back()->with($status, $message);
    }

    /**
     * Delete property
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        $result = $this->location->deleteLocation($id);

        $status = $result['status'] ?? 'error';
        $message = $result['message'] ?? 'An error occurred';

        return redirect()->back()->with($status, $message);
    }
}
