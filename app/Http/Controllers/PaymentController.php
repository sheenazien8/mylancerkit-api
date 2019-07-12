<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('project')
                            ->where('user_id', app('auth')->id())
                            ->latest()->paginate(10);

        return response()->json([
            'payments' => $payments,
            'message' => 'Success!'
        ], 200);
    }

    public function getAll()
    {
        $payments = Payment::with('project')
                            ->where('user_id', app('auth')->id())
                            ->orWhereNull('user_id')->latest()->get();

        return response()->json([
            'payments' => $payments,
            'message' => 'Success!'
        ], 200);
    }
    public function detail($payment)
    {
        $payment = Payment::with('project')->find($payment);

        return response()->json([
            'message' => 'Success!',
            'payment' => $payment,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'payment' => 'required',
        ]);
        $project = Project::find($request->project_id);
        $payment = new Payment();
        $payment->fill($request->all());
        $payment->project()->associate($project);
        $payment->save();

        return response()->json([
            'message' => 'Success!',
            'payment' => $payment,
        ], 200);
    }

    public function update(Request $request, $payment)
    {
        $payment = Payment::find($payment);
        $this->validate($request, [
            'amount' => 'required',
            'payment' => 'required',
        ]);
        $project = Project::find($request->project_id);
        $payment->fill($request->all());
        $payment->project()->associate($project);
        $payment->save();

        return response()->json([
            'message' => 'Success!',
            'payment' => $payment,
        ], 200);
    }


    public function delete($payment)
    {
        $payment = Payment::find($payment);
        $payment->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request, $payment)
    {
        $payments = Payment::with('project')
                            ->where('payment_id', $payment)
                            ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                            ->where('user_id', app('auth')->id())
                            ->latest()->paginate(10);

        return response()->json([
            'message' => 'Success!',
            'payments' => $payments
        ]);
    }
}
