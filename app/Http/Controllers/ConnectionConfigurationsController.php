<?php
namespace App\Http\Controllers;

use App\Http\Requests\ConnectionConfiguration\UpdateConnectionConfigurationRequest;
use App\Http\Requests\ConnectionConfiguration\DeleteConnectionConfigurationRequest;
use App\Http\Requests\ConnectionConfiguration\RestoreConnectionConfigurationRequest;
use App\Models\ConfigurationType;
use App\Models\ConnectionConfiguration;
use App\Models\Country;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ConnectionConfigurationsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('connection-configurations/Index', [
            'connectionConfigurations' => ConnectionConfiguration::with([
                ConnectionConfiguration::RELATION_CONFIGURATION_TYPE,
                ConnectionConfiguration::RELATION_SERVERS_IN,
                ConnectionConfiguration::RELATION_SERVERS_OUT
            ])->get(),
            'configurationsTypeNames' => [
                'shadowSocks' => ConfigurationType::SHADOW_SOCKS,
                'doubleVpn' => ConfigurationType::DOUBLE_VPN,
            ],
            'serverTypeNames' => [
                'ss' => ServerType::SS,
                'vpnIo' => ServerType::VPN_IO,
                'vpnIn' => ServerType::VPN_IN,
                'vpnOut' => ServerType::VPN_OUT,
            ],
            'configurationsTypes' => ConfigurationType::select(['id as value', 'name as label'])->get(),
            'serversShadowSocksIn' => Server::select(['id as value', 'name as label'])
                ->whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                    $query->where('name', ServerType::SS);
                })->get(),
            'serversShadowSocksOut' => Server::select(['id as value', 'name as label'])
                ->whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                    $query->where('name', ServerType::VPN_IO);
                })->get(),
            'serversDoubleVpnIn' => Server::select(['id as value', 'name as label'])
                ->whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                    $query->where('name', ServerType::VPN_IN);
                })->get(),
            'serversDoubleVpnOut' => Server::select(['id as value', 'name as label'])
                ->whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                    $query->where('name', ServerType::VPN_OUT);
                })->get(),
            'createConnectionConfigurationUrl' => route('connection-configurations.store'),
            'updateConnectionConfigurationUrl' => route('connection-configurations.update'),
            'deleteServerUrl' => route('connection-configurations.delete'),
            'deletedUrl' => route('connection-configurations.deleted.index'),
        ]);
    }

    public function store(UpdateConnectionConfigurationRequest $request): RedirectResponse
    {
        /** @var ConnectionConfiguration $connectionConfiguration */
        $connectionConfiguration = ConnectionConfiguration::create($request->all());

        $connectionConfiguration->serversIn()->attach($request->get('servers_in_ids'));
        $connectionConfiguration->serversOut()->attach($request->get('servers_out_ids'));

        return Redirect::route('connection-configurations.index')->with('success', 'Сервер успешно добавлен!');
    }

    public function update(UpdateConnectionConfigurationRequest $request): RedirectResponse
    {
        ConnectionConfiguration::updateOrCreate(['id' => $request->get('id')], $request->all());

        return Redirect::route('connection-configurations.index')->with('success', 'Сервер успешно изменён!');
    }

    public function delete(DeleteConnectionConfigurationRequest $request): RedirectResponse
    {
        ConnectionConfiguration::find($request->get('id'))->delete();

        return Redirect::route('connection-configurations.index')->with('success', 'Сервер успешно удалён!');
    }

    public function deleted(): Response
    {
        return Inertia::render('servers/Deleted', [
            'servers' => Server::with([Server::RELATION_SERVER_TYPE, Server::RELATION_COUNTRY])->onlyTrashed()->get(),
            'serversTypes' => ServerType::select(['id as value', 'name as label'])->get(),
            'countries' => Country::select(['id as value', 'name as label'])->get(),
            'restoreServerUrl' => route('connection-configurations.deleted.restore'),
            'restoreAllServersUrl' => route('connection-configurations.deleted.restoreAll'),
            'finallyDeleteServerUrl' => route('connection-configurations.deleted.finallyDelete'),
            'finallyDeleteAllServersUrl' => route('connection-configurations.deleted.finallyDeleteAll'),
        ]);
    }

    public function restore(RestoreConnectionConfigurationRequest $request): RedirectResponse
    {
        ConnectionConfiguration::onlyTrashed()->find($request->get('id'))->restore();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Сервер успешно восстановлен!');
    }

    public function restoreAll(): RedirectResponse
    {
        ConnectionConfiguration::onlyTrashed()->restore();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Все серверы успешно восстановлены!');
    }

    public function finallyDelete(DeleteConnectionConfigurationRequest $request): RedirectResponse
    {
        ConnectionConfiguration::onlyTrashed()->find($request->get('id'))->forceDelete();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Сервер окончательно и безвозвратно удалён!');
    }

    public function finallyDeleteAll(): RedirectResponse
    {
        ConnectionConfiguration::onlyTrashed()->forceDelete();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Все серверы окончательно и безвозвратно удалены!');
    }
}
