@extends('layouts.app')

@section('content')
     <script>
        app.controller('pageCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {
            $scope.data = [];
            $scope.page = 1;
            $scope.filter = '{{$filter}}';
            //console.log($scope.data);
            
            $scope.changeFilter = function(filter){                                
                if($scope.filter == filter) return;
                $scope.page = 1;
                $scope.filter = filter;
                history.pushState({ 'page_id': 1 }, '', '/virt/'+filter);
                $scope.data = [];
                $scope.getData();
            }
            
            $scope.getData = function(){
                $http({
                    url:'/virt/get_data?page='+$scope.page+'&filter='+$scope.filter
                }).then(function(ret){
                    if(!$scope.data.length){
                        $scope.data = ret.data;
                        return;
                    }
                    
                    for(var key in ret.data){
                        $scope.data.push(ret.data[key]);
                    }
                })
            }
            $scope.getData();
             
            $scope.loadMore = function(event){
                 $scope.page +=1;
                 $scope.getData();
            }
        }]);
    </script>
    
    <style>
        .item{
            display:block;
            text-align: center;
            margin-bottom: 30px;
        }
        .item .image_block{
            width: 140px;
            height: 140px;
            border-radius: 70px;            
        }
        .item:hover{
            text-decoration: none;
            color: black;
        }
    </style>
    
    <section ng-controller="pageCtrl">
         <div class="tape-tabs">
            <ul>
                <li ng-click="changeFilter('')" class="@{{filter=='' ? 'active':''}}"><a href="">Все</a></li>
                <li ng-click="changeFilter('online')" class="@{{filter=='online' ? 'active':''}}"><a href="">Онлайн</a></li>                
            </ul>
        </div>
        
        <div class="list_block">
            <div class="row">
                <div class="col-sm-3" ng-repeat="item in data">
                    <a href='/user/@{{item.name}}' class="item" ng-cloak>
                        <div class="image_block" style='background:url(/storage/images/avatars/@{{item.avatar}}) no-repeat; background-size: cover;'></div>
                                                    
                        @{{item.name}}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="btn-green tapes-else">
            <a ng-click="loadMore($event)">Смотреть еще <img src="/img/tape/else_icon.svg" alt=""></a>
        </div>
    </section>
@endsection
