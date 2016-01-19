<?php

use Dredd\Hooks;
use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/../../../vendor/autoload.php';

$app = require __DIR__ . '/../../../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Artisan::call('migrate');

Hooks::beforeEach(function (&$transaction) use ($app) {
    $app->make('db')->beginTransaction();
});

Hooks::afterEach(function (&$transaction) use ($app) {
    $app->make('db')->rollback();
});

Hooks::afterAll(function (&$transaction) use ($app) {
    Artisan::call('migrate:rollback');
});

Hooks::before('/users > GET', function(&$transaction) {

    factory(\App\User::class)->create([
            'name' => 'Dom',
            'email' => 'ddelnano@gmail.com',
        ]
    );
});