import VueRouter from 'vue-router';


let routes = [{
        path: '/beranda',
        name: 'beranda',
        component: require('./views/dashboard').default
    },
    {
        path: '/sekolah',
        name: 'sekolah',
        component: require('./views/referensi/sekolah').default
    },
    {
        path: '/tanah',
        name: 'tanah',
        component: require('./views/referensi/tanah').default
    },
    {
        path: '/bangunan',
        name: 'bangunan',
        component: require('./views/referensi/bangunan').default
    },
    {
        path: '/ruang',
        name: 'ruang',
        component: require('./views/referensi/ruang').default
    },
    {
        path: '/alat',
        name: 'alat',
        component: require('./views/referensi/alat').default
    },
    {
        path: '/angkutan',
        name: 'angkutan',
        component: require('./views/referensi/angkutan').default
    },
    {
        path: '/buku',
        name: 'buku',
        component: require('./views/referensi/buku').default
    },
    {
        path: '/pengguna',
        name: 'pengguna',
        component: require('./views/referensi/users').default
    },
    {
        path: '/profil',
        name: 'profil',
        component: require('./views/profile').default
    },
    {
        path: '/unduhan',
        name: 'unduhan',
        component: require('./views/unduhan').default
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