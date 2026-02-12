<?php
namespace App\Http\Controllers;

use App\Http\Requests\Server\DeleteServerRequest;
use App\Http\Requests\Server\RestoreServerRequest;
use App\Http\Requests\Server\UpdateServerRequest;
use App\Models\Country;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ServersController extends Controller
{
    private const COMPONENT_NAME = 'servers';

    public function index(): Response
    {
        return Inertia::render(self::COMPONENT_NAME . '/Index', [
            'servers' => Server::with([Server::RELATION_SERVER_TYPE, Server::RELATION_COUNTRY])->get(),
            'serversTypes' => ServerType::select(['id as value', 'name as label'])->get(),
            'countries' => Country::select(['id as value', 'name as label'])->get(),
            'createServerUrl' => route('servers.store'),
            'updateServerUrl' => route('servers.update'),
            'deleteServerUrl' => route('servers.delete'),
            'deletedUrl' => route('servers.deleted.index'),
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

    public function deleted(): Response
    {
        return Inertia::render(self::COMPONENT_NAME . '/Deleted', [
            'servers' => Server::with([Server::RELATION_SERVER_TYPE, Server::RELATION_COUNTRY])->onlyTrashed()->get(),
            'serversTypes' => ServerType::select(['id as value', 'name as label'])->get(),
            'countries' => Country::select(['id as value', 'name as label'])->get(),
            'restoreServerUrl' => route('servers.deleted.restore'),
            'restoreAllServersUrl' => route('servers.deleted.restore-all'),
            'finallyDeleteServerUrl' => route('servers.deleted.finally-delete'),
            'finallyDeleteAllServersUrl' => route('servers.deleted.finally-delete-all'),
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
