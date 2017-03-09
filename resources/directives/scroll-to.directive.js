
(function () {
    'use strict';
    angular.module('shared').directive('scrollTo', scrollTo);

    function scrollTo() {
        var directive = {
            link: link,
            restrict: 'A',
            scope: {
                scrollToElem:'@',
                reduceOffset: '='
            }
        };

        return directive;

        function link(scope, element, attrs) {
            element.on('click', function () {
                $('html, body').animate({
                    scrollTop: $(scope.scrollToElem).offset().top - (scope.reduceOffset ? scope.reduceOffset : 0)
                }, 500);
            });
        }
    }
})();