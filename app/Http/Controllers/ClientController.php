<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('projects')
                    ->where('user_id', app('auth')->id())->latest()->paginate(10);

        return response()->json([
            'clients' => $clients,
            'message' => 'Success!'
        ], 200);
    }
    public function getAll()
    {
        $clients = Client::with('projects')
                    ->where('user_id', app('auth')->id())->latest()->get();

        return response()->json([
            'clients' => $clients,
            'message' => 'Success!'
        ], 200);
    }

    public function detail($client)
    {
        $client = Client::with('projects')->find($client);

        return response()->json([
            'message' => 'Success!',
            'client' => $client,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $client = new Client();
        $client->fill($request->all());
        $client->save();

        return response()->json([
            'message' => 'Success!',
            'client' => $client,
        ], 200);
    }

    public function update(Request $request, $client)
    {
        $client = Client::find($client);
        $this->validate($request, [
            'name' => 'required'
        ]);

        $client->fill($request->all());
        $client->save();

        return response()->json([
            'message' => 'Success!',
            'client' => $client,
        ], 200);
    }


    public function delete($client)
    {
        $client = Client::find($client);
        $client->delete();

        return response()->json([
            'message' => 'Success!'
        ]);
    }

    public function search(Request $request)
    {
        $clients = Client::with('projects')
                    ->where('user_id', app('auth')->id())
                    ->where('name', 'LIKE', "%%".$request->input('q')."%%")
                    ->latest()->paginate(10);

        return response()->json([
            'message' => 'Success!',
            'clients' => $clients
        ]);
    }
}

    public function client()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://quotes.rest/qod.json');

        return response()->json(json_decode($response->getBody())->contents);
    }
