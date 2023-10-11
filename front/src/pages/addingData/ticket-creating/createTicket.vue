<template>
  <div>
    <div class="template__container" v-if="currentComponent === 'informAboutTicket'">
      <div class="flex">
        <h1 class="creating__template">Создание шаблона 1/2</h1>
        <InformAboutTicket @data-created="createData" />
      </div>
      <div class="image">
        <a class="back__image-first"></a>
        <button class="forward__image-first" @click="nextComponent(`databaseQuestions`)"></button>
      </div>
    </div>
    <div class="template__container" v-else-if="currentComponent === 'databaseQuestions'">
      <div class="flex-container">
        <h1 class="title">Создание шаблона 2/2</h1>
        <DatabaseQuestions @get-questions="handleGetQuestions" />
        <div class="box">
          <div class="box__image">
            <button class="button" @click="createTicket">Создать шаблон</button>
            <div class="image">
              <button class="back__image-second" @click="nextComponent(`informAboutTicket`)"></button>
              <a class="forward__image-second"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import InformAboutTicket from '../../../components/informAboutTicket.vue';
import DatabaseQuestions from '../../../components/databaseQuestions.vue';
import axios from 'axios';

export default {
  components: {
    InformAboutTicket,
    DatabaseQuestions,
  },
  data() {
    return {
      data: {
        questions: [],
      },
      currentComponent: 'informAboutTicket',
    };
  },
  methods: {
    nextComponent(componentName) {
      this.currentComponent = componentName;
    },
    createData(newData) {
      this.data = { ...this.data, ...newData };
    },
    async createTicket() {
      let id;
      try {
        const userData = {
          jwt: localStorage.getItem('jwt'),
        };
        await axios.post(`/user/validate_token.php`, userData).then((res) => {
          id = res.data.data.id;
        });
        const data = {
          name: this.data.name,
          commission: this.data.commission,
          subject: this.data.subject,
          faculty: this.data.faculty,
          examiner: this.data.examiner,
          specialization: this.data.specialization,
          chairman: this.data.chairman,
          questions: this.data.questions,
          userID: id,
        };
        await axios.post(`/ticket/create_ticket.php`, data).then(() => {
          alert('Билет был успешно создан');
        });
      } catch (error) {
        alert('Ошибка в работе сервера');
      }
    },
    handleGetQuestions(newQuestions) {
      this.data.questions = newQuestions;
    },
  },
};
</script>

<style scoped src="@/css/addingData/createTicket.css"></style>
