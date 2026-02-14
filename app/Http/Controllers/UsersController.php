<?php
namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    private const COMPONENT_NAME = 'users';
    
    public function index(): Response
    {
        $users = User::with([
            User::RELATION_TARIFFS,
        ])->where('is_admin', false)->get();
        
        foreach ($users as $user) {
            foreach ($user->tariffs as $tariff) {
                $tariff->is_active = $tariff->pivot->is_active;
            }
        }

        return Inertia::render(self::COMPONENT_NAME . '/Index', [
            'users' => $users,
        ]);
    }
}
