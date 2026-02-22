<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentTableController extends Controller
{
    public function __invoke()
    {
        $departments = \App\Models\Department::query();

        return datatables()->of($departments)
            ->addColumn('action', function ($department) {
                return view('livewire.settings.department-action-buttons', ['department' => $department]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
