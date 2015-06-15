'use strict';

var celiacaApp = angular.module('celiacaApp', [
  'ngRoute',
  'angular-storage',
  'angular-jwt',
  'login',
  'home',
  'navigation']);

celiacaApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

  $httpProvider.interceptors.push('sessionInjector');

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
      controller: 'homeCtrl'
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


celiacaApp.factory('sessionInjector', function() {
  var responseInterceptor = {
    response: function(response) {
      console.log('SERVER RESPONSE');
      console.log(response);
      return response;
    },
    responseError: function(rejection) {
      console.log('SERVER REJECTION');
      console.log(rejection);
      return rejection;
    }
  };

  return responseInterceptor;
});