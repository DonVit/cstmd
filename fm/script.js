angular.module('script', ['ngRoute'])

.config(function($routeProvider) {
  $routeProvider
    .when('/', {
      controller:'CustomersCtrl as custCtrl',
      templateUrl:'list.html'
    })
	
    .when('/play/:projectId', {
      controller:'ProjectListController as listCtrl',
      templateUrl:'detail.html'
    })
    .otherwise({
      redirectTo:'/'
    });
})

.controller('ProjectListController', function($scope, $http, $sce) {
  
	//var custCtrl=this;
	//var names=[];
	//custCtrl.names=[];
	console.log('ProjectListController');
    //$http.get("http://fm.casata.it/fm.php").success(function (response) {$scope.names = response;});  
	
})

.controller('EditProjectController', function(projects) {
  console.log(test);
})

.filter("trustUrl", ['$sce', function ($sce) {
	return function (recordingUrl) {
	return $sce.trustAsResourceUrl(recordingUrl);
	};
}])
.controller('CustomersCtrl', function($scope, $http, $sce) {
	//var custCtrl=this;
	console.log('test');
    $http.get("http://fm.casata.it/fm.php").success(function (response) {$scope.names = response;});
    
    $scope.play = function(x,l) {    	 
        var aud = document.getElementById("aud_"+x.id);
        var audsrc = document.getElementById("audsrc_"+x.id);
        var audspn = document.getElementById("audplayspn_"+x.id);
        var rowid = document.getElementById("row_"+x.id);

        if (aud.paused) {

            $scope.names.forEach(function(entry) {
            	if (document.getElementById("aud_"+entry.id) !== null) {
            		document.getElementById("aud_"+entry.id).src="";
            		document.getElementById("row_"+entry.id).className="";
            		document.getElementById("audplayspn_"+entry.id).className="glyphicon glyphicon-play";
            	}
            });        	
        	
         aud.src=x.stream_url;
         aud.play();
         rowid.className ="warning";
         audspn.className="glyphicon glyphicon-pause";
         aud.onprogress = function() {
         };         
         aud.onplaying = function() {
        	 rowid.className ="success";
         };
         aud.onerror = function() {
        	 rowid.className ="";
         };                  
        } else {
            aud.pause();
            aud.preload="none";
            audspn.className="glyphicon glyphicon-play";
            rowid.className ="warning";
            aud.src = '';
            aud.onpause = function() {
            	rowid.className ="";
            };            
        }
    }
});