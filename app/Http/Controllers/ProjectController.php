<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Notifications\ReminderNotifications;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index($client)
    {
        $projects = Project::with('client', 'projectStatus', 'paymentMethod', 'paymentStatus')
                        ->where('client_id', $client)
                        ->where('user_id', app('auth')->id())->latest()->paginate(10);

        return response()->json([
            'projects' => $projects,
            'message' => 'Success!'
        ], 200);
    }

    public function getAll()
    {
        $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')
                        ->where('user_id', app('auth')->id())->latest()->get();

        return response()->json([
            'projects' => $projects,
            'message' => 'Success!'
        ], 200);
    }

    public function detail($project)
    {
        $project = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')->find($project);

        return response()->json([
            'message' => 'Success!',
            'project' => $project,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $this->validate($request, [
            'title' => 'required',
            'client_id' => 'required',
            'brief' => 'required',
            'file_location' => 'required',
            'deadline' => 'required'
        ]);
        $paymentStatus = PaymentStatus::find($request->payment_status_id);
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        $project = new Project();
        $project->fill($request->all());
        $project->paymentStatus()->associate($paymentStatus);
        $project->paymentMethod()->associate($paymentMethod);
        $project->save();

        return response()->json([
            'message' => 'Success!',
            'project' => $project,
        ], 200);
    }

    public function update(Request $request, $project)
    {
        $request->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $project = Project::find($project);
        $this->validate($request, [
            'title' => 'required',
            'client_id' => 'required',
            'brief' => 'required',
            'file_location' => 'required',
            'deadline' => 'required'
        ]);
        $projectStatus = $project->project_status_id;
        $paymentStatus = PaymentStatus::find($request->payment_status_id);
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        $client = Client::find($request->client_id);
        $project->fill($request->all());
        $project->paymentStatus()->associate($paymentStatus);
        $project->projectStatus()->associate($projectStatus);
        $project->paymentMethod()->associate($paymentMethod);
        $project->save();

        return response()->json([
            'message' => 'Success!',
            'project' => $project,
        ], 200);
    }

    public function delete($project)
    {
        $project = Project::find($project);
        $project->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request)
    {
        $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')
                    ->where('user_id', app('auth')->id())
                    ->where('title', 'LIKE', "%%".$request->input('q')."%%")
                    ->paginate(10);

        if ($projects->isEmpty()) {
            $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')
                    ->where('user_id', app('auth')->id())
                    ->whereHas('client', function ($query) use ($request){
                        return $query->where('name', 'LIKE', "%%".$request->input('q')."%%");
                    })
                    ->paginate(10);
        }

        return response()->json([
            'message' => 'Success!',
            'projects' => $projects
        ]);
    }

    public function updateProjectStatus($project, $projectStatus)
    {
        $project = Project::find($project);
        $projectStatus = ProjectStatus::find($projectStatus);

        $project->projectStatus()->associate($projectStatus);
        $project->save();

        return response()->json([
            'message' => 'Success!',
            'projects' => $project,
            'projectStatus' => $projectStatus->name
        ]);
    }
    public function updatePaymentStatus($project, $paymentStatus)
    {
        $project = Project::find($project);
        $paymentStatus = PaymentStatus::find($paymentStatus);

        $project->paymentStatus()->associate($paymentStatus);
        $project->save();

        return response()->json([
            'message' => 'Success!',
            'projects' => $project,
            'paymentStatus' => $paymentStatus->name
        ]);
    }

    public function updatePaymentMethod($project, $paymentMethod)
    {
        $project = Project::find($project);
        $paymentMethod = PaymentMethod::find($paymentMethod);

        $project->paymentMethod()->associate($paymentMethod);
        $project->save();

        return response()->json([
            'message' => 'Success!',
            'projects' => $project,
            'paymentMethod' => $paymentMethod->name
        ]);
    }

    public function reminder()
    {
    }
}
