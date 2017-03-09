/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function () {
    'use strict';

    angular.module('shared').controller('headerController', headerController);
    headerController.$inject = ['$timeout'];
    function headerController($timeout) {
        var vm = this;
        vm.showLogo = showLogo;
        vm.showLogo1 = false;
        vm.collapsed = false;
        function showLogo() {
            $timeout(function () {
                vm.showLogo1 = true;
            }, 200);
        }

    }
})();
