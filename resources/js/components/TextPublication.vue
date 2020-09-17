<template>
    <section class="sec-publication">
        <div class="wrap-publication">
            <div class="wrap-publication-content">
                <div class="publication-content-form">
                    <h3 v-show="error" style="color: red">
                        Сперва заполните все поля!
                    </h3>
                    <form method="post" id="addPost" enctype="multipart/form-data">
                        <div class="publication-content-form-el">
                            <h4>Название</h4>
                            <div class="inputs">
                                <div class="input-el">
                                    <input class="inp_maxlength" minlength="2" maxlength="120" name="title" type="text">
                                    <div class="input-el__media">
                                        <p>Осталось - <span class="count_limit">120</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="publication-content-form-el">
                            <h4>Текст</h4>
                            <div class="textarea">
                                <div class="textarea-block maxlength">
                                    <textarea class="textarea-block__textarea" minlength="5" maxlength="2000"></textarea>
                                    <div class="textarea-block-media">
                                        <div class="textarea-block-media_el">
                                            <div class="load_media">
                                                <label class="unselectable" v-if="items.length < 10">
                                                    <input @change="onFileChange($event)" id="inpImg" type="file" multiple=""
                                                           class="inp-file-textarea" data-maxfiles="10">
                                                    <img src="/img/camera.svg" alt="">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-el__media">
                                    <p>Осталось - <span class="count_limit">2000</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="publication-content-form-el">
                            <div class="publication-content-form-files">
                                <ul class="wrap-files">
                                    <li class="file"  v-for="(item, index) in items" v-if="item.image != null">
                                        <div @click="deleteMin(index)" class="delete" title="Удалить"></div>
                                        <img :src="item.image" alt="">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <add-field></add-field>
                        <div class="publication-content-form-el">
                            <h4>Тема</h4>
                            <div class="select">
                                <select required class="select_jq" name="tags[]" data-placeholder=" " multiple>
                                    <option v-for="(tag) in tags" :value="tag.id">{{tag.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="publication-content-form-el">
                            <div style="margin-bottom:-35px">
                                <div class="wrap-checkbox unselectable anon-check">
                                    <label>
                                        <div class="checkbox-el">
                                            <input type="checkbox" name="anon">
                                            <div class="checkbox"></div>
                                        </div>
                                        <span>Анонимно</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="publication-content-form-bottom" style="margin-bottom: 20px">
                            <div class="publication-content-form-btn btn-green">
                                <button @click.prevent="sendForm" v-show="publish" type="submit">Опубликовать</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


</template>

<script>
    export default {
        name: "TextPublication",
        props: ['tags'],

        data() {
            return {
                items: [],
                files: [],
                publish: true,
                error: false,
            }
        },
        methods: {
            sendForm() {
                $('#inpImg').remove();
                let description = $('.textarea .emojionearea-editor')[0].innerHTML;
                let form = $('#addPost');
                let formData = new FormData(form[0]);
                formData.append('description', description);

                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('files[' + i + ']', file, file.name);
                }

                this.publish = false;

                axios.post('/posts/create', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        let slug = response.data;
                        window.location.href = '/posts/' + slug;
                    }).catch(error => {
                    this.publish = true;
                    this.error = true;
                    })
            },

            onFileChange(e) {
                if (this.files.length < 10) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    if (files.length > 10) {
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
                let image = new Image();
                let reader = new FileReader();

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

        },
    }
</script>

<style scoped>

</style>
