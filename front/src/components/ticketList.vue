<template>
  <div v-if="tickets.length > 0">
    <h2 class="main__block__text">Выбирите билет:</h2>
    <ul>
      <li v-for="ticket in paginatedTickets" :key="ticket.id">
        <div class="ticket" @click="showTicketDetails(ticket)">
          <img class="ticket__image" src="@/assets/tickcet.png" alt="Билет" />
          <div class="ticket__content">
            <h2 class="ticket__name"><strong>Билет:</strong> {{ ticket.name }}</h2>
            <div class="ticket__subject"><strong>Предмет:</strong> {{ ticket.subject }}</div>
            <div class="ticket__text">
              <strong>Теоретические вопросы:</strong> {{ countTheoreticalQuestions(ticket) }}
            </div>
            <div class="ticket__text"><strong>Практические вопросы:</strong> {{ countPracticalQuestions(ticket) }}</div>
          </div>
        </div>
      </li>
    </ul>
    <div class="pagination">
      <button v-for="page in totalPages" :key="page" @click="goToPage(page)" :class="{ active: page === currentPage }">
        {{ page }}
      </button>
    </div>
  </div>
  <div v-else>
    <h1 class="main__block__no-ticket">У вас пока нет билетов</h1>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tickets: [],
      paginatedTickets: [],
      itemsPerPage: 3,
      currentPage: 1,
    };
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
        this.updatePaginatedTickets();
      });
    } catch (error) {
      alert('Ошибка в работе сервера');
    }
  },
  methods: {
    showTicketDetails(ticket) {
      this.$emit('showTicketDetails', ticket);
    },
    countPracticalQuestions(ticket) {
      return ticket.questions.filter((question) => question.typeQuestion === 'Практика').length;
    },
    countTheoreticalQuestions(ticket) {
      return ticket.questions.filter((question) => question.typeQuestion === 'Теория').length;
    },
    goToPage(page) {
      this.currentPage = page;
      this.updatePaginatedTickets();
    },
    updatePaginatedTickets() {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      this.paginatedTickets = this.tickets.slice(startIndex, endIndex);
    },
  },
  computed: {
    totalPages() {
      return Math.ceil(this.tickets.length / this.itemsPerPage);
    },
  },
};
</script>

<style scoped src="@/css/ticketList.css"></style>
