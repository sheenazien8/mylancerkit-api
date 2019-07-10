<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::with('projects')
                            ->where('user_id', app('auth')->id())
                            ->orWhereNull('user_id')->latest()->paginate(10);

        return response()->json([
            'paymentMethods' => $paymentMethods,
            'message' => 'Success!'
        ], 200);
    }

    public function getAll()
    {
        $paymentMethods = PaymentMethod::with('projects')
                            ->where('user_id', app('auth')->id())
                            ->orWhereNull('user_id')->latest()->get();

        return response()->json([
            'paymentMethods' => $paymentMethods,
            'message' => 'Success!'
        ], 200);
    }
    public function detail($paymentMethod)
    {
        $paymentMethod = PaymentMethod::with('projects')->find($paymentMethod);

        return response()->json([
            'message' => 'Success!',
            'paymentMethod' => $paymentMethod,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'label_color' => 'required',
        ]);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->fill($request->all());
        $paymentMethod->save();

        return response()->json([
            'message' => 'Success!',
            'paymentMethod' => $paymentMethod,
        ], 200);
    }

    public function update(Request $request, $paymentMethod)
    {
        $paymentMethod = PaymentMethod::find($paymentMethod);
        $this->validate($request, [
            'name' => 'required',
            'label_color' => 'required',
        ]);

        $paymentMethod->fill($request->all());
        $paymentMethod->save();

        return response()->json([
            'message' => 'Success!',
            'paymentMethod' => $paymentMethod,
        ], 200);
    }


    public function delete($paymentMethod)
    {
        $paymentMethod = PaymentMethod::find($paymentMethod);
        $paymentMethod->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request)
    {
        $paymentMethods = PaymentMethod::with('projects')
                                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                                    ->where('user_id', app('auth')->id())
                                    ->latest()->paginate(10);

        if ($paymentMethods->isEmpty()) {
            $paymentMethods = PaymentMethod::with('projects')
                                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                                    ->latest()->paginate(10);

            if (!$paymentMethods->pluck('user_id')->contains(app('auth')->id())) {
                $paymentMethods = PaymentMethod::with('projects')
                                    ->where('name', 'sheena muhammad ali zien')
                                    ->latest()->paginate(10);
            }
        }

        return response()->json([
            'message' => 'Success!',
            'paymentMethods' => $paymentMethods
        ]);
    }
}
