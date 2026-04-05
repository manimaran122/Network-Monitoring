<?php

use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Schema::disableForeignKeyConstraints();
Schema::dropIfExists('model_has_permissions');
Schema::dropIfExists('model_has_roles');
Schema::dropIfExists('role_has_permissions');
Schema::dropIfExists('roles');
Schema::dropIfExists('permissions');
Schema::dropIfExists('cache');
Schema::enableForeignKeyConstraints();

echo "Tables dropped successfully.\n";
