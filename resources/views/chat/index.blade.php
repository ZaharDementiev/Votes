@extends('layouts.app')

@section('content')
    <!--{{--<chat :user="{{ auth()->user() }}" :active-friend="{{ $user->id }} " :friend="{{$user}}"></chat>--}}-->

    <script>
        app.controller('pageCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http){
            $scope.moment = moment;
            $scope.friend = {!!json_encode($user)!!};
            $scope.files = [];
            $scope.allMessages = [];
            $scope.filter = 'flirt';
            
            $scope.getData = function(){
                $http({
                    url:'/chat/get_messages/'+$scope.friend.id
                }).then(function(ret){
                    $scope.allMessages = ret.data;
                    console.log($scope.allMessages);
                    setTimeout($scope.scrollToEnd,100);
                })
            }
            $scope.getData();
            
            $scope.initTexarea = function(block) {
                let maxlength = block.attr('maxlength');
                let el = $(block).emojioneArea({
                    search: false,
                    saveEmojisAs: 'shortname',
                    textcomplete: {

                    },
                    filters: {
                        recent: {
                            title: 'Часто используемые'
                        },
                        smileys_people: {
                            title: 'Эмоции и жесты'
                        },
                        animals_nature: {
                            title: 'Животные и растения'
                        },
                        food_drink: {
                            title: 'Еда'
                        },
                        activity: {
                            title: 'Спорт и активности'
                        },
                        travel_places: {
                            title: 'Путешествия и транспорт'
                        },
                        objects: {
                            title: 'Предметы'
                        },
                        symbols: {
                            title: 'Символы'
                        },
                        flags: {
                            title: 'Флаги'
                        },
                    }
                });                                
            }
            $scope.initTexarea($('.textarea-block'));
            setTimeout(function(){
                $('.emojionearea-editor')[0].innerHTML = '';
            });
            
            $scope.onFileChange = function(e) {
                if ($scope.files.length < 2) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    if (files.length > 2) {
                        return;
                    }
                    if (files.length > 1) {
                        for (let file of files) {
                            $scope.files.push(file);
                            let len = this.items.push({image: null});
                            $scope.createImage(this.items[len - 1], file);
                        }
                    } else {
                        $scope.files.push(files[0]);
                        let len = this.items.push({image: null});
                        $scope.createImage(this.items[len - 1], files[0]);
                    }
                }
            }
            
            $scope.createImage = function(item, file) {
                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {
                    item.image = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            $scope.deleteMin = function(index) {
                delete this.items[index];
                this.items.splice(index, 1);
                delete this.files[index];
                this.files.splice(index, 1);
            }


            $scope.onTyping = function(){
                Echo.private('privatechat.'+this.activeFriend).whisper('typing',{
                    user:this.user
                });
            }
            
            $scope.handleInput = function(value){
                this.message = value;
            }
            
            $scope.sendMessage = function(){
                data = new FormData();
                var message = $('.emojionearea-editor')[0].innerHTML;

                data.append('message', message);

                if( $scope.files.length){
                    for( var i = 0; i < $scope.files.length; i++ ){
                        let file = this.files[i];
                        data.append('files[' + i + ']', file, file.name);
                    }
                }
                
                $.ajax({
                    url:'/chat/send/'+$scope.friend.id,
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (json){
                        $('.emojionearea-editor')[0].innerHTML = '';
                        console.log(json.message);
                        $scope.allMessages.push(json.message);
                        if(json.status == 'error'){
                            //$rootScope.$broadcast('errorModal',{message:json.msg});
                        }else{
                            /*if(!json.data[0]){
                                $rootScope.$broadcast('errorModal',{message:"Ничего не найдено"});
                            }
                            location.href = '/face/person/?id='+json.data[0].id;*/
                        }                        
                        $scope.$digest();
                        setTimeout($scope.scrollToEnd,100);
                    }
                });
            }
            
            $scope.redirect = function() {
                window.location.href = '/dialogs';
            }
            
            /*$scope.fetchMessages = function() {
                axios.get('/private-messages/'+this.activeFriend).then(response => {
                    this.allMessages = response.data;
                    setTimeout(this.scrollToEnd,100);
                });
            }*/

            $scope.scrollToEnd = function(){
                document.getElementById('privateMessageBox').scrollTo(0,99999);
            }
            
            $scope.toggleEmo = function(){
                this.emoStatus= !this.emoStatus;
            }
            
            $scope.onInput = function(e){
                if(!e){
                    return false;
                }
                if(!this.message){
                    this.message=e.native;
                }else{
                    this.message=this.message + e.native;
                }
                this.emoStatus=false;
            }

            $scope.onResponse = function(e){
                console.log('onrespnse file up',e);
            }
        }]);
     </script>
     
       <div class="tape-tabs">
            <ul>
                <li ng-click="changeFilter('')" class="@{{filter=='' ? 'active':''}}"><a href="">Флирт</a></li>
                <li ng-click="changeFilter('online')" class="@{{filter=='online' ? 'active':''}}"><a href="">Вирт</a></li>                
            </ul>
        </div>
        
    <style>
        .emojionearea .emojionearea-editor{
            width:100%;
            height: 60px;
            min-height: auto;
            resize:none;
            overflow:hidden;
        }
    </style>
    
    <section class="messages" ng-controller="pageCtrl">
        <div class="wrap-messages">
            <h2>Сообщения</h2>
            <div class="wrap-messages-content">
                <div class="messages-content-top">
                    <div class="messages-content-top-back" ng-click="redirect()">
                        <a>
                            <div class="icon_back">
                                <img src="/img/arr_back.png" alt="">
                            </div>
                            <span>Назад</span>
                        </a>
                    </div>
                    <div class="messages-content-top-name">
                        <div class="content-top-name-user">
                            <span><a ng-href="/user/@{{friend.name}}">@{{friend.name}}</a></span>
                        </div>
                        <div class="content-top-time">
                            <span>был в сети @{{moment(friend.last_online_at).fromNow()}}</span>
                        </div>
                    </div>
                    <div class="messages-content-top-user_icon" style="background-image: url(/storage/images/avatars/@{{friend.avatar}});'"></div>
                </div>
                
                <!---MEssage Content Body--->
                <div class="messages-content-body" id="privateMessageBox" ng-cloak>
                    <div class="message-content-el" ng-repeat="message in allMessages">                        
                        <a href="/user/@{{message.user_id}}">
                        <div class="message-content-el-icon" style="background-image: url(/storage/images/avatars/@{{message.user_avatar}});'"></div></a>
                        <div class="message-content-el-body">
                            <div class="message-content-el-body-name">
                                <a href="/user/@{{message.user_id}}">
                                    <div class="message-content-el-icon message-content-el-icon-mobile" style="'background-image: url(/storage/images/avatars/@{{message.user_avatar}}"></div>                        
                                </a>
                                <div class="message-content-el-body-name-user">
                                    <span><a href="/user/@{{message.user_id}}">@{{message.user_name}}</a></span>
                                </div>
                                <div class="message-content-el-body-name-time">
                                    <span> @{{moment(message.created_at).fromNow()}}</span>
                                </div>
                            </div>
                            <div class="message-content-el-body-text">
                                <p ng-bind-html="message.message"></p>
                            </div>
                            <div class="wrap-files">
                                <a ng-repeat="image in message.images" href="/storage/images/chat/@{{image.path}}" data-fancybox="message-@{{message.id}}">
                                    <img src="/storage/images/chat/@{{image.path}}" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <form ng-submit="sendMessage()" class="messages-content-foot">
                    <div class="detail-content-comments-add">
                        <ul class="wrap-files">
                            <li v-for="(item, index) in items" class="file">
                                <div ng-click="deleteMin(index)" class="delete" title="Удалить"></div>
                                <img ng-src="item.image" alt="">
                            </li>
                        </ul>
                        
                        <div class="textarea" data-bool="1">
                            <div class="textarea-block maxlength">
                                <textarea class="textarea-block__textarea" maxlength="280" placeholder="Напишите сообщение" style="display: none;"></textarea>
                                          
                                <div class="textarea-block-media">
                                    <div class="textarea-block-media_el">
                                        <div class="load_media">
                                            <label class="unselectable">
                                                <input ng-change="onFileChange($event)" ng-model="file"
                                                       data-maxfiles="2"
                                                       type="file" ref="files" multiple
                                                       name="files[]" style="display:none;"/>
                                                <img src="/img/camera.svg" alt="">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-el__media">
                                <p>Осталось - <span class="count_limit">280</span></p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button class="btn btn-primary" ng-click="sendMessage">Отправить</button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
