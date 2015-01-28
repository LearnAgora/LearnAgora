var learnodex = angular.module('learnodex', []);

learnodex.controller('LearnodexController', ['$scope', '$http', function ($scope, $http) {
    $scope.newCard = function () {
        $http({method: 'GET', url: '/app_dev.php/api/ln/cards/random'})
            .success(function (data) {
                console.log(data);
                $scope.card = data;
            }
        );
    };
}]);
