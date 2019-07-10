<?php

namespace App\Http\Controllers;

use App\Models\PaymentStatus;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function index()
    {
        $paymentStatuses = PaymentStatus::with('projects')
        ->where('user_id', app('auth')->id())
        ->orWhereNull('user_id')->latest()->paginate(10);

        return response()->json([
            'paymentStatuses' => $paymentStatuses,
            'message' => 'Success!'
        ], 200);
    }

    public function getAll()
    {
        $paymentStatuses = PaymentStatus::with('projects')
        ->where('user_id', app('auth')->id())
        ->orWhereNull('user_id')->latest()->get();

        return response()->json([
            'paymentStatuses' => $paymentStatuses,
            'message' => 'Success!'
        ], 200);
    }

    public function detail($paymentStatus)
    {
        $paymentStatus = PaymentStatus::with('projects')->find($paymentStatus);

        return response()->json([
            'message' => 'Success!',
            'paymentStatus' => $paymentStatus,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'label_color' => 'required',
        ]);

        $paymentStatus = new PaymentStatus();
        $paymentStatus->fill($request->all());
        $paymentStatus->save();

        return response()->json([
            'message' => 'Success!',
            'paymentStatus' => $paymentStatus,
        ], 200);
    }

    public function update(Request $request, $paymentStatus)
    {
        $paymentStatus = PaymentStatus::find($paymentStatus);
        $this->validate($request, [
            'name' => 'required',
            'label_color' => 'required',
        ]);

        $paymentStatus->fill($request->all());
        $paymentStatus->save();

        return response()->json([
            'message' => 'Success!',
            'paymentStatus' => $paymentStatus,
        ], 200);
    }


    public function delete($paymentStatus)
    {
        $paymentStatus = PaymentStatus::find($paymentStatus);
        $paymentStatus->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request)
    {
        $paymentStatuses = PaymentStatus::with('projects')
                                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                                    ->where('user_id', app('auth')->id())
                                    ->latest()->paginate(10);

        if ($paymentStatuses->isEmpty()) {
            $paymentStatuses = PaymentStatus::with('projects')
                                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                                    ->latest()->paginate(10);
            if (!$paymentStatuses->pluck('user_id')->contains(app('auth')->id())) {
                $paymentStatuses = PaymentStatus::with('projects')
                                    ->where('name', 'sheena muhammad ali zien')
                                    ->latest()->paginate(10);
            }
        }

        return response()->json([
            'message' => 'Success!',
            'paymentStatuses' => $paymentStatuses
        ]);
    }
}
