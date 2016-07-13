// This module contains the templates for all shared modules (see BundleConfig)
(function () {
    'use strict';
    
    angular.module('shared').run(runBlock);
    
    runBlock.$inject = ['$rootScope', '$window'];
    
    function runBlock($rootScope, $window) {
        // fired once the state transition is complete. (https://github.com/angular-ui/ui-router/wiki)
        $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {

            // APP INSIGHTS
            //insights.trackPageView(toState.url);

            // GOOGLE ANALYTICS
            //if ($window.ga)
              //  $window.ga('send', 'pageview', { page: toState.url });
            $rootScope.previousState = fromState;
        });
        
        /* Event - Window Scroll */
        $(window).scroll(function ()
        {
            var scroll = $(window).scrollTop();
            var height = $(window).height();

            /*** set sticky-navigation menu ***/
            if (scroll < 50)
            {
                $('.primary-navigation li a').removeClass('bgtransparent');
            }
            if (scroll >= 145)
            {
                $('.header-1, .header-2, .header-4 .header-4-inner, .menu-style-5').addClass("sticky-navigation");
            } else
            {
                $('.header-1, .header-2, .header-3 .menu-panel, .header-4 .header-4-inner, .menu-style-5').removeClass("sticky-navigation");
                $('.header-3 .menu-panel').addClass("no-sticky");
            }// set sticky-navigation menu - end

            /*** set sticky-navigation menu ***/
            if (scroll >= 600)
            {
                $('.header-3 .menu-panel').addClass("sticky-navigation");
                $('.header-3 .menu-panel').removeClass("no-sticky");
            } else
            {
                $('.header-3 .menu-panel').removeClass("sticky-navigation");
                $('.header-3 .menu-panel').addClass("no-sticky");
            }

            /* Back to Top */
            if ($(window).scrollTop() > 1000)
            {
                $("#back-to-top").fadeIn(1000);
            } else
            {
                $("#back-to-top").fadeOut(1000);
            }
        });
        /* Event - Window Scroll /- */
    }
})();


