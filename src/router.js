import Vue from 'vue';
import VueRouter from 'vue-router';
import Allroutes from './routes';

Vue.use(VueRouter)

var myroutes = [
    {
        path: '/',
        name: 'main',
        component: () => import('@/views/login.vue')
    }
];

myroutes = myroutes.concat(Allroutes);

const routes = myroutes;

export default new VueRouter({
    mode: 'history',
    routes
})