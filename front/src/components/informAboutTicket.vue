<!-- Контейнер с информацией о билете -->
<template>
  <div class="ticket__name">
    <h1 class="ticket__name__text">Название билета:</h1>
    <input class="ticket__name__input" type="text" v-model="name" />
  </div>
  <div class="grid__container">
    <div class="container-input">
      <label class="input__title">Цикловая коммисия</label>
      <input class="input" type="text" v-model="commission" />
    </div>
    <div class="container-input">
      <label class="input__title">Название предмета</label>
      <input class="input" type="text" v-model="subject" />
    </div>
    <div class="container-input">
      <label class="input__title">Факультет</label>
      <input class="input" type="text" v-model="faculty" />
    </div>
    <div class="container-input">
      <label class="input__title">Экзаменатор</label>
      <input class="input" type="text" v-model="examiner" />
    </div>
    <div class="container-input">
      <label class="input__title">Направление подготовки/cпециальность</label>
      <input class="input" type="text" v-model="specialization" />
    </div>
    <div class="container-input">
      <label class="input__title">Председатель ЦК</label>
      <input class="input" type="text" v-model="chairman" />
    </div>
  </div>
</template>

<script>
export default {
  props: ['selectedTicket'],
  data() {
    return {
      name: this.selectedTicket ? this.selectedTicket.name : '',
      commission: this.selectedTicket ? this.selectedTicket.commission : '',
      subject: this.selectedTicket ? this.selectedTicket.subject : '',
      faculty: this.selectedTicket ? this.selectedTicket.faculty : '',
      examiner: this.selectedTicket ? this.selectedTicket.examiner : '',
      specialization: this.selectedTicket ? this.selectedTicket.specialization : '',
      chairman: this.selectedTicket ? this.selectedTicket.chairman : '',
      ticketID: this.selectedTicket ? this.selectedTicket.ticketID : '',
      questions: this.selectedTicket ? this.selectedTicket.questions : '',
    };
  },
  methods: {},
  beforeUnmount() {
    this.$emit('data-created', {
      name: this.name,
      commission: this.commission,
      subject: this.subject,
      faculty: this.faculty,
      examiner: this.examiner,
      specialization: this.specialization,
      chairman: this.chairman,
    });
    this.$emit('data-updated', {
      ticketID: this.ticketID,
      name: this.name,
      commission: this.commission,
      subject: this.subject,
      faculty: this.faculty,
      examiner: this.examiner,
      specialization: this.specialization,
      chairman: this.chairman,
      questions: this.questions,
    });
  },
};
</script>
<style scoped src="@/css/informAboutTicket.css"></style>
