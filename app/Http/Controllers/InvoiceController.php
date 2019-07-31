<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice(Request $request)
    {
        // dd($request->id_project);
        $project = Project::find($request->id_project)
                    // ->with(['payments', 'client'])
                    ->where('user_id', app('auth')->id())
                    ->where('payment_status_id', 2);

        return response()->json([
            'project' => $project->pluck('title'),
            'client' => $project->pluck('client'),
            'payments' => $project->pluck('payments')
        ]);
    }
}
