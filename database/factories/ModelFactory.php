<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Stats::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');

    return [
        'netin' => $f->randomFloat(3, 0, 30000),
        'netout' => $f->randomFloat(3, 0, 30000),
        'uptime' => $f->randomNumber(3),
        'maps' => $f->randomNumber(3),
        'fps' => $f->randomFloat(3, 0, 30000),
        'players' => $f->randomNumber(1),
        'svms' => $f->randomFloat(3, 0, 30000),
        'svms_stdv' => $f->randomFloat(3, 0, 30000),
        'var' => $f->randomFloat(3, 0, 30000),
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});

$factory->define(App\Status::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');

    return [
        'hostname' => $f->name . ' server',
        'version' => $f->randomNumber(),
        'udpip' => $f->ipv4,
        'os' =>$f->randomElement(['windows', 'linus']),
        'type' => $f->randomElement(['competitive', 'casual']),
        'map' => $f->randomElement(['de_dust2', 'de_dust', 'de_mirage', 'de_mirage']),
        'players' => '0 humans, 0 bots (' . $f->randomNumber(1) . '/0 max) (not hibernating)',
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});


$factory->define(App\Server::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');

    return [
        'name' => $f->name . ' server',
        'ip' => $f->ipv4,
        'port' => $f->randomNumber(5),
        'rcon_password' => $f->password(),
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});


$factory->define(App\Player::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');


    return [
        'userid' => 'STEAM:_' . $f->randomNumber(5),
        'name' => $f->name,
        'uniqueid' => 'STEAM_1:1:' . $f->randomNumber(5),
        'connected' => $f->randomNumber(2) . ':' . $f->randomNumber(2),
        'ping' => $f->randomNumber(2),
        'loss' => $f->randomNumber(2),
        'state' => 'active',
        'rate' => $f->randomNumber(5),
        'adr' => $f->ipv4,
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});


$factory->define(App\Message::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');


    return [
        'name' => $f->name,
        'message' => $f->text(100),
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});


$factory->define(App\MessageConfig::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');


    return [
        'name' => $f->name,
        'serverName' => $f->name . ' servers',
        'mySQL' => 'false',
        'serverType' => 'sql',
        'time' => '500',
        'languages' => 'pt-br',
        'defaultLanguage' => 'pt-br',
        'logExpiredMessages' => 'true',

        'wm_enable' => true,
        'wm_type' => 'say',
        'wm_delay' => '50',
        'wm_flags' => '',
        'wm_message' => $f->text(),

        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});

$factory->define(App\NameHistory::class, function (Faker\Generator $f) {
    $created_at = $f->dateTimeThisMonth()->format('Y-m-d H:i:s');


    return [
        'name' => $f->name,
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});