angular.module('app', ['Gdb'])

    .run(function (Gdb) {
        Gdb.init({
            server: 'http://localhost:8080/gdb',
            token: '4b96716bcb3d42fc01ff421ea2cfd757'
        });
    })

    .controller('MainController', function ($scope, Gdb) {
        $scope.change = function () {
            Gdb.set('key', $scope.key).then(function() {
                console.log("Value set");
            });
        };

        Gdb.get('key').then(function (data) {
            $scope.key = data;
        });

        Gdb.watch('key', function (value) {
            console.log("Value updated");
            $scope.key = value;
        });
    })
;