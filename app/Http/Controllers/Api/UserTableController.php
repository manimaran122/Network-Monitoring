<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserTableController extends Controller
{
    public function __invoke()
    {
        $users = User::with('roles')->select(['id', 'name', 'email', 'profile_photo_path', 'created_at']);

        return DataTables::of($users)
            ->addColumn('profile_photo_url', function ($user) {
                return $user->profile_photo_url;
            })
            ->addColumn('role', function ($user) {
                return $user->roles->map(function($role) {
                    return '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">' . ucfirst($role->name) . '</span>';
                })->implode(' ');
            })
            ->addColumn('action', function ($user) {
                return '
                    <div class="flex items-center gap-2">
                        <button onclick="Livewire.dispatch(\'editUser\', {id: '.$user->id.'})" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="confirm(\'Are you sure you want to delete this User?\') ? Livewire.dispatch(\'deleteUser\', {id: '.$user->id.'}) : false" class="text-red-600 hover:text-red-900" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['role', 'action'])
            ->make(true);
    }
}
