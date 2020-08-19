import VueRouter from 'vue-router';


let routes = [{
        path: '/beranda',
        component: require('./views/dashboard').default
    },
    {
        path: '/komponen',
        component: require('./views/komponen').default
    },
    {
        path: '/berita',
        component: require('./views/berita').default
    },
    {
        path: '/galeri',
        component: require('./views/galeri').default
    },
    {
        path: '/faq',
        component: require('./views/faq').default
    },
    {
        path: '/aspek',
        component: require('./views/aspek').default
    },
    {
        path: '/atribut',
        component: require('./views/atribut').default
    },
    {
        path: '/indikator',
        component: require('./views/indikator').default
    },
    {
        path: '/sekolah',
        component: require('./views/sekolah').default
    },
    {
        path: '/ptk',
        component: require('./views/ptk').default
    },
    {
        path: '/pd',
        component: require('./views/pd').default
    },
    {
        path: '/instrumen',
        component: require('./views/instrumen').default
    },
    {
        path: '/pengguna',
        component: require('./views/users').default
    },
    {
        path: '/kuisioner/pengisian',
        component: require('./views/kuisioner/pengisian').default
    },
    {
        path: '/kuisioner/progres',
        component: require('./views/kuisioner/progres').default
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
        path: '/rapor-mutu/hitung',
        component: require('./views/rapor-mutu/hitung').default,
    },
    {
        path: '/rapor-mutu/hasil',
        component: require('./views/rapor-mutu/hasil').default
    },
    {
        path: '/rapor-mutu/pakta-integritas',
        component: require('./views/rapor-mutu/pakta-integritas').default
    },
    {
        path: '/hasil-verval',
        component: require('./views/verval/hasil-verval').default
    },
    {
        path: '/hasil-supervisi',
        component: require('./views/verval/hasil-supervisi').default
    },
];


export default new VueRouter({
    path: '/app',
    component: require('./views/dashboard').default,
    base: '/app/',
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});