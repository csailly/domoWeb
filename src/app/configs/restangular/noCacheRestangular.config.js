(function () {
  'use strict';

  angular
    .module('domoweb.configs')
    .factory('NoCacheRestangular', NoCacheRestangular);

  function NoCacheRestangular(Restangular, appConfig) {
    return Restangular.withConfig(function (RestangularConfigurer) {
      RestangularConfigurer.setBaseUrl(appConfig.apiBaseUrl);
      RestangularConfigurer.setDefaultHttpFields({cache: false});
    });
  }
})();
