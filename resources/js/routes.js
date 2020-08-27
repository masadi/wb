import VueRouter from 'vue-router';


let routes = [{
        path: '/beranda',
        name: 'beranda',
        component: require('./views/dashboard').default
    },
    {
        path: '/berita',
        name: 'berita',
        component: require('./views/news/berita').default
    },
    {
        path: '/galeri',
        name: 'galeri',
        component: require('./views/news/galeri').default
    },
    {
        path: '/faq',
        name: 'faq',
        component: require('./views/news/faq').default
    },
    {
        path: '/komponen',
        name: 'komponen',
        component: require('./views/referensi/komponen').default
    },
    {
        path: '/aspek',
        name: 'aspek',
        component: require('./views/referensi/aspek').default
    },
    {
        path: '/atribut',
        name: 'atribut',
        component: require('./views/referensi/atribut').default
    },
    {
        path: '/indikator',
        name: 'indikator',
        component: require('./views/referensi/indikator').default
    },
    {
        path: '/instrumen',
        name: 'instrumen',
        component: require('./views/referensi/instrumen').default
    },
    {
        path: '/penjamin-mutu',
        name: 'penjamin_mutu',
        component: require('./views/referensi/penjamin_mutu').default
    },
    {
        path: '/sekolah',
        name: 'sekolah',
        component: require('./views/referensi/sekolah').default
    },
    {
        path: '/pengguna',
        name: 'pengguna',
        component: require('./views/referensi/users').default
    },
    {
        path: '/kuisioner/pengisian',
        name: 'pengisian_kuisioner',
        component: require('./views/kuisioner/pengisian').default
    },
    {
        path: '/detil-pengisian-kuisioner/:id',
        name: 'detil_pengisian',
        component: require('./views/kuisioner/detil-pengisian-kuisioner').default
    },
    {
        path: '/proses-pengisian-kuisioner/:id/:new_page?',
        name: 'proses_pengisian',
        component: require('./views/kuisioner/proses-pengisian-kuisioner').default
    },
    {
        path: '/rapor-mutu/hasil',
        name: 'hasil_rapor',
        component: require('./views/rapor-mutu/hasil').default
    },
    {
        path: '/rapor-mutu/pakta-integritas',
        name: 'pakta_integritas',
        component: require('./views/rapor-mutu/pakta-integritas').default
    },
    {
        path: '/hasil-verval',
        name: 'hasil_verval',
        component: require('./views/verval/hasil-verval').default
    },
    {
        path: '/kirim-laporan',
        name: 'kirim_laporan',
        component: require('./views/verval/kirim-laporan').default
    },
    {
        path: '/proses-verifikasi/:sekolah_id/:verifikator_id',
        name: 'proses_verifikasi',
        component: require('./views/verval/proses-verifikasi').default
    },
    {
        path: '/hasil-validasi',
        name: 'hasil_validasi',
        component: require('./views/pengesahan/validasi').default
    },
    {
        path: '/hasil-pengesahan',
        name: 'hasil_pengesahan',
        component: require('./views/pengesahan/pengesahan').default
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