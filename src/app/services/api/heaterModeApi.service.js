(function () {
  'use strict';

  angular.module('domoweb.services')
    .factory('heaterModeApiService', heaterModeApiService);
  function heaterModeApiService(Restangular) {
    var service = {
      getAllMode: getAllMode
    };

    return service;

    //------------------------
    function getAllMode() {
      return Restangular.all('heaterMode').getList().then(function (modes) {
        return modes;
      });
    }
  }

})();
