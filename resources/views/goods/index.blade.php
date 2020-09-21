@extends('layouts/app')
@section('content')    
    <script>
        app.controller('pageCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {            
            $scope.data = [];
            $scope.$moment = moment;
            
            $scope.files = [];
            
            $scope.setActive = function(event){
                var parent = $(event.target).closest('.settings-content-el');

                if(parent.hasClass('active')){
                    parent.removeClass('active');
                }else{
                    parent.addClass('active');
                }
            }
            
            $scope.getData =  function(){
                $http({
                    url:'/goods/get_data?page='+$scope.page
                }).then(function(ret){
                    $scope.data = ret.data;
                });
            }
            $scope.getData();
            
            $scope.activateImageLoad = function (event){
                event.stopPropagation();
                event.preventDefault();
                
                $('#loadimage').click();
            }

            $('#loadimage').change(function(){
                files = $('#loadimage[type=file]')[0].files;

                if (files && files[0]){
                    var image = new Image();
                    var reader = new FileReader();
                    
                    /*reader.onload = function (e) {
                        $scope.images.push(e.target.result);
                        
                        $scope.$digest();
                    }*/

                    reader.readAsDataURL(files[0]);                    
                }
                
                $scope.files.push(files[0]);
                
                $scope.submit();

                $scope.$digest();
            });
            
            $scope.submit = function(){
                var data = new FormData();

                if ($scope.files){
                    $.each($scope.files, function (key, value) {
                        data.append('files[' + key + ']', value,value.name);
                    });
                }
                
                $.ajax({
                    url:'/goods/load_image',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (json){
                        console.log(json);
                    }
                });
            }
        }]);
    </script>

    <style>
        .settings-content-el.active .settings-content-el-body{
            display:block;
        }
    </style>
    
    <div class='' ng-controller="pageCtrl">
        <div class='form-group'>
            <div  class='btn btn-primary'>Добавить видео</div>
            
            <div ng-click="activateImageLoad($event)" class='btn btn-primary'>Добавить фото</div>
            <input type="file" style="display:none;" id="loadimage"  multiple="multiple" accept=".txt,image/*">
        </div>
        
        <div class="settings-content-el">
            <div class="settings-content-el-top unselectable" ng-click="setActive($event)">
                <div class="settings-content-el-left">
                    <div class="settings-content-el-left-icon">
                        <img src="/img/settings/pass.svg" alt="">
                    </div>
                    <span>Изображения</span>
                </div>
                <div class="settings-content-el-right">
                    <div class="arr_settigns">
                        <img src="/img/arr_blue.svg" alt="">
                    </div>
                </div>
            </div>

            <div class="settings-content-el-body">
               <div class='row'>
                   <div class='col-sm-6' ng-repeat="item in data" ng-if='item.type == 1'>
                       <img src='/storage/goods/images/@{{item.file}}'>
                   </div>
               </div>
            </div>
        </div>
        
        <div class="settings-content-el">
            <div class="settings-content-el-top unselectable" ng-click="setActive($event)">
                <div class="settings-content-el-left">
                    <div class="settings-content-el-left-icon">
                        <img src="/img/settings/pass.svg" alt="">
                    </div>
                    <span>Видео</span>
                </div>
                <div class="settings-content-el-right">
                    <div class="arr_settigns">
                        <img src="/img/arr_blue.svg" alt="">
                    </div>
                </div>
            </div>

            <div class="settings-content-el-body">
               <div class='row'>
                   <div class='col-sm-6' ng-repeat="item in data" ng-if='item.type == 2'>
                       <img src='/storage/goods/images/@{{item.file}}'>
                   </div>
               </div>
            </div>
        </div>
    </div>
@endsection