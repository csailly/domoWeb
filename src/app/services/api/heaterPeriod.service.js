(function () {
  'use strict';

  angular.module('domoweb.services')
    .factory('heaterPeriodApiService', heaterPeriodApiService);

  function heaterPeriodApiService(NoCacheRestangular){
    var service = {
      getCurrent: getCurrent
    };

    return service;

    //--------------------
    function getCurrent(){
      return NoCacheRestangular.one('heaterPeriod/current').get()
        .then(function(data){
          return data;
        });
    }
  }

})();
