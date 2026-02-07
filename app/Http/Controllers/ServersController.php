<?php
namespace App\Http\Controllers;

use App\Http\Requests\Servers\DeleteServerRequest;
use App\Http\Requests\Servers\UpdateServerRequest;
use App\Models\Country;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Http\RedirectResponse;
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
            'updateServerUrl' => route('servers.update'),
            'deleteServerUrl' => route('servers.delete'),
        ]);
    }

    public function store(UpdateServerRequest $request): RedirectResponse
    {
        Server::create($request->all());

        return Redirect::route('servers.index')->with('success', 'Сервер успешно добавлен!');
    }

    public function update(UpdateServerRequest $request): RedirectResponse
    {
        Server::updateOrCreate(['id' => $request->get('id')], $request->all());

        return Redirect::route('servers.index')->with('success', 'Сервер успешно изменён!');
    }

    public function delete(DeleteServerRequest $request): RedirectResponse
    {
        Server::find($request->get('id'))->delete();

        return Redirect::route('servers.index')->with('success', 'Сервер успешно удалён!');
    }
}
