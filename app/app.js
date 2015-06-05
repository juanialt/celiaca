'use strict';

var celiacaApp = angular.module('celiacaApp', [
  'ngRoute',
  'login']);

celiacaApp.config(['$routeProvider',
  function($routeProvider) {
    console.log('route');
    $routeProvider.
      when('/login', {
        templateUrl: 'app/login/login.html',
        controller: 'loginCtrl'
      }).
      when('/phones/:phoneId', {
        templateUrl: 'app/login/login.html',
        controller: 'loginCtrl'
      }).
      otherwise({
        redirectTo: '/login'
      });
  }]);
