/**
 * Blocks JavaScript
 *
 * @author kteraguchi@commonsnet.org (Kohei Teraguchi)
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */
NetCommonsApp.controller('BlocksController', function($scope, dialogs, $http) {
  /* frame setting START */

  $scope.orderByField = 'block.name';
  $scope.isOrderDesc = false;

  $scope.parseDate = function(d) {
    rep = d.replace(/-/g, '/');
    var date = Date.parse(rep);
    return new Date(date);
  };

  $scope.orderBlock = function(field) {
    $scope.isOrderDesc =
        ($scope.orderByField === field) ? !$scope.isOrderDesc : false;
    $scope.orderByField = field;
  };

  $scope.setBlock = function(frameId, blockId) {
    $http.post('/frames/frames/setBlock/' + frameId + '/' + blockId)
      .success(function(data, status, headers, config) {
          $scope.flash.success(data.name);
        })
      .error(function(data, status, headers, config) {
          $scope.flash.danger(data.name);
        });
  };

  $scope.showCalendar = function($event, type) {
    $event.stopPropagation();
    if (type === 'from') {
      $scope.isFrom = !$scope.isFrom;
    } else if (type === 'to') {
      $scope.isTo = !$scope.isTo;
    }
  };
})
.config(function(datepickerConfig, datepickerPopupConfig) {
      angular.extend(datepickerConfig, {
        formatMonth: 'yyyy / MM',
        formatDayTitle: 'yyyy / MM',
        showWeeks: false
      });
      angular.extend(datepickerPopupConfig, {
        datepickerPopup: 'yyyy/MM/dd HH:mm',
        showButtonBar: false
      });

      /* frame setting E N D */
    });
