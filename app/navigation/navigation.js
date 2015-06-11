'use strict';

var navigation = angular.module('navigation', []);

navigation.controller('navigationCtrl', ['$scope', function($scope) {

}]);

navigation.directive('navigation', function() {
  return {
  	restrict: 'E',
    templateUrl: './app/navigation/navigation.html'
  };
});