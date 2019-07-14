<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Notifications\ReminderNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\Facades\Image;

class ProjectController extends Controller
{
    public function index($client)
    {
        $projects = Project::with('client', 'projectStatus', 'paymentMethod', 'paymentStatus', 'payments')
                        ->where('client_id', $client)
                        ->whereHas('projectStatus', function ($query)
                        {
                            return $query->where('name', '!=', 'TRASH');
                        })
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
        $project = Project::with(
                'client',
                'paymentStatus',
                'paymentMethod',
                'projectStatus',
                'payments')->find($project);

        return response()->json([
            'message' => 'Success!',
            'project' => $project,
        ], 200);
    }
    public $path;
    public $dimensions;

    public function __construct()
    {
        //DEFINISIKAN PATH
        $this->path = storage_path('app/public/images');
        //DEFINISIKAN DIMENSI
        $this->dimensions = ['245', '300', '500'];
    }
    private function uploadFile($file, $fileExists = null)
    {
        if(File::exists($this->path.'/'.$fileExists)){
            File::delete($this->path.'/'.$fileExists);
            foreach ($this->dimensions as $row) {
                File::delete($this->path . '/' . $row . '/' . $fileExists);
            }
        }
        if (!File::isDirectory($this->path)) {
            File::makeDirectory($this->path, 0777, true);
        }
        $fileName = null;
        if ($file) {
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->path . '/' . $fileName);
            foreach ($this->dimensions as $row) {
                $canvas = Image::canvas($row, $row);
                $resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {
                    $constraint->aspectRatio();
                });

                if (!File::isDirectory($this->path . '/' . $row)) {
                    File::makeDirectory($this->path . '/' . $row);
                }
                $canvas->insert($resizeImage, 'center');
                $canvas->save($this->path . '/' . $row . '/' . $fileName);
            }
        }

        return $fileName;
    }
    public function store(Request $request)
    {
        $uploadedFile = $request->file('image');
        $filename = $this->uploadFile($uploadedFile);
        $request->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $this->validate($request, [
            'title' => 'required',
            'client_id' => 'required',
            'brief' => 'required',
            'file_location' => 'required',
            'deadline' => 'required'
        ]);
        $request->request->add([
            'reffile_image' => $filename,
            'image_name' => $this->path.'/'
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
        $project = Project::find($project);
        $uploadedFile = $request->file('image');
        $filename = $this->uploadFile($uploadedFile, $project->reffile_image);
        $request->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $this->validate($request, [
            'title' => 'required',
            'client_id' => 'required',
            'brief' => 'required',
            'file_location' => 'required',
            'deadline' => 'required'
        ]);
        $request->request->add([
            'reffile_image' => $filename,
            'image_name' => $this->path.'/'
        ]);
        $projectStatus = ProjectStatus::find($request->project_status_id);
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
                        ->when($request->input('client_id'), function ($query) use ($request)
                        {
                            return $query->where('client_id', $request->input('client_id'));
                        })
                        ->where('user_id', app('auth')->id())
                        ->whereHas('projectStatus', function ($query)
                        {
                            return $query->where('name', '!=', 'TRASH');
                        })
                        ->where('title', 'LIKE', "%%".$request->input('q')."%%")
                        ->paginate(10);

        if ($projects->isEmpty()) {
            $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')
                    ->where('user_id', app('auth')->id())
                    ->when($request->input('client_id'), function ($query) use ($request)
                    {
                        return $query->where('client_id', $request->input('client_id'));
                    })
                    ->whereHas('client', function ($query) use ($request){
                        return $query->where('name', 'LIKE', "%%".$request->input('q')."%%");
                    })
                    ->whereHas('projectStatus', function ($query)
                    {
                        return $query->where('name', '!=', 'TRASH');
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

    public function trashSearch(Request $request)
    {
        $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')
                    ->where('user_id', app('auth')->id())
                    ->whereHas('projectStatus', function ($query)
                    {
                        return $query->where('name', 'TRASH');
                    })
                    ->where('title', 'LIKE', "%%".$request->input('q')."%%")
                    ->paginate(10);

        if ($projects->isEmpty()) {
            $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus')
                    ->where('user_id', app('auth')->id())
                    ->whereHas('client', function ($query) use ($request){
                        return $query->where('name', 'LIKE', "%%".$request->input('q')."%%");
                    })
                    ->whereHas('projectStatus', function ($query)
                    {
                        return $query->where('name', 'TRASH');
                    })
                    ->paginate(10);
        }

        return response()->json([
            'message' => 'Success!',
            'projects' => $projects
        ]);
    }

    public function projectStatus(Request $request)
    {
        $projects = Project::with('client', 'paymentStatus', 'paymentMethod', 'projectStatus', 'payments')
                        ->where('user_id', app('auth')->id())
                        ->when($request->input('client_id'), function ($q) use ($request)
                        {
                            $q->where('client_id', $request->input('client_id'));
                        })
                        ->when($request->input('project_status_id'), function ($q) use ($request)
                        {
                            $q->where('project_status_id', $request->input('project_status_id'));
                        });
        if ($request->input('paginate')) {
            if ($request->input('orderBy')) {
                $projects = $projects->orderBy('id', $request->input('orderBy'));
            }else {
                $projects = $projects->latest();
            }
            $projects = $projects->paginate(10);
        }else {
            if ($request->input('orderBy')) {
                $projects = $projects->orderBy('id', $request->input('orderBy'));
            }else {
                $projects = $projects->latest();
            }
            $projects = $projects->get();
        }

        return response()->json([
            'projects' => $projects,
            'message' => 'Success!'
        ], 200);
    }

    public function incomeByProject(Request $request)
    {
        $query = "select sum(amount) as total_amount from  payments as pay join projects as proj
        on pay.project_id = proj.id join project_status
        on project_status.id = proj.project_status_id where proj.payment_status_id = 2
        and project_status_id = 7 and proj.user_id = " . app('auth')->id().
        " and pay.created_at LIKE '" .Carbon::now()->format('Y-m') ."%%'";
        $amount = app('db')->select($query);

        return response()->json([
            'amount' => $amount,
            'message' => 'Success!'
        ], 200);
    }

    public function bestFiveProject()
    {
        $date = Carbon::now()->format('Y-m');
        $query = "select * from projects as proj JOin payment_status as ps
        on ps.id = proj.payment_status_id join payments as pay
        on proj.id = pay.project_id where proj.payment_status_id = 2
        and proj.deadline like '". $date ."%%' and proj.project_status_id != 9 order by pay.amount desc limit 5;";
        $projects = Project::where('user_id', app('auth')->id())
                            ->with('payments')
                            ->where('payment_status_id', 2)
                            ->where('deadline', 'LIKE', $date.'%%')
                            ->where('project_status_id', '!=', 9)
                            ->whereHas('payments', function ($query)
                            {
                                return $query->orderBy('amount', 'desc');
                            })
                            ->get();

        return response()->json([
            'projects' => $projects,
            'message' => 'Success!'
        ], 200);
    }

    public function projectNearDeadline(Request $request)
    {
        $projects = Project::with('paymentStatus', 'client')
                            ->where('deadline', '>' , Carbon::now()->format('Y-m-d'))
                            ->whereIn('project_status_id', [1,2,3,4])
                            ->orderBy('deadline', 'asc')
                            ->where('user_id', app('auth')->id())
                            ->when($request->input('limit'), function ($query) use ($request)
                            {
                                return $query->limit($request->input('limit'));
                            })->get();

        return response()->json([
            'projects' => $projects,
            'message' => 'success'
        ]);
    }

    public function reminder()
    {

    }
}
