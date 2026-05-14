<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$unread = \App\Models\Message::where('is_read', false)->get();
echo "Unread messages:\n";
foreach($unread as $m) {
    echo "ID: {$m->id}, Sender: {$m->sender_id}, Receiver: {$m->receiver_id}\n";
    $senderExists = \App\Models\User::find($m->sender_id) ? 'Yes' : 'No';
    echo "  Sender exists? $senderExists\n";
}
