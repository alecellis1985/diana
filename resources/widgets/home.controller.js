(function () {
    'use strict';
    angular.module('shared').controller('homeCtrl', homeCtrl);
    homeCtrl.$inject = ['$translate'];
    function homeCtrl($translate) {
        var vm = this;
        
        vm.changeLanguage = function (langKey) {
            $translate.use(langKey);
        };
        
        vm.howitworksarr = [{
                img:'',
                title: 'Grow your business',
                paragraph: 'We offer high quality cost effective service, allowing you to take in more work load.'
            },
            {
                img:'resources/images/icons/knowhow.png',
                title: 'Know How',
                paragraph: 'Despite of being a foreign company, we have the required technical knowledge regarding building code, construction method, work methodology and cultural parameters of your country.'
            }, {
                img:'resources/images/icons/tw.png',
                title: 'EXCELLENT TEAMWORK',
                paragraph: 'Our team includes trained professionals, with adaptation to change, excellent level of English language and great knowledge of design tools.'
            }, {
                img:'resources/images/icons/comunication.png',
                title: 'GREAT COMMUNICATION',
                paragraph: 'Distance is only theoretical. We provide constant communication by e-mail, online meetings, phone calls, conference calls. We can assure full availability during your working hours, as the time difference is almost unnoticeable.'
            }, {
                img:'resources/images/icons/worldx2.png',
                title: 'OPTIMAL CONNECTION',
                paragraph: 'Uruguay is one of the countries with best internet downloading speed, which is a great benefit when it comes to communication and file sharing.'
            }
        ];
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
        
        init();
        //Functions *
        
        function init(){
            
        }
    }
})();