<?php
namespace App\Http\Controllers;

use App\Http\Requests\ConnectionConfiguration\UpdateConnectionConfigurationRequest;
use App\Http\Requests\ConnectionConfiguration\DeleteConnectionConfigurationRequest;
use App\Http\Requests\ConnectionConfiguration\RestoreConnectionConfigurationRequest;
use App\Models\ConfigurationType;
use App\Models\ConnectionConfiguration;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ConnectionConfigurationsController extends Controller
{
    private const COMPONENT_NAME = 'connection-configurations';
    
    public function index(): Response
    {
        return Inertia::render(self::COMPONENT_NAME . '/Index', [
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
            'deleteConnectionConfigurationUrl' => route('connection-configurations.delete'),
            'deletedUrl' => route('connection-configurations.deleted.index'),
        ]);
    }

    public function store(UpdateConnectionConfigurationRequest $request): RedirectResponse
    {
        /** @var ConnectionConfiguration $connectionConfiguration */
        $connectionConfiguration = ConnectionConfiguration::create($request->all());

        $connectionConfiguration->serversIn()->attach($request->get('servers_in_ids'));
        $connectionConfiguration->serversOut()->attach($request->get('servers_out_ids'));

        return Redirect::route('connection-configurations.index')->with('success', 'Конфигурация успешно добавлена!');
    }

    public function update(UpdateConnectionConfigurationRequest $request): RedirectResponse
    {
        /** @var ConnectionConfiguration $connectionConfiguration */
        $connectionConfiguration = ConnectionConfiguration::updateOrCreate(['id' => $request->get('id')], $request->all());

        $connectionConfigurationServersIn = $connectionConfiguration->serversIn();
        $connectionConfigurationServersOut = $connectionConfiguration->serversOut();

        $connectionConfigurationServersIn->detach();
        $connectionConfigurationServersOut->detach();

        $connectionConfigurationServersIn->attach($request->get('servers_in_ids'));
        $connectionConfigurationServersOut->attach($request->get('servers_out_ids'));

        return Redirect::route('connection-configurations.index')->with('success', 'Конфигурация успешно изменёна!');
    }

    public function delete(DeleteConnectionConfigurationRequest $request): RedirectResponse
    {
        ConnectionConfiguration::find($request->get('id'))->delete();

        return Redirect::route('connection-configurations.index')->with('success', 'Конфигурация успешно удалена!');
    }

    public function deleted(): Response
    {
        return Inertia::render(self::COMPONENT_NAME . '/Deleted', [
            'connectionConfigurations' => ConnectionConfiguration::with([
                    ConnectionConfiguration::RELATION_CONFIGURATION_TYPE,
                    ConnectionConfiguration::RELATION_SERVERS_IN,
                    ConnectionConfiguration::RELATION_SERVERS_OUT
                ])
                ->onlyTrashed()
                ->get(),
            'restoreConnectionConfigurationUrl' => route('connection-configurations.deleted.restore'),
            'restoreAllConnectionConfigurationsUrl' => route('connection-configurations.deleted.restore-all'),
            'finallyDeleteConnectionConfigurationUrl' => route('connection-configurations.deleted.finally-delete'),
            'finallyDeleteAllConnectionConfigurationsUrl' => route('connection-configurations.deleted.finally-delete-all'),
        ]);
    }

    public function restore(RestoreConnectionConfigurationRequest $request): RedirectResponse
    {
        ConnectionConfiguration::onlyTrashed()->find($request->get('id'))->restore();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Конфигурация успешно восстановлена!');
    }

    public function restoreAll(): RedirectResponse
    {
        ConnectionConfiguration::onlyTrashed()->restore();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Все конфигурации успешно восстановлены!');
    }

    public function finallyDelete(DeleteConnectionConfigurationRequest $request): RedirectResponse
    {
        /** @var ConnectionConfiguration $connectionConfiguration */
        $connectionConfiguration = ConnectionConfiguration::onlyTrashed()->find($request->get('id'));

        $connectionConfiguration->serversIn()->detach();
        $connectionConfiguration->serversOut()->detach();

        $connectionConfiguration->forceDelete();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Конфигурация окончательно и безвозвратно удалена!');
    }

    public function finallyDeleteAll(): RedirectResponse
    {
        /** @var ConnectionConfiguration $connectionConfiguration */
        $connectionConfigurations = ConnectionConfiguration::onlyTrashed();

        $connectionConfigurations->each(function ($connectionConfiguration) {
            $connectionConfiguration->serversIn()->detach();
            $connectionConfiguration->serversOut()->detach();
        });

        $connectionConfigurations->forceDelete();

        return Redirect::route('connection-configurations.deleted.index')->with('success', 'Все конфигурации окончательно и безвозвратно удалены!');
    }
}
