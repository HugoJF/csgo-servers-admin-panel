/*****************
** DEPENDENCIES **

******************/
var Rcon      = require('rcon');
var request   = require('request');
var Sequelize = require('sequelize');
var fs        = require('fs');
var util      = require('util');
var sq        = require('querystring');


/*************
** CONTANTS **
**************/

var STATS_INTERVAL  = 5000; //ms
var STATUS_INTERVAL = 5000;

var DB_TABLE       = 'server_dashboard_staging';
var DB_USER        = 'server_dashboard_staging';
var DB_PASS        = 'JSwV_v3W5_%-DFXbnCa.81PNRkobDdyK';
var DB_HOST        = 'localhost';
var DB_PORT        = 3306;
var DB_TIMEOUT     = 5;

var EMAIL_FROM = '';
var EMAIL_TO = '';
var EMAIL_URL = '';


/*********************
** GLOBAL VARIABLES **
*********************/

var servers = [];
var connections = [];
var connectionLastSeen = [];
var errorNotifications = [];
var offlineNotificationIntervals = [1, 5, 10, 15, 20, 30, 45, 60];


/********************
** INITIALIZATIONS **
*********************/

var sequelize = new Sequelize(DB_TABLE, DB_USER, DB_PASS, {
    host: DB_HOST,
    port: DB_PORT,
    logging: false
});


/************************
** STDOUT REDIRECTIONS **
*************************/

var log_file = fs.createWriteStream(__dirname + '/logs/logs.log', {flags : 'w'});
var log_stdout = process.stdout;

console.log = function(d) { //
    log_file.write(util.format(d) + '\n');
    log_stdout.write(util.format(d) + '\n');
};

console.log('Redirected console.log output to file');


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

var DaemonLogs = sequelize.define('daemon_logs', {
    id: {
        type: Sequelize.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },

    message: Sequelize.STRING,
    log: Sequelize.TEXT,
    type: Sequelize.STRING,

    created_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
    },
    updated_at: {
        type: 'TIMESTAMP',
        defaultValue: Sequelize.literal('CURRENT_TIMESTAMP'),
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

notifyConnectionError(0, 15);
console.log('notified?');

sequelize.sync({
    alter: false
}).then(function () {
    sendEmail('Daemon is up and running again');
    Server.findAll().then(function(serversQuery) {
        servers = serversQuery;
        console.log('Servers table acquired!');
        log('Servers table acquired', servers);

        console.log('Openning connections...');
        openConnections();

        console.log('Starting intervals...');
        start();
    });
});


function openConnections() {
    for(var index = 0; index < servers.length; index++) {
        connections[index] = createConnection(index, servers[index].ip, servers[index].port, servers[index].rcon_password);
        connections[index].connect();
        connectionLastSeen[index] = (new Date).getTime();
    }
}

function createConnection(id, ip, port, rcon_password) {
    var connection = new Rcon(ip, port, rcon_password);


    (function (i, ip, port, rcon_password){
        connection.on('auth', function() {
            console.log("Authed on server " + i + "!");
            log('Authenticated on server ' + i);

        }).on('response', function(str) {
            processResponse(str, i);
            connectionLastSeen[i] = (new Date).getTime();
            errorNotifications[i] = 0;

        }).on('end', function(err) {
          console.log("Socket " + i + " closed!");
          log('Socket from server ' + i + ' got closed', err);

        }).on('error', function(err) {
            console.log("ERROR: " + err);
            console.log('Trying to reopen connection to server ' + i);
            log('Error caught on server ' + i + ', recreating connection...', err);
            connections[i] = createConnection(i, ip, port, rcon_password);
            connections[i].connect();
            var deltaTime = ((new Date).getTime() - connectionLastSeen[i]) / 1000 / 60;

            if(Math.round(deltaTime) > offlineNotificationIntervals[errorNotifications[i]]) {
                console.log('Sending notifications that server is offline');
                notifyConnectionError(i, Math.round(deltaTime))
                errorNotifications[i]++;
            }

        });
    })(id, ip, port, rcon_password)

    return connection;
}

function notifyConnectionError(i, deltaTime) {
    sendEmail("Server is offline for " + Math.round(deltaTime) + "minutes", "ya that sucks");
}

function sendEmail(subject, textData) {
    var postData = {
        from: EMAIL_FROM,
        to: EMAIL_TO,
        subject: subject,
        text: textData
    };

    var options = {
        url: EMAIL_URL,
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        qs: postData
    };

    request(options, function(err, res, body) {
        console.log(body);
        console.log('err: ' + err);
        console.log('res: ' + res);
    });
}

function processResponse(str, connIndex) {

    log('Receiving response from connection ' + connIndex, str);

    if(isStatusResponse(str, connIndex)) {
        processStatusResponse(str, connIndex);
    } else if (isStatsResponse(str, connIndex)) {
        processStatsResponse(str, connIndex);
    } else {
        console.log('Received unknown response: ' + str);
        log('Received unknown response', str);
    }

}

function processStatsResponse(str, connIndex) {
    console.log('Received Stats RCON response from ' + connIndex)
    var parsed = parseStatsCommand(str, connIndex);
    if(parsed != undefined) {
        parsed.server_id = servers[connIndex].id;
        parsed.request_interval = STATS_INTERVAL;

        console.log('Before creating stats entry');
        Stats.create(parsed).then(function(stat){
            console.log('Successfuly inserted stat into database with ID: ' + stat.id + ' and `server_id`: ' + stat.server_id);
        });

    } else {
        console.log('Failed to parse stats command. ' + str);
        log('Failed to parse stats command', str);
    }
}

process.on('uncaughtException', function (err) {
    console.log('Caught exception: ' + err);
    log('Uncaught Exception on process', err);
});

function processStatusResponse(str, connIndex) {
    console.log('Received Status RCON response from ' + connIndex);
    var parsed = parseStatusCommand(str, connIndex);

    if(parsed != undefined) {
        var playerList = parsed.playerList;
        delete parsed.playerList;

        parsed.server_id = servers[connIndex].id;
        parsed.request_interval = STATUS_INTERVAL;

        Status.create(parsed).then(function(status) {
            console.log('Successfuly inserted status into database with ID: ' + status.id + ' and `server_id`: ' + status.server_id);
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

    console.log('Started setIntervals with ' + STATS_INTERVAL  + 'ms delay.');
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
            for(var j = 0; j < dbColumns.length; j++) {
                player[dbColumns[j]] = playerInfo[j + 2];
            }
        } else {
            console.log('Received `undefined` playerInfo - maybe empty, maybe bad response');
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

/**
 *  Log function to send logs to the database
 */

function log(message, log, type = undefined) {

    console.log('dbLogging: ' + message + ' ' + log);

    if(type == undefined) {
        type = 'GENERIC';
    }

    var content = {
        message: message,
        log: log,
        type: type,
    };

    DaemonLogs.create(content).then(function(log) {
        console.log('Successfuly inserted log into database with ID: ' + log.id);
    }).catch(function (err) {
        console.log('ERROR while creating DaemonLogs: ' + err);
    });
}