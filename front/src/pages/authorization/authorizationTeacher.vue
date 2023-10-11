<template>
  <div class="authorization-teacher">
    <form class="form" v-on:submit.prevent="auth" method="post">
      <h2 class="authorization__rectangle__title">Авторизация</h2>
      <img src="@/assets/img/icon-teacher.svg" alt="" />

      <input
        v-model="email"
        name="name"
        class="form__information"
        placeholder="E-mail"
        type="email"
        id="email"
        required
      />
      <input
        v-model="password"
        name="name"
        class="form__information"
        placeholder="Пароль"
        type="password"
        id="password"
        required
      />

      <button class="button" type="submit">Войти</button>

      <div class="registration">
        <router-link to="/registrationTeacher">Зарегистрироваться!</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'autorizationTeacher',

  data() {
    return {
      email: '',
      password: '',
      jwt: '',
    };
  },
  methods: {
    async auth() {
      try {
        const data = {
          email: this.email,
          password: this.password,
        };
        await axios.post(`/user/login.php`, data).then((res) => {
          this.jwt = res.data.jwt;
          localStorage.setItem('jwt', this.jwt);
          console.log(this.jwt);
          this.$router.push('/personalAccount');
          alert('Авторизация прошла успешно');
          // window.location.reload()
        });
      } catch (error) {
        if (error.toString().includes('401')) {
          alert('Ошибка входа');
        } else {
          alert('Ошибка в работе сервера');
        }
      }
    },
  },
};
</script>

<style scoped>
.authorization-teacher {
  margin: auto;
}
.form {
  width: calc(calc(1vw + 1vh) * 15);
  background: #fff;
  filter: drop-shadow(0 0.2rem 0.25rem rgba(0, 0, 0, 0.2));
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 35px;
}

h2 {
  margin-bottom: 20px;
}

.form__information {
  padding-left: 10px;
  margin-top: calc(calc(1vw + 1vh) * 1.25);
  width: calc(calc(1vw + 1vh) * 11.5);
  height: calc(calc(1vw + 1vh) * 1.75);
  background: #dee2ea;
  box-shadow: 0 6px 6px rgba(0, 0, 0, 0.25);
  border: none;
  outline: none;
}

.form__information:first-child {
  margin-top: 0;
}

.form__information::placeholder {
  font-size: calc(calc(1vw + 1vh) * 1.05);
}

.button {
  font-size: calc(calc(1vw + 1vh) * 1.05);
  width: calc(calc(1vw + 1vh) * 8.5);
  height: calc(calc(1vw + 1vh) * 1.75);
  background-color: #405d91;
  color: white;
  margin-top: 50px;
  border: none;
  cursor: pointer;
}

.registration {
  text-align: center;
  padding: 10px;
  font-size: calc(calc(1vw + 1vh));
  text-decoration: underline #405d91;
}
</style>
