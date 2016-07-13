(function () {
    'use strict';
    angular.module('shared').config(['$stateProvider', '$urlRouterProvider', '$urlMatcherFactoryProvider', 'blockUIConfig', '$translateProvider',
        function ($stateProvider, $urlRouterProvider, $urlMatcherFactoryProvider, blockUIConfig, $translateProvider) {


            $translateProvider.translations('en', translationsEN);
            $translateProvider.translations('es', translationsES);
            $translateProvider.preferredLanguage('es');
            $translateProvider.useSanitizeValueStrategy('escape');

            // Reference: http://angular-ui.github.io/ui-router/site/#/api/ui.router.util.$urlMatcherFactory
            $urlMatcherFactoryProvider.caseInsensitive(true);
            $urlMatcherFactoryProvider.strictMode(false);

            setupBlockUI(blockUIConfig);

            
            //
            // Now set up the states
            $stateProvider
                    .state('index', {
                        url: '/index',
                        templateUrl: 'resources/widgets/homepage.html',
                        controller: 'homeCtrl',
                        controllerAs: 'vm'
                    });
                    
                    //
            
            $urlRouterProvider.otherwise("/index");
        }]);
    /*
     * .state('state2', {
     url: "/state2",
     templateUrl: "partials/state2.html"
     })
     .state('state2.list', {
     url: "/list",
     templateUrl: "partials/state2.list.html",
     controller: function($scope) {
     $scope.things = ["A", "Set", "Of", "Things"];
     }
     });
     */

    // Reference: https://github.com/McNull/angular-block-ui
    function setupBlockUI(blockUIConfig) {
        blockUIConfig.delay = 0;
        blockUIConfig.autoBlock = false;
    }
})();