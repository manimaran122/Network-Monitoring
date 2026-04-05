<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "Starting Role Sync...\n";

// Ensure roles exist
$adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
$viewerRole = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

// Sync Admin users
$admins = User::where('role', 'admin')->get();
foreach ($admins as $u) {
    if (!$u->hasRole('admin')) {
        $u->assignRole($adminRole);
        echo "Assigned ADMIN role to: {$u->email}\n";
    }
}

// Sync Viewer users
$viewers = User::where('role', 'viewer')->get();
foreach ($viewers as $u) {
    if (!$u->hasRole('viewer')) {
        $u->assignRole($viewerRole);
        echo "Assigned VIEWER role to: {$u->email}\n";
    }
}

echo "Sync Complete.\n";
