<?php

namespace App\Livewire\Settings;

use Livewire\Component;

use App\Models\ActivityLog; // Assuming model exists, wait I didn't create ActivityLog model yet? I created the table. I need a model.
// Or just use DB builder. Model is better.
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ActivityLogs extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.settings.activity-logs', ['logs' => $logs]);
    }
}
