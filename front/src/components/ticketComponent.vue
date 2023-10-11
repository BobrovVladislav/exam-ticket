<!-- Билет -->
<template>
  <div class="ticket__example">
    <div class="main__container">
      <div class="inner__container">
        <p class="text__university">ФГБОУ ВО «Воронежский государственный университет инженерных технологий»</p>
        <hr />
        <div class="text__university-group">
          <p class="text__university">Цикловая комиссия {{ selectedTicket.commission }}</p>
          <p class="text__university">Факультет {{ selectedTicket.faculty }}</p>
        </div>
        <p class="text__university mb">Направление подготовки/специальность: {{ selectedTicket.specialization }}</p>
        <p class="item__name mb">{{ selectedTicket.subject }}</p>
        <h2 class="mb">БИЛЕТ №1</h2>
        <ol class="questions mb">
          <li v-for="question in theoreticalQuestions" :key="question.text">
            {{ question.text }}
          </li>
          <li v-for="question in practicalQuestions" :key="question.text">
            {{ question.text }}
          </li>
        </ol>
        <div class="teachers">
          <p>Экзаменатор ______ {{ selectedTicket.examiner }}</p>
          <p>Председатель ЦК ______ {{ selectedTicket.chairman }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'ticketComponent',
  props: ['selectedTicket'],
  computed: {
    practicalQuestions() {
      return this.selectedTicket.questions.filter((question) => question.typeQuestion === 'Практика').slice(0, 2);
    },
    theoreticalQuestions() {
      return this.selectedTicket.questions.filter((question) => question.typeQuestion === 'Теория').slice(0, 2);
    },
  },
  /*methods: {
    clickOnElement(event) {
      let target = event;
      if (
        event.target.tagName === 'FIGURE' ||
        event.target.tagName === 'IMG' ||
        event.target.tagName === 'FIGCAPTION'
      ) {
        target.path[1].childNodes[1].classList.add('border');
        console.log(target.path[1].childNodes[1]);
      } else {
        return;
      }
    },
  },*/
};
</script>
<style scoped src="@/css/ticketComponent.css"></style>
