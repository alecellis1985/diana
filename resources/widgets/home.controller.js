(function () {
    'use strict';
    angular.module('shared').controller('homeCtrl', homeCtrl);
    homeCtrl.$inject = ['$translate', '$timeout', '$rootScope', 'CommonService'];
    function homeCtrl($translate, $timeout, $rootScope, CommonService) {
        var vm = this;
        vm.goToSection = goToSection;
        vm.changeLanguage = function (langKey) {
            $translate.use(langKey);
        };
        vm.showHowItWorks = showHowItWorks;

        vm.show = false;
        vm.hovered = false;
        vm.mokArr = [{
                isCollapsed: true,
                img: '',
                title: 'Grow your business',
                paragraph: 'We offer high quality cost effective service, allowing you to take in more work load.'
            },
            {
                isCollapsed: true,
                img: 'resources/images/icons/knowhow.png',
                title: 'Know How',
                paragraph: 'Despite of being a foreign company, we have the required technical knowledge regarding building code, construction method, work methodology and cultural parameters of your country.'
            }, {
                isCollapsed: true,
                img: 'resources/images/icons/tw.png',
                title: 'EXCELLENT TEAMWORK',
                paragraph: 'Our team includes trained professionals, with adaptation to change, excellent level of English language and great knowledge of design tools.'
            }, {
                isCollapsed: true,
                img: 'resources/images/icons/comunication.png',
                title: 'GREAT COMMUNICATION',
                paragraph: 'Distance is only theoretical. We provide constant communication by e-mail, online meetings, phone calls, conference calls. We can assure full availability during your working hours, as the time difference is almost unnoticeable.'
            }, {
                isCollapsed: true,
                img: 'resources/images/icons/worldx2.png',
                title: 'OPTIMAL CONNECTION',
                paragraph: 'Uruguay is one of the countries with best internet downloading speed, which is a great benefit when it comes to communication and file sharing.'
            }];
        vm.howitworksarr = [];

        vm.clients = [{
                src: '/resources/images/image.png',
                alt: 'Architecture'
            },
            {
                src: '/resources/images/img.jpg',
                alt: 'Architecture'
            }, {
                src: '/resources/images/img2.jpg',
                alt: 'Architecture'
            }];

        vm.myInterval = 3000;
        vm.active = 0;
        vm.extension = '.png';
        vm.noWrapSlides = false;
        vm.slides = [{
                image: '/resources/images/carusel1',
                title: 'WHAT IS PLANB?',
                text: 'We are your strategic ally for the development of your projects',
                id: 0
            },
            {
                image: '/resources/images/carusel2',
                title: 'Reduce your company expenses',
                text: 'Close more deals and reduce operational costs, maintaining high quality.',
                id: 1
            },
            {
                image: '/resources/images/carusel3',
                title: 'Increase your production',
                text: 'Just focus on the core business while we provide the support you need.',
                id: 2
            }
        ];

        vm.timeout = null;
        vm.outOfFlip = false;
        vm.flipInX = flipInX;
        vm.flipOutX = flipOutX;

        function flipInX() {
            vm.hovered = true;
            vm.outOfFlip = false;
            console.log('inX' + vm.hovered);
        }

        function flipOutX() {
            vm.outOfFlip = true;
            if (vm.timeout !== null) {
                $timeout.cancel(vm.timeout);
            }
            vm.timeout = $timeout(function () {
                if (vm.outOfFlip) {
                    vm.hovered = false;
                    console.log('outX' + vm.hovered);
                }
            }, 1000);
        }

        vm.flipped = false;

        vm.flip = function () {
            vm.flipped = !vm.flipped;
        };


        function showHowItWorks() {
            var length = vm.mokArr.length;
            for (var i = 0; i < length; i++) {
                $timeout(function () {
                    vm.howitworksarr.push(vm.mokArr.shift());
                }, i + 1 * 200);
            }

        }

        function goToSection(id) {
            $('html, body').animate({
                scrollTop: $('#' + id).offset().top
            }, 500);
        }

        function resizeSectionsScreen() {
            //var marginTop = $('header').height();
            //var windowHeight = $(window).height();
            //var lpmainHeight = $(window).height() - $('header').height();
            //$('#lp-main').css({"height": lpmainHeight + 'px', "margin-top": marginTop + 'px'});
            //$timeout(function () {


            //$('.customCarousel').css({"height": lpmainHeight + 'px'});
            //$('.customCarousel .carousel').css({"max-height": lpmainHeight + 'px'});


            //});

            //$('section').height(windowHeight);
        }

        vm.user = {};

        vm.sendEmail = function (isValid) {
            if (!isValid) {
                $rootScope.$broadcast('alert-event', {type: 'danger', msg: "Existen errores en el formulario!"});
                return;
            }

            CommonService.postJsonRequest('api/sendMail', vm.user).then(function (result) {
                if (result.data.success)
                    $rootScope.$broadcast('alert-event', {type: 'success', msg: 'Has sido registrado con exito'});
                else
                    $rootScope.$broadcast('alert-event', {type: 'danger', msg: result.data.msg});
            });
        };

        init();
        //Functions *

        function init() {
            resizeSectionsScreen();

        }
    }
})();