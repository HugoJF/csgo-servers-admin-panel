/*********
** TODO **
**********/
// Decidir como auto-atualizar do banco de dados
// Atualizar `servers` com status
// Handlar ECONNRESET


// Refratorar codigo para suportar varios servidores
// Buscar servidores a serem analisados do banco de dados



/*****************
** DEPENDENCIES **
******************/
var Rcon      = require('rcon');
var request   = require('request');
var Sequelize = require('sequelize');
var colors    = require('colors');


/*************
** CONTANTS **
**************/ 


var STATS_INTERVAL  = 1000; //ms
var STATUS_INTERVAL = 1000;


var DB_TABLE       = 'homestead';
var DB_USER        = 'homestead';
var DB_PASS        = 'secret';
var DB_HOST        = 'localhost';
var DB_PORT        = 33060;
var DB_TIMEOUT     = 5;


/*********************
** GLOBAL VARIABLES **
*********************/ 


var servers = [];
var connections = [];


/********************
** INITIALIZATIONS **
*********************/ 


var sequelize = new Sequelize(DB_TABLE, DB_USER, DB_PASS, {
    host: DB_HOST,
    port: DB_PORT,
    logging: false
});


/*******************
** DB DEFINITIONS **
********************/ 


var Server = sequelize.define('servers', {
    id: { 
        type: Sequelize.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },

    name: Sequelize.STRING,
    ip: Sequelize.STRING,
    port: Sequelize.INTEGER,
    rcon_password: Sequelize.STRING,

    message_config_id: Sequelize.INTEGER.UNSIGNED,

    created_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    },
    updated_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    }
}, {
    freezeTableName: true,
    timestamps: false
});

var Stats = sequelize.define('stats', {
    id: { 
        type: Sequelize.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },
    
    netin: Sequelize.FLOAT,
    netout: Sequelize.FLOAT,
    uptime: Sequelize.INTEGER,
    maps: Sequelize.INTEGER,
    fps: Sequelize.FLOAT,
    players: Sequelize.INTEGER,
    svms: Sequelize.FLOAT,
    svms_stdv: Sequelize.FLOAT,
    var: Sequelize.FLOAT,

    status_id: Sequelize.INTEGER.UNSIGNED,
    server_id: Sequelize.INTEGER.UNSIGNED,

    created_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    },
    updated_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    }
}, {
    freezeTableName: true,
    timestamps: false
});

var Status = sequelize.define('status', {
    id: { 
        type: Sequelize.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },
    
    hostname: Sequelize.STRING,
    version: Sequelize.STRING,
    udpip: Sequelize.STRING,
    os: Sequelize.STRING,
    type: Sequelize.STRING,
    players: Sequelize.STRING,
    map: Sequelize.STRING,
    
    server_id: Sequelize.INTEGER.UNSIGNED,

    created_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    },
    updated_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    }
}, {
    freezeTableName: true,
    timestamps: false
});
var Players = sequelize.define('players', {
    id: { 
        type: Sequelize.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },
    
    userid: Sequelize.STRING,
    
    status_id: Sequelize.INTEGER.UNSIGNED,

    created_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    },
    updated_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    }
}, {
    freezeTableName: true,
    timestamps: false
});;
var PlayersStatus = sequelize.define('player_status', {
    id: { 
        type: Sequelize.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },
    
    userid: Sequelize.STRING,
    name: Sequelize.STRING,
    connected: Sequelize.STRING,
    ping: Sequelize.INTEGER,
    loss: Sequelize.INTEGER,
    state: Sequelize.STRING,
    rate: Sequelize.INTEGER,
    adr: Sequelize.STRING,

    player_id: Sequelize.INTEGER.UNSIGNED,

    created_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    },
    updated_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
        allowNull: false
    }
}, {
    freezeTableName: true,
    timestamps: false
});


/********************
** DB RELATIOSHIPS **
*********************/ 


Server.hasMany(Stats, {foreignKey: 'server_id'});
Stats.belongsTo(Server, {foreignKey: 'server_id'});



sequelize.sync({
    alter: false
}).then(function () {
    Server.findAll().then(function(serversQuery) {
        servers = serversQuery;
        console.log('Servers table acquired!'.green);

        console.log('Openning connections...'.green);
        openConnections();

        console.log('Starting intervals...'.green);
        start();
    });
});


function openConnections() {
    for(var index = 0; index < servers.length; index++) {
        (function (i){
            console.log(servers[i].port);
            connections[i] = new Rcon(servers[i].ip, servers[i].port, servers[i].rcon_password);


            connections[i].on('auth', function() {
                console.log(("Authed on server " + i + "!").green);

            }).on('response', function(str) {
                processResponse(str, i);

            }).on('end', function() {
              console.log(("Socket " + i + " closed!").green);
              process.exit();

            }).on('error', function() {
                console.log('is this it?'.blue);
            });

            connections[i].connect();
        })(index)
        
    }
}

function processResponse(str, connIndex) {
    if(isStatusResponse(str, connIndex)) {
        processStatusResponse(str, connIndex);
    } else if (isStatsResponse(str, connIndex)) {
        processStatsResponse(str, connIndex);
    } else {
        console.log('Received unknown response'.red);
        console.log(String(str).black.bgWhite);
    }

}

function processStatsResponse(str, connIndex) {
    console.log(('Received Stats RCON response from ' + connIndex).green)
    var parsed = parseStatsCommand(str, connIndex);
    if(parsed != undefined) {
        parsed.server_id = servers[connIndex].id;
        parsed.request_interval = STATS_INTERVAL;
        
        Stats.create(parsed).then(function(stat){
            console.log(('Successfuly inserted stat into database with ID: ' + String(stat.id).bold + ' and `server_id`: ' + String(stat.server_id).bold).green);
        }); 
      
    } else {
        console.log('Failed to parse stats command.'.red);
        console.log(String(str).red.bgGray)
    }
}

function processStatusResponse(str, connIndex) {
    console.log(('Received Status RCON response from ' + connIndex).green)

    var parsed = parseStatusCommand(str, connIndex);

    if(parsed != undefined) {
        var playerList = parsed.playerList;
        delete parsed.playerList;

        parsed.server_id = servers[connIndex].id;
        parsed.request_interval = STATUS_INTERVAL;

        Status.create(parsed).then(function(status) {
            console.log(('Successfuly inserted status into database with ID: ' + String(status.id).bold + ' and `server_id`: ' + String(status.server_id).bold).green);
        });
    }
}

function isStatusResponse(str) {
    return String(str).indexOf('udp/ip  :') != -1;
}

function isStatsResponse(str) {
    return String(str).indexOf('~tick') != -1;
}

function start() {
	setInterval(function() {
        for(var i = 0; i < connections.length; i++) {
		   connections[i].send('stats');
        }
	}, STATS_INTERVAL);


    setInterval(function() {
        for(var i = 0; i < connections.length; i++) {
           connections[i].send('status');
        }
    }, STATUS_INTERVAL);

    console.log(('Started setIntervals with ' + STATS_INTERVAL  + 'ms delay.').yellow.bold);
}


function parseStatsCommand(str, connIndex) {
    var dataArray = str.split(/\s+/);
    var dbColumns = ['netin', 'netout', 'uptime', 'maps', 'fps', 'players', 'svms', 'svms_stdv', 'var'];
    var dataObject = {};
    var failed = false;

    for(var i = 12; i <= 20; i++) {
        dataObject[dbColumns[i - 12]] = dataArray[i];
        if(dataArray[i] == undefined) failed = true;
    }

    return failed ? undefined : dataObject;
}

function parseStatusCommand(str, connIndex) {
    console.log(str);

    dataObject = {
        hostname: findBetween(str, 'hostname: '),
        version: findBetween(str, 'version : '),
        udpip: findBetween(str, 'udp/ip  : '),
        os: findBetween(str, 'os      : '),
        type: findBetween(str, 'type    : '),
        map: findBetween(str, 'map     : '),
        server_id: servers[connIndex].id,
        players: findBetween(str, 'players : '),
        playerList: parsePlayerList(findBetween(str, 'adr', '#end', 'm', true)),
    };

    return dataObject;
}


function parsePlayerList(str) {
    var playersInfo = str.split(/\n/);
    var players = [];
    for(var i = 0; i < playersInfo.length; i++) {

        if(playersInfo[i].indexOf('BOT') != -1) continue;

        var playerInfo = playersInfo[i].trim().match(/('.*?'|".*?"|\S+)/g);
        var player = {};

        var dbColumns = ['userid', 'name', 'uniqueid', 'connected', 'ping', 'loss', 'state', 'rate', 'adr'];

        if(playerInfo != undefined) {
            for(var i = 0; i < dbColumns.length; i++) {
                player[dbColumns[i]] = playerInfo[i + 2];
            }
        } else {
            console.log('Received `undefined` playerInfo - maybe empty, maybe bad response'.yellow.bold);
        }

        players.push(players);
    }

    return players;
}

function findBetween(str, left, right = undefined, flags = '', multiline = false) {
    if(right != undefined) {
        right = '(?:' + right + ')';
    } else { 
        right = '';
    }

    multiline = multiline ? '|\n' : '';

    return matchAndReturnGroup(str, new RegExp('(?:' + left + ')((.' + multiline + ')*)' + right, flags));
}
function matchAndReturnGroup(str, regex, group = 1) {
    var match = str.match(regex);
    if(match != null && match != undefined) {
        return match[group].trim();
    } 

    return 'no match';
}

/*
var stat = 
  CPU   NetIn   NetOut    Uptime  Maps   FPS   Players  Svms    +-ms   ~tick
  10.0      0.0      0.0     719    13  134.46       0    0.21    0.62    0.60

hostname: twitch.tv/de_nerdTV - #4 -  DM FFA HS ONLY 128 TICK DUST2 ONLY
version : 1.35.7.8/13578 491/6752 secure  [G:1:1196974]
udp/ip  : 177.54.156.142:27018  (public ip: 177.54.156.142)
os      :  Linux
type    :  community dedicated
map     : de_dust2
players : 0 humans, 0 bots (14/0 max) (not hibernating)

# userid name uniqueid connected ping loss state rate adr
#end
*/