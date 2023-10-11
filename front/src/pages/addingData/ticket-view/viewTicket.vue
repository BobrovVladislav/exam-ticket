<template>
  <div>
    <!--Просмотр билета. Первая страница со списком билетов.-->
    <div class="template__container" v-if="!selectedTicket">
      <h1 class="main__block__title">Просмотр билетов</h1>
      <ListOfTickets @showTicketDetails="showTicketDetails"></ListOfTickets>
    </div>
    <!--Просмотр билета. Вторая страница с примером билета.-->
    <div class="template__container" v-if="selectedTicket">
      <div class="title">
        <button class="back" @click="backToList">
          <div class="back__image"></div>
          <p class="back__text">Назад</p>
        </button>
        <p class="main__block__title">Просмотр билета</p>
      </div>
      <TicketExample :selectedTicket="selectedTicket"></TicketExample>
    </div>
  </div>
</template>

<script>
import ListOfTickets from '../../../components/ticketList';
import TicketExample from '../../../components/ticketComponent';
export default {
  components: { ListOfTickets, TicketExample },
  data() {
    return {
      selectedTicket: null,
    };
  },
  methods: {
    showTicketDetails(ticket) {
      this.selectedTicket = ticket;
    },
    backToList() {
      this.selectedTicket = null;
    },
  },
};
</script>

<style scoped src="@/css/addingData/viewTicket.css"></style>
