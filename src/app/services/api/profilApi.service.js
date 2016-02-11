(function () {
  'use strict';

  angular.module('domoweb.services')
    .factory('profilApiService', profilApiService);
  function profilApiService(Restangular, NoCacheRestangular) {
    var service = {
      getAllProfil: getAllProfil,
      getCurrentProfil: getCurrentProfil,
      activateProfil: activateProfil
    };

    return service;

    //------------------------
    function getAllProfil() {
      return Restangular.all('profil').getList().then(function (profils) {
        return profils;
      });
    }

    function getCurrentProfil() {
      return NoCacheRestangular.one('profil/current').get().then(function (profil) {
        return profil;
      });
    }

    function activateProfil(id){
       return NoCacheRestangular.one('profil',id).one('activate').put().then(function(data){
         return data;
       });
    }

  }

})();
