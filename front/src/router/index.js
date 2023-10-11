//импорт файлов авторизации
import authorizationTeacher from '../pages/authorization/authorizationTeacher.vue';
import registrationTeacher from '../pages/registration/registTeacher.vue';

//импорт файлов личного кабинета
import exPersonalAccount from '../pages/personalAccount/PersonalAccount.vue';

//импорт файлов добавления данных
import { createRouter, createWebHistory } from 'vue-router';

import addingData from '../pages/addingData/addingData.vue';
import viewTicket from '../pages/addingData/ticket-view/viewTicket.vue';
import createTicket from '../pages/addingData/ticket-creating/createTicket.vue';
import editingTicket from '../pages/addingData/ticket-redact/editTicket.vue';
import generationTicket from '../pages/addingData/ticket-generation/generationTickets.vue';

const routes = [
  //роутеры авторизации
  {
    path: '/authorizationTeacher',
    name: 'authorizationTeacher',
    component: authorizationTeacher,
    alias: '/',
  },
  {
    path: '/registrationTeacher',
    name: 'registrationTeacher',
    component: registrationTeacher,
  },

  //роутеры личного кабинета
  {
    path: '/personalAccount',
    name: 'personalAccount',
    component: exPersonalAccount,
  },

  //роутеры добавления данных
  {
    path: '/addingData',
    name: 'addingData',
    component: addingData,
    children: [
      {
        path: '/addingData/viewTicket',
        name: 'viewTicket',
        component: viewTicket,
      },
      {
        path: '/addingData/createTicket',
        name: 'createTicket',
        component: createTicket,
      },
      {
        path: '/addingData/ticket-edit',
        name: 'ticket-edit',
        component: editingTicket,
      },
      {
        path: '/addingData/ticket-generation',
        name: 'ticket-generation',
        component: generationTicket,
      },
    ],
  },

  //обработка неверных маршрутов
  //task переписать редирект. Создать станицу 404 об ошибке.
  {
    path: '/:catchAll(.*)',
    redirect: { name: 'authorizationTeacher' },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE__URL),
  routes,
});

export default router;
