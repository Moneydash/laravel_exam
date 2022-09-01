import Vue from 'vue';
import App from './App.vue';
import * as VueRouter from 'vue-router';
import vuetify from './plugins/vuetify';
import store from './store/store';
import axios from 'axios';
import router from './router';
import VueSession from 'vue-session';

Vue.use(VueRouter);
Vue.use(VueSession);

Vue.prototype.$http = axios;
Vue.prototype.$dir = 'http://localhost:8000';

Vue.config.productionTip = false

new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
