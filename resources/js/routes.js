import VueRouter from 'vue-router';


let routes = [{
        path: '/beranda',
        name: 'beranda',
        component: require('./views/dashboard').default
    },
    { path: '/master/kurs-dollar', name: 'kurs_dollar', component: require('./views/dollar.vue').default },
    { path: '/master/sub-ib', name: 'sub_ib', component: require('./views/sub_ib.vue').default },
    { path: '/master/trader', name: 'trader', component: require('./views/trader.vue').default },
    { path: '/transaksi/upload', name: 'upload', component: require('./views/upload.vue').default },
    { path: '/transaksi/rebate', name: 'rebate', component: require('./views/rebate.vue').default },
    { path: '/transaksi/komisi', name: 'komisi', component: require('./views/komisi.vue').default },
    { path: '/transaksi/trader', name: 'new_trader', component: require('./views/new_trader.vue').default },
    {
        path: '/users',
        name: 'users',
        component: require('./views/users').default
    },
    {
        path: '/profil',
        name: 'profil',
        component: require('./views/profile').default
    },
];

const router = new VueRouter({
    path: '/app',
    component: require('./views/dashboard').default,
    base: '/app/',
    mode: 'history',
    routes,
    linkActiveClass: 'active',
    user: user,
});
/*router.beforeResolve((to, from, next) => {
    // If this isn't an initial page load.
    if (to.name) {
        $('.fade').removeClass('show').addClass('out');
        // Start the route progress bar.
        NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    // Complete the animation of the route progress bar.
    //NProgress.done()
    //$('.fade').removeClass('out').addClass('show');
    setTimeout(function() {
        NProgress.done();
        $('.fade').removeClass('out').addClass('show');
    }, 1000);
})*/
export default router