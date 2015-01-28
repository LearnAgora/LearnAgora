var learnodex = angular.module('learnodex', []);

learnodex.service('environment', function () {
    return {
        isDebug: true
    };
});

learnodex.service('api', ['$http', function ($http) {
    var randomCard = function (data) {
        $http({method: 'GET', url: Routing.generate('la_learnodex_api_random_card')}).success(data.success);
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
