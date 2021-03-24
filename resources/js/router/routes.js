function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/root', name: 'home', component: page('home.vue') },
  {
    path: '/user',
    component: page('user/index.vue'),
    children: [
      { path: '', redirect: { name: 'user.profile' } },
      { path: 'profile', name: 'user.profile', component: page('user/profile.vue') },
      { path: 'password', name: 'user.password', component: page('user/password.vue') }
    ]
  },
  {
    path: '/home',
    component: page('home/layout.vue'),
    children: [
      { path: '', redirect: { name: 'home.index' } },
      { path: 'index', name: 'home.index', component: page('home/index.vue') },
      {
        path: 'settings',
        component: {
          render: (c) => c('router-view')
        },
        children: [
          { path: '', redirect: { name: 'home.settings.module.create' } },
          { path: 'module/create', name: 'home.settings.module.create', component: page('home/settings/module/create.vue') }
        ]
      },
    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
