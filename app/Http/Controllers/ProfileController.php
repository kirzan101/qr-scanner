<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ProfileFormRequest;
use App\Interfaces\Fetches\DepartmentFetchInterface;
use App\Interfaces\ProfileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileInterface $profile,
        private DepartmentFetchInterface $departmentFetch,
    ) {}


    /**
     * Display properties
     *
     * @return void
     */
    public function index()
    {

        $departments = Cache::remember('profile_departments', 60, function () {
            return $this->departmentFetch->index();
        });

        return Inertia::render('System/Profiles', [
            'can' => [],
            'departments' => $departments['data'],
            'positions' => Helper::PROFILE_POSITIONS,
        ]);
    }

    /**
     * Store Property
     *
     * @param Request $request
     * @return void
     */
    public function store(ProfileFormRequest $request)
    {
        $result = $this->profile->storeProfile($request->all());

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
        $result = $this->profile->updateProfile($id, $request->all());

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
        $result = $this->profile->deleteProfile($id);

        $status = $result['status'] ?? 'error';
        $message = $result['message'] ?? 'An error occurred';

        return redirect()->back()->with($status, $message);
    }
}
