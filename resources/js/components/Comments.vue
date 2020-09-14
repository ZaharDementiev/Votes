<template>
<div>
    <div v-if="allComments.length < 1" class="detail-content-noCommnets">
        <p class="noCommnets__text">Пока никто не прокомментировал...</p>
    </div>

    <div v-else class="publication-detail-content-comments">
        <div v-if="hasMore" class="detail-content-comments-else">
            <div class="btn-green tapes-else">
                <a @click="loadMore">
                    Смотреть еще
                    <img src="/img/tape/else_icon.svg" alt="">
                </a>
            </div>
        </div>

        <div class="detail-content-comments">
            <ul class="content-comments">
                    <comment v-for="(comment, index) in allComments"
                             :comment="comment" :post="post"
                             :user="user" :index="index" :key="comment.id"
                             @remove="deleteComment(comment.id, index)"
                    ></comment>
            </ul>
        </div>
    </div>

    <div v-if="!user" class="detail-content-main-activity" style="display: flex; justify-content: center">
        <div class="card-body">
            <h4 style="text-align: center">Чтобы оставить комментарий, необходимо <strong class="signIn_open"><a
                href="" style="color:#42978a"><br><br>войти</a></strong> или <strong class="registration_open">
                <a style="color:#42978a" href="">зарегистрироваться</a></strong></h4>
        </div>
    </div>

    <div class="card" v-if="user">
        <div class="card-body">
            <form id="fcomment" method="POST" action="/comments">
                <input type="hidden" name="commentable_type" value="App\Post" />
                <input type="hidden" name="commentable_id" :value="post.id" />

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
                                          <!--<input @change="onFileChange($event)"
                                                 data-maxfiles="2"
                                                 type="file" ref="files" multiple
                                                 name="files[]" style="display:none;"/>
                                          <img src="/img/camera.svg" alt="">
                                          !-->
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
                      <a @click="sendComment">Отправить</a>
                  </div>
              </div>
          </form>
      </div>
  </div>
  <br />

</div>
</template>

<script>
  export default {
      name: "comments",
      props:['comments', 'post', 'user'],
      data() {
          return {
              allComments:[],
              hasMore: false,
              files:[],
              items: [{image:null}],
          }
      },

      created() {
          for (let comment of this.comments) {
              this.allComments.unshift(comment);
          }

          if (this.allComments.length > 9) {
              this.hasMore = true;
          }
      },

      methods: {
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

          deleteComment(id, index) {
              axios.post('/comment-delete/' + id).then(response => {
                  delete this.allComments[index];
                  this.allComments.splice(index, 1);
                  console.log(response);
                  console.log(12);
              })
          },

          loadMore() {
              let ids = [];
              for (let comment of this.allComments) {
                  ids.push(comment.id)
              }

              axios.post(window.location.pathname, {ids: ids}).then(response => {
                  for(let single of response.data) {
                      this.allComments.unshift(single);
                  }
              }).catch(error => {
                  console.log(error);
              })

          },

          sendComment() {
              let message = $('.detail-content-comments-add .emojionearea-editor')[0].innerHTML;
              let formData = new FormData();
              formData.append('message', message);
              formData.append('commentable_type', "App\\Post");
              formData.append('commentable_id', this.post.id);

              for( var i = 0; i < this.files.length; i++ ){
                  let file = this.files[i];
                  formData.append('files[' + i + ']', file, file.name);
              }

              // console.log(formData)
              axios.post('/comments', formData,
                  {
                      headers: {
                          'Content-Type': 'multipart/form-data'
                      }
                  }).then(response => {
                      let newCom = response.data.comment;
                      newCom.likers_count=0;
                      newCom.bookmarkers_count=0;
                      newCom.images = response.data.images;
                      this.allComments.push(newCom);
                      $('.detail-content-comments-add .emojionearea-editor')[0].innerHTML = '';
                      this.items = [{image: null}];
                      this.files = [];
                      window.location.hash = 'comment-' + newCom.id;
                  })
          },
      }

  }
</script>

<style scoped>

</style>
