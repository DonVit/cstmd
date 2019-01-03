var app = angular.module('myApp', []);
app.controller('customersCtrl', function($scope, $http, $sce) {
    $http.get("fm.php").success(function (response) {$scope.names = response;});
    
    $scope.play = function(x,l) {
    	 console.log(l);
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


app.filter("trustUrl", ['$sce', function ($sce) {
	return function (recordingUrl) {
	return $sce.trustAsResourceUrl(recordingUrl);
	};
	}]);
app.directive('plaything', function() {
	   return {
	       restrict: 'A',
	       link: function(scope, elem, attr, ctrl) {
	            elem.bind('onprogress1', function(e) {
	                /* do something here */
	                window.alert('test');
	            });
	            elem.bind('onloadstart', function(e) {
	                /* do something here */
	                window.alert('test');
	            });
	            elem.bind('onclick', function(e) {
	                /* do something here */
	                window.alert('test');
	            });
	       }
	   };
	});
app.directive('fmplayer', function($sce) {
	  return {
		replace: 'true',
		template: function(elem,attrs) {return '<audio controls><source data-ng-src="'+$sce.trustAsResourceUrl(attrs.testparam)+'" type="audio/mpeg"></audio>'}
		}
	});
app.directive('h1test', function($sce) {
	  return {
		replace: 'true',
		template: function(elem,attrs) {return '<h1>Test H1 '+attrs.testparam+'</h1>'},
	    link: function(scope,elem,attrs){
	      elem.bind('click',function(){
	        elem.css('background-color','red');
	        scope.$apply(function(){
	          scope.color="red";
	          console.log('test');
	          console.log(attrs.testparam);
	          console.log(scope.trusted(attrs.testparam));
	        });
	      });
	      elem.bind('mouseover',function(){
	        elem.css('cursor','pointer');
	      });
	    }			
	  };
	});