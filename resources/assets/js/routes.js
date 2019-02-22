import User from './components/User';
import Welcome from './components/Welcome';
import Login from './components/Login';
import Logout from './components/Logout';
import Superpower from './components/Superpower';
import Trending from './components/Trending';

export const routes = [
    // {
    //     path: '/home',
    //     name: 'home',
    //     component: Home,
    //     meta: {
    //         requiresAuth: true,
    //     },
    // },

    {
        path: '/superpowers/trending',
        name: 'trending',
        component: Trending,
        meta: {
            requiresAuth: true,
        },
    },

    {
        path: '/users/:id',
        name: 'user',
        component: User,
        meta: {
            requiresAuth: true,
        },
    },

    {
        path: '/superpowers/:id',
        name: 'superpower',
        component: Superpower,
        meta: {
            requiresAuth: true,
        },
    },

    {
        path: '/',
        name: 'welcome',
        component: Welcome,
        // meta: {
        //     requiresVisitor: true,
        // },
    },

    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            requiresVisitor: true,
        },
    },

    {
        path: '/logout',
        name: 'logout',
        component: Logout,
        meta: {
            requiresAuth: true,
        },
    }
]