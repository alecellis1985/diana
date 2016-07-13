(function () {
    'use strict';
    angular.module('shared').controller('homeCtrl', homeCtrl);
    homeCtrl.$inject = [];
    function homeCtrl() {
        var vm = this;
        vm.howitworksarr = [{
                subtitle: 'Fast',
                paragraph: 'Im a paragraph. Click here to add your own text and edit me. It’s easy. Just click “Edit Text” or double click me to add your own content and make changes to the font. Feel free to drag and drop me anywhere you like on your page. I’m a great place for you to tell a story and let your users know a little more about you.'
            },
            {
                subtitle: 'Secure',
                paragraph: 'Im a paragraph. Click here to add your own text and edit me. It’s easy. Just click “Edit Text” or double click me to add your own content and make changes to the font. Feel free to drag and drop me anywhere you like on your page. I’m a great place for you to tell a story and let your users know a little more about you.'
            }, {
                subtitle: 'Easy',
                paragraph: 'Im a paragraph. Click here to add your own text and edit me. It’s easy. Just click “Edit Text” or double click me to add your own content and make changes to the font. Feel free to drag and drop me anywhere you like on your page. I’m a great place for you to tell a story and let your users know a little more about you.'
            }
        ];
    }
})();