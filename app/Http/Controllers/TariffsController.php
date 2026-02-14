<?php
namespace App\Http\Controllers;

use App\Http\Requests\Tariff\UpdateTariffRequest;
use App\Http\Requests\Tariff\DeleteTariffRequest;
use App\Http\Requests\Tariff\RestoreTariffRequest;
use App\Models\ConnectionConfiguration;
use App\Models\Tariff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TariffsController extends Controller
{
    private const COMPONENT_NAME = 'tariffs';
    
    public function index(): Response
    {
        return Inertia::render(self::COMPONENT_NAME . '/Index', [
            'tariffs' => Tariff::with([
                Tariff::RELATION_CONFIGURATIONS,
            ])->get(),
            'configurations' => ConnectionConfiguration::select(['id as value', 'name as label'])->get(),
            'createTariffUrl' => route('tariffs.store'),
            'updateTariffUrl' => route('tariffs.update'),
            'deleteTariffUrl' => route('tariffs.delete'),
            'deletedUrl' => route('tariffs.deleted.index'),
        ]);
    }

    public function store(UpdateTariffRequest $request): RedirectResponse
    {
        /** @var Tariff $tariff */
        $tariff = Tariff::create($request->all());

        $tariff->configurations()->attach($request->get('configurations_ids'));

        return Redirect::route('tariffs.index')->with('success', 'Тариф успешно добавлен!');
    }

    public function update(UpdateTariffRequest $request): RedirectResponse
    {
        /** @var Tariff $tariff */
        $tariff = Tariff::updateOrCreate(['id' => $request->get('id')], $request->all());

        $connectionConfigurations = $tariff->configurations();

        $connectionConfigurations->detach();

        $connectionConfigurations->attach($request->get('configurations_ids'));

        return Redirect::route('tariffs.index')->with('success', 'Тариф успешно изменён!');
    }

    public function delete(DeleteTariffRequest $request): RedirectResponse
    {
        Tariff::find($request->get('id'))->delete();

        return Redirect::route('tariffs.index')->with('success', 'Тариф успешно удалён!');
    }

    public function deleted(): Response
    {
        return Inertia::render(self::COMPONENT_NAME . '/Deleted', [
            'tariffs' => Tariff::with([
                    Tariff::RELATION_CONFIGURATIONS,
                ])
                ->onlyTrashed()
                ->get(),
            'restoreTariffUrl' => route('tariffs.deleted.restore'),
            'restoreAllTariffsUrl' => route('tariffs.deleted.restore-all'),
            'finallyDeleteTariffUrl' => route('tariffs.deleted.finally-delete'),
            'finallyDeleteAllTariffsUrl' => route('tariffs.deleted.finally-delete-all'),
        ]);
    }

    public function restore(RestoreTariffRequest $request): RedirectResponse
    {
        Tariff::onlyTrashed()->find($request->get('id'))->restore();

        return Redirect::route('tariffs.deleted.index')->with('success', 'Тариф успешно восстановлен!');
    }

    public function restoreAll(): RedirectResponse
    {
        Tariff::onlyTrashed()->restore();

        return Redirect::route('tariffs.deleted.index')->with('success', 'Все тарифы успешно восстановлены!');
    }

    public function finallyDelete(DeleteTariffRequest $request): RedirectResponse
    {
        /** @var Tariff $tariff */
        $tariff = Tariff::onlyTrashed()->find($request->get('id'));

        $tariff->serversIn()->detach();
        $tariff->serversOut()->detach();

        $tariff->forceDelete();

        return Redirect::route('tariffs.deleted.index')->with('success', 'Тариф окончательно и безвозвратно удалён!');
    }

    public function finallyDeleteAll(): RedirectResponse
    {
        /** @var Tariff $tariff */
        $tariffs = Tariff::onlyTrashed();

        $tariffs->each(function ($tariff) {
            $tariff->serversIn()->detach();
            $tariff->serversOut()->detach();
        });

        $tariffs->forceDelete();

        return Redirect::route('tariffs.deleted.index')->with('success', 'Все тарифы окончательно и безвозвратно удалены!');
    }
}
