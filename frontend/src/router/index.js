import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import AdminDashboard from '../views/AdminDashboard.vue'


const routes = [
  { path: '/', component: LoginView },
  { path: '/admin', component: AdminDashboard }
]

export default createRouter({
  history: createWebHistory(),
  routes
})
