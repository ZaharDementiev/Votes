<template>
    <li :id="'comment-'+ comment.id" :class="'media comment ' + replyClass(comment) ">

    <div class="wrap-comment">
            <div class="wrap-comment-top">
                <div class="comment-top-left">
                    <div v-if="comment.commenter_id == post.user_id && post.is_anonimous" class="comment-top-user">
                        <div class="user_activity_img"
                             style="background-image: url(/storage/images/avatars/default.jpg);">
                        </div>
                        <div class="user_activity_name">
                            <span><a :name="'comment-' + comment.id">Аноним</a></span>
                        </div>
                    </div>
                    <div v-else class="comment-top-user">
                        <div class="user_activity_img"
                             :style="'background-image: url(/storage/images/avatars/' + comment.commenter.avatar + ');'">
                        </div>
                        <div class="user_activity_name">
                            <span>
                                <a :name="'comment-'+ comment.id" :href="'/user/' + comment.commenter.name">
                                    {{comment.commenter.name}}
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="comment-top-time">
                        <span>{{$moment(comment.created_at).tz('Europe/Moscow').fromNow()}}</span>
                    </div>
                </div>
            </div>
            <div v-if="user.role == 'admin' || user.role == 'moderator'">
                <a @click.prevent="removeComment"
                   class="btn btn-sm btn-link text-danger text-uppercase"
                   :href="'#comment-' + comment.id" style="margin-bottom: 10px; text-decoration: underline !important;">Удалить</a>
                <a href style="text-decoration: underline !important;" @click.prevent="edit = !edit">Редактировать</a>
            </div>
            <div class="wrap-comment-body">
                <p v-show="!edit" v-html="comment.comment"></p>
                <div  v-if="user.role == 'admin' || user.role == 'moderator'" :class="'edit-comment-' + comment.id" v-show="edit">
                    <div class="textarea" data-bool="1">
                        <div class="textarea-block maxlength">
                            <textarea class="textarea-block__textarea"
                                      maxlength="280" data-emojiable="true"
                                      placeholder="Напишите сообщение">{{comment.comment}}</textarea>
                        </div>
                        <div class="input-el__media">
                            <p>Осталось - <span class="count_limit">280</span></p>
                        </div>
                    </div>
                    <div style="margin-top: 27px" class="btn-green">
                        <a @click.prevent="update" href="#" class="edit-comment-btn">
                            Сохранить
                        </a>
                        <a style="margin-left: 13px" @click.prevent="edit = !edit" href="#">Отменить</a>
                    </div>
                </div>

                <div class="wrap-files">
                    <a v-for="(image) in comment.images"
                       :href="'/storage/images/comments/' + image.path" :data-fancybox="'comment-' + comment.id">
                <img  :src="'/storage/images/comments/' + image.path" alt="">
                    </a>
                </div>
            </div>

        <div class="comment-reply">
            <div class="comment-reply-bottom">
                <comment-activities :comment="comment"></comment-activities>

                <div  v-if="user && user.id != comment.commenter.id" class="btn-green">
                    <a href="#" class="reply-comment-btn">
                        Ответить
                    </a>
                </div>
            </div>
            <div class="wrap-fade">
                <ul class="wrap-files">
                    <li v-for="(item, index) in items" class="file">
                        <div @click="deleteMin(index)" class="delete" title="Удалить"></div>
                        <img :src="item.image" alt="">
                    </li>
                </ul>
                <div class="textarea" data-bool="1">
                    <div class="textarea-block maxlength">
                        <textarea class="textarea-block__textarea" maxlength="280" data-emojiable="true" placeholder="Напишите сообщение"></textarea>
<!--                        <div class="textarea-block-media">-->
<!--                            <div class="textarea-block-media_el">-->
<!--                                <div class="load_media">-->
<!--                                    <label class="unselectable">-->
<!--                                        <input @change="onFileChange($event)"-->
<!--                                               data-maxfiles="2"-->
<!--                                               type="file" ref="files" multiple-->
<!--                                               name="files[]" style="display:none;"/>-->
<!--                                        <img src="/img/camera.svg" alt="">-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <div class="input-el__media">
                        <p>Осталось - <span class="count_limit">280</span></p>
                    </div>
                </div>
                <div class="btn-green">
                    <a @click="sendComment" href="#" class="reply-comment-btn">
                        Ответить
                    </a>
                </div>
            </div>
        </div>
        </div>
        <br>
        <comment v-for="(child) in comment.all_children" :user="user" :post="post" :comment="child"
                 :key="child.id" @remove="removeComment">
        </comment>
    </li>

</template>

<script>
    export default {
        name: "comment",
        props:['comment', 'user', 'post'],
        data() {
            return {
                files:[],
                items: [{image:null}],
                edit: false,
            }
        },
        computed: {
          style() {
              if (this.comment.images.length >=1) {
                  return 'margin-bottom:175px';
              }
          }
        },
        methods: {
            update() {
                let newValue = $('.edit-comment-' + this.comment.id + ' .emojionearea-editor')[0].innerHTML;
                this.edit = !this.edit;
                axios.post('/comment-edit/' + this.comment.id, {message: newValue}).then(response => {
                    this.comment.comment = response.data;
                    $('.edit-comment-' + this.comment.id + ' .emojionearea-editor').html('');
                })
            },

            replyClass(comment) {
                if (comment.child_id) {
                    return 'reply';
                }

                return '';
            },

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

            sendComment() {
                if ($('#comment-' + this.comment.id + ' .comment-reply').hasClass('active')) {
                    let message = $('.active .emojionearea-editor')[0].innerHTML;
                    let formData = new FormData();
                    formData.append('message', message);
                    formData.append('commentable_type', "App\\Post");
                    formData.append('commentable_id', this.post.id);

                    for( var i = 0; i < this.files.length; i++ ){
                        let file = this.files[i];
                        formData.append('files[' + i + ']', file, file.name);
                    }

                    // console.log(formData)
                    axios.post('/comments/' + this.comment.id, formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(response => {
                        let newCom = response.data.comment;
                        newCom.likers_count=0;
                        newCom.bookmarkers_count=0;
                        if (!this.comment.all_children) {
                            this.comment.all_children  = [];
                        }
                        newCom.images = response.data.images;
                        this.comment.all_children.push(newCom);
                        $('.active .emojionearea-editor')[0].innerHTML = '';
                        this.items = [{image: null}];
                        this.files = [];
                    })
                }
            },

            removeComment() {
                this.$emit('remove');
            }
        }
    }
</script>

<style scoped>

</style>
