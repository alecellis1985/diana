(function () {
    'use strict';

    angular.module('shared').factory('CommonService', CommonService);

    CommonService.$inject = ['$http', '$q'];//$upload

    function CommonService($http, $q) {//, $upload
        var commonService = {
            getRequest: getRequest,
            getRequestCustom: getRequestCustom,
            postRequest: postRequest,
            postJsonRequest: postJsonRequest
        };
        //postRequestWithFile: postRequestWithFile

        return commonService;

        function getRequest(requestUrl, params, canceller) {
            var deferred = $.Deferred(),
                    cancelTimeout = canceller || $q.defer();

            $http.get(requestUrl + (params != undefined ? '?' + $.param(params) : ''), {timeout: cancelTimeout.promise}).success(function (data) {
                deferred.resolve(data);
            });
            return deferred.promise();
        }

        function getRequestCustom(requestUrl, params, canceller) {
            var deferred = $.Deferred(),
                    cancelTimeout = canceller || $q.defer();
            var paramsget = params !== undefined ? '/' + $.param(params).replace('&', '/') : '';
            $http.get(requestUrl + paramsget, {timeout: cancelTimeout.promise}).success(function (data) {
                deferred.resolve(data);
            });
            return deferred.promise();
        }

        function postRequest(requestUrl, params) {
            var deferred = $.Deferred();
            $http.post(requestUrl, JSON.stringify(params)).success(function (data) {
                deferred.resolve(data);
            });
            return deferred.promise();
        }

        function postJsonRequest(requestUrl, params) {
            var deferred = $.Deferred();
            $http.post(requestUrl, params, {headers: {'Content-Type': 'application/json;charset=utf-8'}}).success(function (data) {
                deferred.resolve(data);
            });
            return deferred.promise();
        }

        /*function postRequestWithFile(requestUrl, params, file) {
         return $upload.upload({
         url: requestUrl,
         file: file,
         fields: params
         });
         }*/
    }
})();




