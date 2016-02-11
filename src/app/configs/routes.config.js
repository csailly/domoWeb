(function () {
  'use strict';

  angular.module('domoweb.configs').config(routesConfig);

  function routesConfig($stateProvider, $urlRouterProvider) {
    var DEFAULT_STATE_NAME = 'root.default';

    $urlRouterProvider.when('/', '/default');
    $stateProvider.state('root', {
      abstract: true,
      url: '/',
      views: {
        '': {
          templateUrl: 'app/views/main/main.html',
          controller: 'MainController',
          controllerAs: 'mainController'
        }
      }
    }).state(DEFAULT_STATE_NAME, {
      url: 'default'
    });

    // Redirection to the default state using the injector to avoid
    // 'Error: [$rootScope:infdig] 10 $digest() iterations reached.
    // Aborting!'
    // Issue from the ui-router:
    // https://github.com/angular-ui/ui-router/issues/1022#issuecomment-76428798
    $urlRouterProvider.otherwise(function ($injector) {
      var $state = $injector.get('$state');
      $state.go(DEFAULT_STATE_NAME);
    });

  }
})();
