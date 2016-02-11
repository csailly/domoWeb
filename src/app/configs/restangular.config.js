(function () {
  'use strict';

  angular.module('domoweb.configs')
    .config(restangularConfig);

  function restangularConfig(RestangularProvider, appConfig) {
    RestangularProvider.setBaseUrl(appConfig.apiBaseUrl);
    RestangularProvider.setDefaultHttpFields({cache: true});

    RestangularProvider.addRequestInterceptor(function (element) {
      console.log("Request started");
      return element;
    });

    RestangularProvider.addResponseInterceptor(function (data) {
      console.log("Request returned");
      return data;
    });

    RestangularProvider.setErrorInterceptor(function (response, deferred, responseHandler) {
      console.log("XHR Error :", response.status);
      return true; // error not handled
    });
  }

})();
