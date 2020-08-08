import VueRouter from 'vue-router';


let routes = [{
        path: '/dashboard',
        component: require('./views/dashboard').default
    },
    {
        path: '/users',
        component: require('./views/users').default
    },
    {
        path: '/instrumen',
        component: require('./views/instrumen').default
    }
];


export default new VueRouter({
    path: '/home',
    component: require('./views/dashboard').default,
    base: '/home/',
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});