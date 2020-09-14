<template>
    <section class="messages">
        <div class="wrap-messages">
            <h2>Сообщения</h2>
            <div class="wrap-messages-content">
                <div class="messages-content-top">
                    <div class="messages-content-top-back" @click="redirect">
                        <a>
                            <div class="icon_back">
                                <img src="/img/arr_back.png" alt="">
                            </div>
                            <span>Назад</span>
                        </a>
                    </div>
                    <div class="messages-content-top-name">
                        <div class="content-top-name-user">
                            <span><a :href="'/user/' + friend.name">{{friend.name}}</a></span>
                        </div>
                        <div class="content-top-time">
                            <span>был в сети {{$moment(friend.last_online_at).tz('Europe/Moscow').fromNow()}}</span>
                        </div>
                    </div>
                    <div class="messages-content-top-user_icon" :style="'background-image: url(/storage/images/avatars/' + friend.avatar + ');'"></div>
                </div>
                <message-list :user="user" :all-messages="allMessages"></message-list>

                <div class="messages-content-foot" @keyup.enter="sendMessage">
                    <div class="detail-content-comments-add">
                        <ul class="wrap-files">
                            <li v-for="(item, index) in items" class="file">
                                <div @click="deleteMin(index)" class="delete" title="Удалить"></div>
                                <img :src="item.image" alt="">
                            </li>
                        </ul>
                        <div class="textarea" data-bool="1">
                            <div class="textarea-block maxlength">
                            <textarea class="textarea-block__textarea" maxlength="280"
                                      placeholder="Напишите сообщение" style="display: none;">

                            </textarea>
                                <div class="textarea-block-media">
                                    <div class="textarea-block-media_el">
                                        <div class="load_media">
                                            <label class="unselectable">
                                                <input @change="onFileChange($event)"
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
                        <div class="btn-green">
                            <a @click="sendMessage">Отправить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import MessageList from './_message-list'
    import ImgUpload from './imgUpload'
    import { Picker } from 'emoji-mart-vue'


    export default {
        props:['user', 'activeFriend', 'friend'],
        components:{
            Picker,
            MessageList,
            ImgUpload
        },

        data () {
            return {
                message:null,
                files:[],
                typingFriend:{},
                allMessages:[],
                typingClock:null,
                emoStatus:false,
                items: [{image:null}],
                token:document.head.querySelector('meta[name="csrf-token"]').content
            }
        },

        watch:{
            activeFriend(val){
                this.fetchMessages();
            },
            '$refs.upload'(val){
                console.log(val);
            }
        },

        methods:{
            onFileChange(e) {
                if (this.files.length < 2) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    if (files.length > 2) {
                        return;
                    }
                    if (files.length > 1) {
                        for (let file of files) {
                            this.files.push(file);
                            let len = this.items.push({image: null});
                            this.createImage(this.items[len - 1], file);
                        }
                    } else {
                        this.files.push(files[0]);
                        let len = this.items.push({image: null});
                        this.createImage(this.items[len - 1], files[0]);
                    }
                }
            },
            createImage(item, file) {
                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {
                    item.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            deleteMin(index) {
                delete this.items[index];
                this.items.splice(index, 1);
                delete this.files[index];
                this.files.splice(index, 1);
            },


            onTyping(){
                Echo.private('privatechat.'+this.activeFriend).whisper('typing',{
                    user:this.user
                });
            },
            handleInput(value){
                this.message = value;
            },
            sendMessage(){
                let formData = new FormData();
                this.message = $('.emojionearea-editor')[0].innerHTML;

                // console.log(this.message);
                formData.append('message', this.message);

                // if(!this.message){
                //     return alert('Please enter message');
                // }
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('files[' + i + ']', file, file.name);
                }

                axios.post('/private-messages/'+this.activeFriend,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    this.message=null;
                    this.files = [];
                    $('.emojionearea-editor')[0].innerHTML = '';
                    let  newMsg = response.data.message;
                    newMsg.images = response.data.images;
                    newMsg.user = this.user;
                    this.allMessages.push(newMsg);
                    setTimeout(this.scrollToEnd,100);
                    this.items = [{image: null}];
                    this.files = [];
                });
            },
            redirect() {
                window.location.href = '/dialogs';
            }
            ,
            fetchMessages() {
                axios.get('/private-messages/'+this.activeFriend).then(response => {
                    this.allMessages = response.data;
                    setTimeout(this.scrollToEnd,100);
                });
            },

            scrollToEnd(){
                document.getElementById('privateMessageBox').scrollTo(0,99999);
            },
            toggleEmo(){
                this.emoStatus= !this.emoStatus;
            },
            onInput(e){
                if(!e){
                    return false;
                }
                if(!this.message){
                    this.message=e.native;
                }else{
                    this.message=this.message + e.native;
                }
                this.emoStatus=false;
            },

            onResponse(e){
                console.log('onrespnse file up',e);
            }

        },

        mounted(){
            axios.get('/mark-messages/' + this.activeFriend).then(response => {
            });



            this.fetchMessages();

            Echo.private('privatechat.'+this.user.id)
                .listen('PrivateMessageSent',(e)=>{
                    console.log('pmessage sent')
                    this.activeFriend=e.message.user_id;
                    this.allMessages.push(e.message)
                    setTimeout(this.scrollToEnd,100);

                })
                .listenForWhisper('typing', (e) => {

                    if(e.user.id==this.activeFriend){

                        this.typingFriend=e.user;

                        if(this.typingClock) clearTimeout();

                        this.typingClock=setTimeout(()=>{
                            this.typingFriend={};
                        },5000);
                    }

                });
        }
    }
</script>

<style scoped>

    .messages{
        /*overflow-y:scroll;*/
        /*height:100vh;*/
    }

</style>
