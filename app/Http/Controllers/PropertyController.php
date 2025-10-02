<?php

namespace App\Http\Controllers;

use App\Interfaces\PropertyInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    public function __construct(
        private PropertyInterface $property
    ) {}

    /**
     * Display properties
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('System/Properties', [
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
        $result = $this->property->storeProperty($request->all());

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

        $result = $this->property->updateProperty($id, $request->all());

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
        $result = $this->property->deleteProperty($id);

        $status = $result['status'] ?? 'error';
        $message = $result['message'] ?? 'An error occurred';

        return redirect()->back()->with($status, $message);
    }
}
