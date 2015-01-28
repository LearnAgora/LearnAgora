var learnodex = angular.module('learnodex', []);

learnodex.service('environment', function () {
    return {
        isDebug: true
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
                if (environment.isDebug) {
                    console.log(data);
                }

                $scope.card = data;
            }
        });
    };
}]);
