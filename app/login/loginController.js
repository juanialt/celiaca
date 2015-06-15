'use strict';

var login = angular.module('login', []);


login.controller('loginCtrl', ['$scope', '$location', 'loginService', 'store', function($scope, $location, loginService, store) {
  console.log('here login!');
  $scope.page = 'LOGIN PAGE!';

  $scope.login = function(user, password) {
    console.log('Login requested');
    console.log('user = ' + user);
    console.log('password = ' + password);

    loginService.login(user, password).then(
      function() {
        console.log('success bro! welcome to the app');
        $location.path('/home');
      },

      function() {
        alert('error!');
      });
  };
}]);


login.factory('loginService', ['$http', '$q', function($http, $q){

  function login (user, password) {
    var deferred = $q.defer();

    $http.post('./server/login/login.php', {user:user, password:password}).
    success(function(data, status, headers, config) {
      console.log('LOGIN SUCCESS');
      deferred.resolve(data);
    }).
    error(function(data, status, headers, config) {
      console.log('LOGIN ERROR');
      deferred.reject(data);
    });

    return deferred.promise;
  }

  return {
    login : login
  }

}]);
