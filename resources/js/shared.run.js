// This module contains the templates for all shared modules (see BundleConfig)
(function () {
    'use strict';

    angular.module('shared').run(runBlock);

    runBlock.$inject = ['$rootScope', '$window'];

    function runBlock($rootScope, $window) {
        $('.alertsTop').removeClass('hideAll');
        // fired once the state transition is complete. (https://github.com/angular-ui/ui-router/wiki)
        $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {

            // APP INSIGHTS
            //insights.trackPageView(toState.url);

            // GOOGLE ANALYTICS
            //if ($window.ga)
            //  $window.ga('send', 'pageview', { page: toState.url });
            $rootScope.previousState = fromState;
        });
    }
})();


