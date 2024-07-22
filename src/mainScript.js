import App from './App.vue'
import Vue from 'vue'
Vue.mixin({ methods: { t, n } })

const VueApp = Vue.extend(App)
new VueApp().$mount('#larpingapp')
