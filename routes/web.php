<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', 'AuthController@login')->name('login');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'check-steam-login'], function () {


    Route::get('/', function () {
        return view('dashboard', [
            'servers' => \App\Server::all(),
        ]);
    })->name('home');

    /**
     * Servers
     */
    Route::get('/servers/{id}', 'ServersController@manage')->name('servers-manage');
    Route::get('/servers', 'ServersController@index')->name('servers');

    /**
     * Settings
     */
    Route::get('/settings', 'ServersController@index')->name('settings');

    /**
     * Messages Configs
     */
    Route::get('/messages-configs', 'MessagesConfigController@index')->name('message_configs');

    /**
     * Messages
     */
    Route::get('/messages', 'MessagesController@index')->name('messages');

    /**
     * Status
     */
    Route::get('/status', 'StatusController@index')->name('status');

    /**
     * Stats
     */
    Route::get('/stats/', 'StatsController@index')->name('stats');

    /**
     * Players
     */
    Route::get('/players/{id}/name-history', 'PlayersController@nameHistory')->name('players-name-history');
    Route::get('/players', 'PlayersController@index')->name('players');

    /**
     * Daemon
     */
    Route::get('/daemon-logs/', 'DaemonController@logs')->name('daemon-logs');
});
