<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleTableController extends Controller
{
    public function __invoke()
    {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->select('roles.*');

        return datatables()->of($roles)
            ->editColumn('name', function ($role) {
                return view('livewire.roles.role-name-column', ['role' => $role]);
            })
            ->addColumn('permissions', function ($role) {
                return view('livewire.roles.role-permissions-column', ['role' => $role]);
            })
            ->addColumn('action', function ($role) {
                return view('livewire.roles.role-action-buttons', ['role' => $role]);
            })
            ->rawColumns(['name', 'permissions', 'action'])
            ->make(true);
    }
}
