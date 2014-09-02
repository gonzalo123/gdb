angular.module('Gdb', [])
    .factory('Gdb', function ($http, $q, $rootScope) {

        var socket,
            gdbServer,
            token,
            watches = {};

        var Gdb = {
            init: function (conf) {
                gdbServer = conf.server;
                token = conf.token;

                $http.get(gdbServer + '/conf', {params: {token: token}}).success(function (data) {
                    socket = io.connect(data.ioServer);
                    socket.on(data.chanel, function (data) {
                        watches.hasOwnProperty(data.key) ? watches[data.key](data.value) : null;
                        $rootScope.$apply();
                    });
                });
            },

            set: function (key, value) {
                var deferred = $q.defer();

                $http.post(gdbServer + '/' + key, {value: value, token: token}).success(function (data) {
                    deferred.resolve(data);
                });

                return deferred.promise;
            },

            get: function (key) {
                var deferred = $q.defer();

                $http.get(gdbServer + '/' + key, {params: {token: token}}).success(function (data) {
                    deferred.resolve(JSON.parse(data));
                });

                return deferred.promise;
            },

            watch: function (key, closure) {
                watches[key] = closure;
            }
        };

        return Gdb;
    });