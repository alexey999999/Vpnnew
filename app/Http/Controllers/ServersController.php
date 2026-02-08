<?php
namespace App\Http\Controllers;

use App\Http\Requests\Servers\DeleteServerRequest;
use App\Http\Requests\Servers\RestoreServerRequest;
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
            'deletedIndexUrl' => route('servers.deleted.index'),
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

    public function deletedIndex(): Response
    {
        return Inertia::render('servers/DeletedIndex', [
            'servers' => Server::with(['serverType', 'country'])->onlyTrashed()->get(),
            'serversTypes' => ServerType::select(['id as value', 'name as label'])->get(),
            'countries' => Country::select(['id as value', 'name as label'])->get(),
            'restoreServerUrl' => route('servers.deleted.restore'),
            'restoreAllServersUrl' => route('servers.deleted.restoreAll'),
            'finallyDeleteServerUrl' => route('servers.deleted.finallyDelete'),
            'finallyDeleteAllServersUrl' => route('servers.deleted.finallyDeleteAll'),
        ]);
    }

    public function restore(RestoreServerRequest $request): RedirectResponse
    {
        Server::onlyTrashed()->find($request->get('id'))->restore();

        return Redirect::route('servers.deleted.index')->with('success', 'Сервер успешно восстановлен!');
    }

    public function restoreAll(): RedirectResponse
    {
        Server::onlyTrashed()->restore();

        return Redirect::route('servers.deleted.index')->with('success', 'Все серверы успешно восстановлены!');
    }

    public function finallyDelete(DeleteServerRequest $request): RedirectResponse
    {
        Server::onlyTrashed()->find($request->get('id'))->forceDelete();

        return Redirect::route('servers.deleted.index')->with('success', 'Сервер окончательно и безвозвратно удалён!');
    }

    public function finallyDeleteAll(): RedirectResponse
    {
        Server::onlyTrashed()->forceDelete();

        return Redirect::route('servers.deleted.index')->with('success', 'Все серверы окончательно и безвозвратно удалены!');
    }
}
