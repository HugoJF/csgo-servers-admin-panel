<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Player::class, 50)->create([
        ])->each(function(App\Player $p) {
            $p->nameHistory()->saveMany(factory(App\NameHistory::class, 5)->create([
                'player_id' => $p->id,
            ]));

        });


        factory(App\Server::class, 10)->create()->each(function (App\Server $s) {
            $s->stats()->saveMany(factory(App\Stats::class, 100)->create([
                'server_id' => $s->id,
            ]));


            $s->status()->saveMany(factory(App\Status::class, 10)->create([
                'server_id' => $s->id,
            ])->each(function (App\Status $s) {
                $s->players()->attach(\App\Player::all()->random(15));
            }));

        });



        factory(App\MessageConfig::class, 10)->create()->each(function (App\MessageConfig $mc) {
            $mc->messages()->saveMany(factory(App\Message::class, 50)->create([
                'message_config_id' => $mc->id,
            ]));
        });

    }
}


/**
 *
 * messages
 * messages-configs
 * servers
 * stats
 * status
 * players
 * players-name-history
 *
 *
 * players-servers
 * players-status
 *
 */
