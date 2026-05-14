<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$messages = \App\Models\Message::all();
$deletedCount = 0;
foreach($messages as $m) {
    if (!\App\Models\User::where('id', $m->sender_id)->exists() || !\App\Models\User::where('id', $m->receiver_id)->exists()) {
        $m->delete();
        $deletedCount++;
    }
}
echo "Deleted $deletedCount orphaned messages.\n";
