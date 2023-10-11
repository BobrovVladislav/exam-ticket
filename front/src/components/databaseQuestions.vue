<template>
  <!-- Блок с базой вопросов -->
  <h1 class="text">База вопросов</h1>

  <div class="questions">
    <div class="teoretical-questions">
      <div class="questions__flex-container">
        <h1 class="questions__title">Теоритические вопросы</h1>
        <a href="#" class="image__doc"></a>
      </div>
      <div class="ul__element">
        <input
          class="ul__input"
          v-model="newTeoreticalQuestionText"
          type="text"
          placeholder="Введите теоретический вопрос"
        />
        <button class="add__block" @click="addTeoreticalQuestion">Добавить</button>
      </div>
      <ul class="questions-list">
        <li v-for="(question, index) in teoreticalQuestions" :key="index">
          <div class="ul__element">
            <input class="ul__input" :placeholder="question.text" disabled />
            <button class="delete__block" @click="deleteTeoreticalQuestion(index)">X</button>
          </div>
        </li>
      </ul>
    </div>
    <div class="practical-questions">
      <div class="questions__flex-container">
        <h1 class="questions__title">Практические вопросы</h1>
        <a href="#" class="image__doc"></a>
      </div>
      <div class="ul__element">
        <input
          class="ul__input"
          v-model="newParticalQuestionText"
          type="text"
          placeholder="Введите практический вопрос"
        />
        <button class="add__block" @click="addPracticalQuestion">Добавить</button>
      </div>
      <ul class="questions-list">
        <li v-for="(question, index) in practicalQuestions" :key="index">
          <div class="ul__element">
            <input class="ul__input" :placeholder="question.text" disabled />
            <button class="delete__block" @click="deletePracticalQuestion(index)">X</button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
export default {
  props: ['questions'],
  data() {
    return {
      newTeoreticalQuestionText: '',
      teoreticalQuestions: [],
      newParticalQuestionText: '',
      practicalQuestions: [],
    };
  },
  watch: {
    questions: {
      handler: 'initializeQuestions',
      immediate: true,
      deep: true,
    },
    teoreticalQuestions: {
      handler: 'sendQuestionsToParent',
      deep: true,
    },
    practicalQuestions: {
      handler: 'sendQuestionsToParent',
      deep: true,
    },
  },
  methods: {
    initializeQuestions(newQuestions) {
      if (this.questions) {
        this.teoreticalQuestions = newQuestions.filter((question) => question.typeQuestion === 'Теория').slice();
        this.practicalQuestions = newQuestions.filter((question) => question.typeQuestion === 'Практика').slice();
      } else {
        this.teoreticalQuestions = [];
        this.practicalQuestions = [];
      }
    },
    addTeoreticalQuestion() {
      if (this.newTeoreticalQuestionText) {
        const newQuestion = {
          typeQuestion: 'Теория',
          text: this.newTeoreticalQuestionText,
        };
        this.teoreticalQuestions.push(newQuestion);
        this.newTeoreticalQuestionText = '';
      }
    },
    deleteTeoreticalQuestion(index) {
      this.teoreticalQuestions.splice(index, 1);
    },
    addPracticalQuestion() {
      if (this.newParticalQuestionText) {
        const newQuestion = {
          typeQuestion: 'Практика',
          text: this.newParticalQuestionText,
        };
        this.practicalQuestions.push(newQuestion);
        this.newParticalQuestionText = '';
      }
    },
    deletePracticalQuestion(index) {
      this.practicalQuestions.splice(index, 1);
    },
    sendQuestionsToParent() {
      const allQuestions = [...this.teoreticalQuestions, ...this.practicalQuestions];

      this.$emit('get-questions', allQuestions);
    },
  },
};
</script>
<style scoped src="@/css/databaseQuestions.css"></style>
