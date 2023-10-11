<template>
  <div class="modal" @click="closePopUp">
    <div class="popUp">
      <div class="popUp__inner" @click.stop>
        <form class="form" v-on:submit.prevent="updateUser" method="put">
          <a href="#" class="popUp__close" @click="closePopUp">X</a>

          <div class="popUp__content">
            <h2 class="popUp__title">Редактирование данных</h2>

            <h2 class="popUp__sub-title">Заполните поля, которые хотите редактировать</h2>

            <div class="teacher__block">
              <!-- <img class="info-logo" src="@/assets/img/icon-teacher.svg" alt="" /> -->

              <input v-model="uploadData.name" type="text" placeholder="ФИО" class="information__input" />
              <input v-model="uploadData.email" type="email" placeholder="E-mail" class="information__input" />
              <input
                v-model="uploadData.newPassword"
                type="password"
                placeholder="**********"
                class="information__input"
              />
              <input v-model="uploadData.tel" type="text" placeholder="Телефон" class="information__input" />
              <input v-model="uploadData.faculty" type="text" placeholder="Факультет" class="information__input" />
            </div>

            <button class="button" type="submit">Редактировать данные</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PopUp',
  data() {
    return {
      uploadData: {
        name: '',
        email: '',
        newPassword: '',
        tel: '',
        faculty: '',
      },
    };
  },
  methods: {
    closePopUp() {
      this.$emit('close');
    },
    async updateUser() {
      try {
        const data = {
          jwt: localStorage.getItem('jwt'),
        };
        for (const field in this.uploadData) {
          if (this.uploadData[field] !== '') {
            data[field] = this.uploadData[field];
          }
        }
        await axios.put(`/user/update_user.php`, data).then((res) => {
          localStorage.setItem('jwt', res.data.jwt);
          alert('Данные пользователя были успешно обновлены');
          this.$emit('user-updated'); // Emit a custom event
        });
      } catch (error) {
        if (error.toString().includes('505')) {
          alert('Почта занята');
        } else {
          alert('Ошибка в работе сервера');
        }
      }
    },
  },
};
</script>

<style scoped>
.modal {
  position: fixed;
  z-index: 999;
  background: rgba(0, 0, 0, 0.8);
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}
.popUp {
  min-height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px 10px;
}

.popUp__inner {
  background: white;
  color: #000;
  max-width: 1200px;
  padding: 10px;
  position: relative;
}

.popUp__close {
  position: absolute;
  right: 10px;
  top: 10px;
  font-size: 25px;
  text-decoration: none;
  color: #405d91;
}

.popUp__title {
  text-align: center;
}
.popUp__sub-title {
  max-width: 500px;
  margin-top: 15px;
  text-align: center;
  font-size: 25px;
}

.popUp__content {
  display: flex;
  flex-direction: column;
  justify-items: center;
  font-size: calc(calc(1vw + 1vh) / 1.4);
  padding-top: 15px;
}

.information__input {
  display: flex;
  justify-self: center;
  margin-top: calc(calc(1vw + 1vh) * 1.25);
  width: calc(calc(1.5vw + 1vh) * 11.5);
  height: calc(calc(1vw + 1vh) * 1.75);
  background: #dee2ea;
  box-shadow: 0 6px 6px rgba(0, 0, 0, 0.25);
  border: none;
  outline: none;
}

.teacher__block {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.active {
  border: 1px solid #405d91;
}

.button {
  font-size: 20px;
  width: calc(calc(1vw + 1vh) * 8.5);
  background-color: #405d91;
  color: white;
  padding: 5px 10px;
  border: none;
  cursor: pointer;
  margin: 0 auto;
  margin-top: 25px;
}

.autorization span {
  text-decoration: underline;
}
</style>
