'use strict';

var login = angular.module('login', []);


login.controller('loginCtrl', ['$scope', 'loginService', function($scope, loginService) {
  console.log('here login!');
  $scope.page = 'LOGIN PAGE!';

  $scope.login = function(user, password) {
    console.log('Login requested');
    console.log('user = ' + user);
    console.log('password = ' + password);

    loginService.login(user, password);
  };
}]);


login.factory('loginService', ['$http', function($http){

  function login (user, password) {
    $http.post('./server/login/login.php', {user:user, password:password}).
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
