(function () {
  'use strict';

  angular.module('domoweb.services')
    .factory('stoveApiService', stoveApiService);

  function stoveApiService(NoCacheRestangular) {
    var service = {
      updateConfig: updateConfig,
      getConfig: getConfig
    };

    return service;

    //------------------------
    function updateConfig(newConfig) {
      return NoCacheRestangular.one('stove/configuration').customPUT({config: newConfig}).then(function (data) {
        return data;
      });
    }

    function getConfig() {
      return NoCacheRestangular.one('stove/configuration').get().then(function (data) {
        return data;
      });
    }
  }

})();
