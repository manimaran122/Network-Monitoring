#!/bin/bash
cd /var/www/test/monitoring

echo "=== Queue / Mail Debug ==="
php artisan tinker --execute="
echo 'Jobs pending: '.DB::table('jobs')->count().PHP_EOL;

\$failed = DB::table('failed_jobs')->get();
echo 'Failed jobs: '.\$failed->count().PHP_EOL;
foreach(\$failed as \$j) {
    echo '--- FAILED JOB ---'.PHP_EOL;
    echo 'Queue: '.\$j->queue.PHP_EOL;
    \$payload = json_decode(\$j->payload, true);
    echo 'Class: '.(\$payload['displayName'] ?? 'unknown').PHP_EOL;
    echo 'Error: '.substr(\$j->exception, 0, 500).PHP_EOL;
}
"

echo ""
echo "=== Sending a direct test mail ==="
php artisan tinker --execute="
try {
    \$result = Mail::raw('Test OTP email from Monitoring Tool at '.now(), function(\$m) {
        \$m->to('manimaran.b@stellaripl.com')->subject('Test Mail - Monitoring Tool');
    });
    echo 'Mail sent OK'.PHP_EOL;
} catch (\Exception \$e) {
    echo 'MAIL ERROR: '.\$e->getMessage().PHP_EOL;
}
"
