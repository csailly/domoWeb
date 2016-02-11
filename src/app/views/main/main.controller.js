(function () {
  'use strict';

  angular.module('domoweb.views')
    .controller('MainController', MainController);

  function MainController(profilApiService, heaterModeApiService, stoveApiService, heaterPeriodApiService) {
    var vm = this;

    vm.updateConfig = updateConfig;
    vm.activateProfil = activateProfil;

    activate();

    //----------

    function activate() {
      profilApiService.getAllProfil()
        .then(function (profils) {
          vm.profils = profils;
          getCurrentProfil();
        });

      heaterModeApiService.getAllMode()
        .then(function (modes) {
          vm.modes = modes;
        });

      getCurrentConfig();
      getCurrentPeriod();
    }

    function updateConfig(newConfig) {
      stoveApiService.updateConfig(newConfig)
        .then(function () {
          vm.currentConfig = newConfig;
        });
    }

    function activateProfil(id) {
      profilApiService.activateProfil(id)
        .then(function () {
          getCurrentPeriod();
          getCurrentProfil();
        });
    }


    function getCurrentPeriod() {
      heaterPeriodApiService.getCurrent()
        .then(function (current) {
          vm.currentPeriod = current;
        });
    }

    function getCurrentConfig() {
      stoveApiService.getConfig()
        .then(function (config) {
          vm.currentConfig = config.value;
        });
    }

    function getCurrentProfil() {
      profilApiService.getCurrentProfil().then(function (profil) {
        vm.currentProfil = profil;
      });
    }

  }

})
();
