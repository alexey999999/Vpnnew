<?php
namespace App\Http\Controllers;

use App\Models\Server;
use Inertia\Inertia;
use Inertia\Response;

class ServersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('servers/Index', [
            'servers' => Server::with(['serverType', 'country'])->get(),
        ]);
    }
}
