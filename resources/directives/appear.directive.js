(function () {
    'use strict';
    //use: appear-target="pepe" effect="debounce" delay="500" this will happen when current element where direcive is used is seen
    angular.module('shared').directive('appearTarget', appearTarget);
    appearTarget.$inject = ['$timeout'];
    function appearTarget($timeout) {
        var directive = {
            link: link,
            scope: {
                effect: '@',
                delay: '='
            },
            restrict: 'A'
        };
        return directive;

        function link(scope, element, attrs) {
            if (scope.delay === undefined) {
                scope.delay = 0;
            }
            $(element).appear(function ()
            {
                $timeout(function () {
                    $(attrs.appearTarget).addClass(scope.effect);
                }, scope.delay);
            });
        }
    }
})();
