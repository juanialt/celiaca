'use strict';

var celiacaApp = angular.module('celiacaApp', [
  'ngRoute',
  'login',
  'navigation']);

celiacaApp.config(['$routeProvider', function($routeProvider) {
  $routeProvider.
    when('/login', {
      templateUrl: 'app/login/login.html',
      controller: 'loginCtrl'
    }).
    when('/phones/:phoneId', {
      templateUrl: 'app/login/login.html',
      controller: 'loginCtrl'
    }).
    when('/home', {
      templateUrl: 'app/home/home.html',
      controller: 'loginCtrl',
      resolve: {
        auth: ["$q", function($q) {
          var userInfo = false;
          var deferred = $q.defer();

          if (userInfo) {
            console.log('login OK');
            deferred.resolve(true);
          } else {
            console.log('login ERROR');
            deferred.reject(false);
          }

          return deferred.promise;
        }]
      }
    }).
    when('/user', {
      templateUrl: 'app/user/user.html',
      controller: 'loginCtrl'
    }).
    when('/brand', {
      templateUrl: 'app/brand/brand.html',
      controller: 'loginCtrl'
    }).
    when('/categories', {
      templateUrl: 'app/categories/categories.html',
      controller: 'loginCtrl'
    }).
    otherwise({
      redirectTo: '/login'
    });
}]);


celiacaApp.run(["$rootScope", "$location", function($rootScope, $location) {
  $rootScope.$on("$routeChangeSuccess", function(userInfo) {
    console.log('route change');
    console.log(userInfo);
  });

  $rootScope.$on("$routeChangeError", function(event, current, previous, eventObj) {
    console.log(eventObj);
    if (eventObj === false) {
      console.log('route change error!');
      $location.path('/login');
    }
  });
}]);