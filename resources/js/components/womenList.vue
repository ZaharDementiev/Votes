<template>
  <div class="wrap-topic">
    <div class="settings-content-el">
      <div class="settings-content-el-top unselectable">
        <div class="settings-content-el-left">
          <div class="settings-content-el-left-icon">
            <img src="/img/icons/searchDoc.svg" alt="">
          </div>
          <span>Найти вирт</span>
        </div>
        <div class="settings-content-el-right">
          <div class="arr_settigns">
            <img src="/img/arr_blue.svg" alt="">
          </div>
        </div>
      </div>
      <div class="settings-content-el-body">
        <form class="form-settigns-el" method="post" id="sortForm">
          <label class="wrap-radio__el radio-el form-settigns-el__el">
            <span class="radio-el__text">Online</span>
            <span class="radio-el__inp radio">
                <input type="checkbox" name="online" class="radio__inp">
                <span class="radio__dec">
                  <span class="radio__dec-circle"></span>
                </span>
              </span>
          </label>
          <div class="form-settigns-el__el">
            <div class="wrap-range">
              <div class="form-settigns-el__title title title_small">Возраст</div>
              <div class="rangeSlider wrap-range__range">
                <input type="text" class="rangeSlider__inp js-range-slider" name="my_range" value="">
              </div>
            </div>
          </div>
          <div class="select form-settigns-el__el">
            <div class="form-settigns-el__title title title_small">Теги</div>
            <select name="tags[]" class="select_jq"
                    data-placeholder="выберите не более 4-х тегов, начните вводить текст" multiple>
              <option v-for="(tag, index) in tags" :value="tag.id">{{ tag.name }}</option>
            </select>
          </div>

          <div class="btn-green" v-if="(user && user.vip)">
            <button type="button" @click="sort">Сохранить</button>
          </div>
          <div class="vip-block" v-else>
            <div class="vip-block__icon"></div>
            <div class="vip-block__text">Доступно для VIP</div>
          </div>
        </form>
      </div>
    </div>
    <div class="articles-users">

      <div v-for="(woman, index) in women" :class="vip_class(woman)">
        <div class="article-user__top">
          <a :href="'/user/' + woman.name" class="article-user__img userpic">
            <img :src="'/storage/images/avatars/' + woman.avatar" alt="" class="userpic__img">
          </a>
<!--          @else-->
<!--          <p>Была в сети {{ $user->last_online_at->diffForHumans() }}</p>-->
<!--          @endif-->
          <div v-if="woman.online" class="status article-user__status">
            <div class="status__dec"></div>
            <div class="status__text">Online</div>
          </div>
          <div v-else="!woman.online" class="status article-user__status">
            <div class="status__text">Была {{ $moment(woman.last_online_at).tz('Europe/Moscow').fromNow()}}</div>
          </div>
        </div>
        <div class="article-user__body">
          <a :href="'/user/' + woman.name" class="article-user__name">{{ woman.name + ' ' + woman.age}}</a>
          <div class="article-user__info">Рейтинг: <span class="article-user__info_bold">{{ woman.rating }}</span></div>
          <div class="article-user__info">Оценка: <span class="article-user__info_bold">4,5</span></div>
          <div class="rating">
            <div class="rating__el rating-el rating-el_active"></div>
            <div class="rating__el rating-el rating-el_active"></div>
            <div class="rating__el rating-el rating-el_active"></div>
            <div class="rating__el rating-el rating-el_active"></div>
            <div class="rating__el rating-el"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="btn-green tapes-else">
      <a href="#">Смотреть еще <img src="/img/tape/else_icon.svg" alt=""></a>
    </div>
  </div>
</template>

<script>
export default {
  name: "womenList",
  props: ['list', 'tags', 'user'],
  data() {
    return {
      women: [],
    }
  },
  created() {
    this.women = this.list;
  },
  methods: {
    sort() {
      let formData = $('#sortForm').serialize();
      axios.post('/virt/search', formData).then(resp => {
        this.women = resp.data;
      })
    },
    vip_class(woman) {
      if (woman.vip) {
        return 'articles-users__el article-user article-user_vip';
      } else {
        return 'articles-users__el article-user';
      }
    }
  }
}
</script>

<style scoped>

</style>