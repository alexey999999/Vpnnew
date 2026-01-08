<?php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ServersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('servers/Index', [
            'servers' => Server::with(['serverType', 'country'])->get(),
            'serversTypes' => ServerType::select(['id as value', 'name as label'])->get(),
            'countries' => Country::select(['id as value', 'name as label'])->get(),
            'createServerUrl' => route('servers.store'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Server::create($request->all());

        return Redirect::route('servers.index')->with('success', 'Сервер успешно добавлен!');
    }
}
