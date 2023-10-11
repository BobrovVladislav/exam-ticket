<template>
  <div class="adding-data">
    <div class="tickets__control">
      <h1 class="control__title">Управление билетами</h1>
      <div class="button__box">
        <button class="button" @click="component = 'viewTicket'">Посмотреть</button>
        <button class="button" @click="component = 'createTicket'">Создать</button>
        <button class="button" @click="component = 'editTicket'">Редактировать</button>
        <button class="button" @click="component = 'generationTickets'">Сгенерировать</button>
      </div>
    </div>

    <div class="main__block">
      <component :is="component" @sendData="childComponent"></component>
    </div>
  </div>
</template>

<script>
import viewTicket from './ticket-view/viewTicket.vue';
import createTicket from './ticket-creating/createTicket.vue';
import editTicket from './ticket-redact/editTicket.vue';
import generationTickets from './ticket-generation/generationTickets.vue';
export default {
  name: 'addingData',

  components: {
    viewTicket,
    createTicket,
    editTicket,
    generationTickets,
  },

  data() {
    return {
      component: 'viewTicket',
      lastPath: null,
    };
  },

  methods: {
    lastUrl() {
      //получаем предыдущий url
      this.lastPath = this.$router.options.history.state.back;
      console.log(this.lastPath);
      //делаем проверку на неавторизованного пользователя
      if (
        this.lastPath === '/authorizationTeacher' ||
        this.lastPath === null ||
        this.lastPath === '/' ||
        this.lastPath === '/registrationTeacher'
      ) {
        alert('Вы не вошли в систему');
        this.$router.push({ path: '/authorizationTeacher' });
      }
    },
    childComponent(data) {
      this.component = `${data.name}`;
    },

    allFunc: function () {
      this.mainSizeNormal();
      this.deleteElement();
    },
    deleteElement: function () {
      if (document.querySelector('.main__block').querySelector('.main__block__p')) {
        document.querySelector('.main__block__p').remove();
      }
    },
    mainSizeMin: function () {
      document.querySelector('.main__block').style.width = 'calc(calc(1vw + 1vh) * 20)';
    },
    mainSizeNormal: function () {
      document.querySelector('.main__block').style.width = 'calc(calc(1vw + 1vh) * 35)';
    },
  },
  mounted() {
    if (document.querySelector('.tickets__generation__one') || document.querySelector('.tickets__generation__two')) {
      document.querySelector('.main__block').style.width = 'calc(calc(1vw + 1vh) * 20)';
    }
  },
  created() {
    if (localStorage.getItem('jwt') !== null) {
      this.$router.push({ name: 'addingData' });
    } else {
      this.lastUrl();
    }
  },
};
</script>

<style scoped src="@/css/addingData/addingData.css"></style>
