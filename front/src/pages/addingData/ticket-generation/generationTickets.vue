<template>
  <!-- Генерация билета. Первая страница.-->
  <div class="template__container" v-if="currentComponent === `selectGenerationTickets`">
    <div class="flex-container">
      <h1 class="title">Генерация билетов</h1>
      <div class="choise__container">
        <h1 class="choise">Выберите шаблон билета</h1>
        <input
          type="text"
          list="datalist"
          placeholder="Шаблон №"
          class="datalist__box"
          v-model="selectedTicket"
          @change="updateSelectedTemplate"
        />
        <datalist class="datalist" id="datalist">
          <option
            v-for="(ticket, index) in tickets"
            :value="`Шаблон №${index + 1} - ${ticket.name}`"
            :data-ticket-id="ticket.ticketID"
            :key="ticket.ticketID"
          ></option>
        </datalist>
      </div>
      <h1 class="amount__tickets">Укажите количество<br />гинерируемых билетов</h1>
      <div class="amount__flex-container">
        <input type="number" class="amount" min="1" max="10" v-model="numberOfTickets" />
        <h1 class="tickets__text">Билетов</h1>
      </div>
      <div class="router-link__box__size">
        <button class="router-link" @click="generateTickets">Сгенерировать</button>
      </div>
    </div>
  </div>
  <!-- Генерация билета. Success страница.-->
  <div class="template__container" v-if="currentComponent === `successGeneration`">
    <div class="flex-size">
      <h1 class="title">Генерация билетов</h1>
      <button class="back__box" @click="nextComponent(`selectGenerationTickets`)">
        <div class="back__img"></div>
        <h1 class="back__text">Назад</h1>
      </button>
      <h1 class="complete">Билеты успешно<br />сгенерированы</h1>
      <div class="complete__image"></div>
      <h1 class="choise__format">Выберите формат</h1>
      <div class="document__box__image">
        <a href="#" class="doc__img" @click="generateTicketDocx"></a>
        <a href="#" class="pdf__img" @click="generateTicketPDF"></a>
      </div>
    </div>
  </div>
  <!-- Генерация билета. Error страница.-->
  <div class="template__container" v-if="currentComponent === `errorGeneration`">
    <div class="flex-size">
      <h1 class="title">Генерация билетов</h1>
      <button class="back__box" @click="nextComponent(`selectGenerationTickets`)">
        <div class="back__img"></div>
        <h1 class="back__text">Назад</h1>
      </button>
      <h1 class="complete">Ошибка в генерации<br />билетов</h1>
      <div class="error__image"></div>
      <h2 class="error">Внимательно проверьте введённые данных на наличие ошибок</h2>
    </div>
  </div>
</template>

<script scoped>
import axios from 'axios';
// import { Document, Packer, Paragraph, TextRun } from 'docx';

export default {
  data() {
    return {
      selectedTicket: '',
      structuredTicket: {},
      tickets: [],
      numberOfTickets: '',
      generatedTickets: [],
      currentComponent: 'selectGenerationTickets',
    };
  },
  methods: {
    updateSelectedTemplate() {
      this.structuredTicket = {};
      const selectedTicketId = document
        .querySelector(`option[value="${this.selectedTicket}"]`)
        .getAttribute('data-ticket-id');
      const selectedTicket = this.tickets.find((ticket) => ticket.ticketID === selectedTicketId);
      if (selectedTicket) {
        this.structuredTicket = selectedTicket;
      }
    },
    generateTickets() {
      if (
        this.structuredTicket.name == '' ||
        this.structuredTicket.commission == '' ||
        this.structuredTicket.subject == '' ||
        this.structuredTicket.faculty == '' ||
        this.structuredTicket.examiner == '' ||
        this.structuredTicket.specialization == '' ||
        this.structuredTicket.chairman == '' ||
        this.structuredTicket.questions.length <= 1 ||
        this.numberOfTickets == '' ||
        this.numberOfTickets < 1
      ) {
        this.generatedTickets = [];
        this.nextComponent('errorGeneration');
      } else {
        const generatedTickets = [];
        for (let i = 0; i < this.numberOfTickets; i++) {
          const randomQuestions = this.generateRandomQuestions(this.structuredTicket);
          generatedTickets.push({
            name: this.structuredTicket.name,
            commission: this.structuredTicket.commission,
            subject: this.structuredTicket.subject,
            faculty: this.structuredTicket.faculty,
            examiner: this.structuredTicket.examiner,
            specialization: this.structuredTicket.specialization,
            chairman: this.structuredTicket.chairman,
            questions: randomQuestions,
          });
        }
        this.generatedTickets = generatedTickets;
        this.nextComponent('successGeneration');
      }
    },
    generateRandomQuestions(template) {
      if (template.questions) {
        const theoryQuestions = template.questions.filter((question) => question.typeQuestion === 'Теория');
        const practiceQuestions = template.questions.filter((question) => question.typeQuestion === 'Практика');

        theoryQuestions.sort(() => Math.random() - 0.5);
        practiceQuestions.sort(() => Math.random() - 0.5);

        const selectedQuestions = [...theoryQuestions.slice(0, 2), ...practiceQuestions.slice(0, 2)];

        return selectedQuestions;
      }

      return [];
    },
    async generateTicketDocx() {
      const data = { tickets: this.generatedTickets };
      try {
        await axios.post('/ticket/generate_ticketDocx.php', data, { responseType: 'blob' }).then((response) => {
          const blob = new Blob([response.data], { type: 'application/pdf' });

          // Create a URL for the blob
          const url = window.URL.createObjectURL(blob);

          // Create a link and trigger the download
          const a = document.createElement('a');
          a.style.display = 'none';
          a.href = url;
          a.setAttribute('download', 'tickets.doc');
          document.body.appendChild(a);
          a.click();

          document.body.removeChild(a);
        });
      } catch (error) {
        alert('Ошибка в работе сервера');
      }
    },
    async generateTicketPDF() {
      const data = { tickets: this.generatedTickets };
      try {
        await axios.post('/ticket/generate_ticketPDF.php', data, { responseType: 'blob' }).then((response) => {
          const blob = new Blob([response.data], { type: 'application/pdf' });

          // Create a URL for the blob
          const url = window.URL.createObjectURL(blob);

          // Create a link and trigger the download
          const a = document.createElement('a');
          a.style.display = 'none';
          a.href = url;
          a.setAttribute('download', 'tickets.pdf');
          document.body.appendChild(a);
          a.click();

          document.body.removeChild(a);
        });
      } catch (error) {
        alert('Ошибка в работе сервера');
      }
    },
    nextComponent(componentName) {
      this.currentComponent = componentName;
    },
  },
  async mounted() {
    let id;
    try {
      const userData = {
        jwt: localStorage.getItem('jwt'),
      };
      await axios.post(`/user/validate_token.php`, userData).then((res) => {
        id = res.data.data.id;
      });
      const data = {
        userID: id,
      };
      await axios.post(`/ticket/getAll_tickets.php`, data).then((res) => {
        this.tickets = res.data;
      });
    } catch (error) {
      alert('Ошибка в работе сервера');
    }
  },
};
</script>

<style scoped src="@/css/addingData/ticketsGenerationOne.css"></style>
