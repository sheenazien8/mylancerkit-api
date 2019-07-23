<?php

namespace App\Http\Controllers;

use App\Models\ProjectStatus;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    public function index()
    {
        $projectStatuses = ProjectStatus::with('projects')
        ->where('user_id', app('auth')->id())
        ->orWhereNull('user_id')->latest()->paginate(10);

        return response()->json([
            'projectStatuses' => $projectStatuses,
            'message' => 'Success!'
        ], 200);
    }

    public function getAll()
    {
        $projectStatuses = ProjectStatus::with('projects')
        ->where('user_id', app('auth')->id())
        ->orWhereNull('user_id')->latest()->get();

        return response()->json([
            'projectStatuses' => $projectStatuses,
            'message' => 'Success!'
        ], 200);
    }



    public function detail($projectStatus)
    {
        $projectStatus = ProjectStatus::with('projects')->find($projectStatus);

        return response()->json([
            'message' => 'Success!',
            'projectStatus' => $projectStatus,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'label_color' => 'required',
        ]);

        $projectStatus = new ProjectStatus();
        $projectStatus->fill($request->all());
        $projectStatus->save();

        return response()->json([
            'message' => 'Success!',
            'projectStatus' => $projectStatus,
        ], 200);
    }

    public function update(Request $request, $projectStatus)
    {
        $projectStatus = ProjectStatus::find($projectStatus);
        $this->validate($request, [
            'name' => 'required',
            'label_color' => 'required',
        ]);

        $projectStatus->fill($request->all());
        $projectStatus->save();

        return response()->json([
            'message' => 'Success!',
            'projectStatus' => $projectStatus,
        ], 200);
    }


    public function delete($projectStatus)
    {
        $projectStatus = ProjectStatus::find($projectStatus);
        $projectStatus->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request)
    {
        $projectStatuses = ProjectStatus::with('projects')
                                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                                    ->where('user_id', app('auth')->id())
                                    ->latest()->paginate(10);

        if ($projectStatuses->isEmpty()) {
            $projectStatuses = ProjectStatus::with('projects')
                                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                                    // ->where('user_id', app('auth')->id())
                                    ->latest()->paginate(10);
            if (!$projectStatuses->pluck('user_id')->contains(app('auth')->id())) {
                $projectStatuses = ProjectStatus::with('projects')
                                    ->where('name', 'sheena muhammad ali zien')
                                    ->latest()->paginate(10);
            }
        }

        return response()->json([
            'message' => 'Success!',
            'projectStatuses' => $projectStatuses
        ]);
    }
}
