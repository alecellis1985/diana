(function () {
    'use strict';
    angular.module('shared').controller('alertCtrl', alertCtrl);

    alertCtrl.$inject = ['$scope', '$timeout'];

    function alertCtrl($scope, $timeout) {
        var vm = this;
        //Alert OBJS { type: 'danger', msg: 'Oh snap! Change a few things up and try submitting again.' }
        vm.alerts = [];
        vm.closeAlert = closeAlert;

        $scope.$on('alert-event', function (event, args) {
            $scope.addAlert(args);
            $timeout(function () {
                //NEED TO ADD THIS BECAUSE angular 1.3 adds to the element an ng hide
                $('.alertsTop button').removeClass('ng-hide');
            }, 5);
        });

        $scope.addAlert = function (alertElem) {
            if (alertElem.type === 'danger') {
                vm.alerts.forEach(function (alert, index) {
                    if (alert.type === 'danger')
                        vm.closeAlert(index);
                });
            }
            vm.alerts.push(alertElem);
            var alertIndex = vm.alerts.length - 1;
            $timeout(function () {
                //NEED TO ADD THIS BECAUSE angular 1.3 adds to the element an ng hide
                vm.closeAlert(alertIndex);
            }, 5000);
        };

        function closeAlert(index) {
            vm.alerts.splice(index, 1);
        }
    }
});