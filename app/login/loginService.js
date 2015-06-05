'use strict';

var loginServices = angular.module('loginServices', []);

loginServices.factory('loginService', ['$http', function($http){

  function login (user, password) {
    $http.post('../server/login/login.php', {user:user, password:password}).
    success(function(data, status, headers, config) {
      console.log('LOGIN SUCCESS');
    }).
    error(function(data, status, headers, config) {
      console.log('LOGIN ERROR');
    });
  }

  return {
    login : login
  }

}]);
