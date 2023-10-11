<template>
  <!-- Редактирование билета. Первая страница со списком билетов.-->
  <div class="template__container" v-if="currentComponent === 'editOne'">
    <h1 class="main__block__title">Редактирование билетов</h1>
    <ListOfTickets @showTicketDetails="showTicketDetails"></ListOfTickets>
  </div>
  <!-- Редактирование билета. Вторая страница с общей информацией.-->
  <div class="template__container" v-else-if="currentComponent === 'editTwo'">
    <div class="flex">
      <h1 class="creating__template">Редактирование шаблона 1/2</h1>
      <InformAboutTicket :selectedTicket="selectedTicket" @data-updated="updateData" />
    </div>
    <div class="image">
      <button class="back__image-second" @click="backToList"></button>
      <button class="forward__image-first" @click="nextComponent(`editThree`)"></button>
    </div>
  </div>
  <!-- Редактирование билета. Третья страница с редактированием вопросов.-->
  <div class="template__container" v-else-if="currentComponent === 'editThree' && selectedTicket">
    <div class="flex-container">
      <h1 class="title">Редактирование шаблона 2/2</h1>

      <DatabaseQuestions :questions="selectedTicket.questions" @get-questions="handleGetQuestions" />

      <div class="box">
        <div class="box__image">
          <button class="button" @click="updateTicket">Сохранить изменения</button>
          <div class="image">
            <button class="back__image-second" @click="nextComponent(`editTwo`)"></button>
            <a class="forward__image-second"></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ListOfTickets from '../../../components/ticketList';
import InformAboutTicket from '../../../components/informAboutTicket.vue';
import DatabaseQuestions from '../../../components/databaseQuestions.vue';
import axios from 'axios';

export default {
  components: {
    InformAboutTicket,
    DatabaseQuestions,
    ListOfTickets,
  },
  data() {
    return {
      selectedTicket: null,
      currentComponent: 'editOne',
    };
  },
  methods: {
    async updateTicket() {
      let id;
      try {
        const userData = {
          jwt: localStorage.getItem('jwt'),
        };
        await axios.post(`/user/validate_token.php`, userData).then((res) => {
          id = res.data.data.id;
        });
        const data = {
          ticketID: this.selectedTicket.ticketID,
          name: this.selectedTicket.name,
          commission: this.selectedTicket.commission,
          subject: this.selectedTicket.subject,
          faculty: this.selectedTicket.faculty,
          examiner: this.selectedTicket.examiner,
          specialization: this.selectedTicket.specialization,
          chairman: this.selectedTicket.chairman,
          questions: this.selectedTicket.questions,
          userID: id,
        };
        await axios.put(`/ticket/update_ticket.php`, data).then(() => {
          alert('Билет был успешно обновлён');
        });
      } catch (error) {
        alert('Ошибка в работе сервера');
      }
    },
    handleGetQuestions(newQuestions) {
      this.selectedTicket.questions = newQuestions;
    },
    nextComponent(componentName) {
      this.currentComponent = componentName;
    },
    showTicketDetails(ticket) {
      this.selectedTicket = ticket;
      this.nextComponent('editTwo');
    },
    backToList() {
      // эта строка кода не работает :(
      this.selectedTicket = '';
      this.nextComponent('editOne');
    },
    updateData(newData) {
      this.selectedTicket = { ...this.data, ...newData };
    },
  },
};
</script>

<style scoped src="@/css/addingData/editingTicket.css"></style>
