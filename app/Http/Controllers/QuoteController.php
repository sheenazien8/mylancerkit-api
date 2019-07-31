<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::latest()->paginate(10);

        return response()->json([
            'quotes' => $quotes,
            'message' => 'Success!'
        ], 200);
    }
    public function getAll()
    {
        $quotes = Quote::latest()->get();

        return response()->json([
            'quotes' => $quotes,
            'message' => 'Success!'
        ], 200);
    }

    public function detail($quote)
    {
        $quote = Quote::find($quote);

        return response()->json([
            'message' => 'Success!',
            'quote' => $quote,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'quote' => 'required'
        ]);

        $quote = new Quote();
        $quote->fill($request->all());
        $quote->save();

        return response()->json([
            'message' => 'Success!',
            'quote' => $quote,
        ], 200);
    }

    public function update(Request $request, $quote)
    {
        $quote = Quote::find($quote);
        $this->validate($request, [
            'quote' => 'required'
        ]);

        $quote->fill($request->all());
        $quote->save();

        return response()->json([
            'message' => 'Success!',
            'quote' => $quote,
        ], 200);
    }


    public function delete($quote)
    {
        $quote = Quote::find($quote);
        $quote->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request)
    {
        $quotes = Quote::where('quote', 'LIKE', "%%".$request->input('q')."%%")
                    ->latest()->paginate(10);

        return response()->json([
            'message' => 'Success!',
            'quotes' => $quotes
        ]);
    }

}
