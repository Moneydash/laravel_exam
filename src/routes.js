import login from '@/views/login.vue';
import register from '@/views/register.vue';
import home from '@/views/home.vue'
import notfound from '@/views/notfound.vue';
import userlist from '@/views/user_list.vue';
import playground from '@/views/playground.vue';
import rolelist from '@/views/user_roles.vue';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            title: 'Laravel Exam - Login'
        }
    },
    {
        path: '/register',
        name: 'Register',
        component: register,
        meta: {
            title: 'Laravel Exam - Register'
        }
    },
    {
        path: '/playground',
        name: 'playground',
        component: playground,
        meta: {
            title: 'Laravel Exam - Playground'
        }
    },
    {
        path: '/home',
        name: 'Home',
        component: home,
        meta: {
            title: 'Laravel Exam - Home Page',
            name: 'Home Page'
        },
        children: [
            {
                path: '/user/user_list',
                name: 'userlist',
                component: userlist,
                meta: {
                    title: 'Laravel Exam - User List',
                    name: 'User List'
                }
            },
            {
                path: '/user/role_list',
                name: 'rolelist',
                component: rolelist,
                meta: {
                    title: 'Laravel Exam - Role List',
                    name: 'Role List'
                }
            },
        ]
    },
    {
        path: '*',
        name: 'NotFound',
        component: notfound
    }
];

export default routes;