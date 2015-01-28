var learnodex = angular.module('learnodex', []);

learnodex.service('environment', function () {
    // Set to false to turn of all debugging options when using this service
    var debug = true;

    var log = function (data) {
        if (debug) {
            console.log(data);
        }
    };

    return {
        log: log
    };
});

learnodex.service('fosRouting', function () {
    var generate = function (name, opt_params, absolute) {
        return Routing.generate(name, opt_params, absolute);
    };

    return {
        generate: generate
    };
});

learnodex.service('api', ['$http', 'fosRouting', function ($http, fosRouting) {
    var randomCard = function (data) {
        $http({method: 'GET', url: fosRouting.generate('la_learnodex_api_random_card')}).success(data.success);
    };

    return {
        randomCard: randomCard
    };
}]);

learnodex.controller('LearnodexController', ['$scope', 'api', 'environment', function ($scope, api, environment) {
    $scope.newCard = function () {
        api.randomCard({
            success: function (data) {
                environment.log(data);
                $scope.card = data;
            }
        });
    };
}]);
