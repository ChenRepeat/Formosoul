import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Member from '@/views/Member.vue'
import ProfessorIntroduction from '@/views/ProfessorIntroduction.vue'
import Shop from '@/views/Shop.vue'
import News from '@/views/News.vue'
import Policy from '@/views/Policy.vue'
import Classes from '@/views/Classes.vue'
import AnnualEvent from '@/views/AnnualEvent.vue'
import SurvivalGuide from '@/views/SurvivalGuide.vue'
import MemberOrders from '@/components/Member/Orders/MemberOrders.vue'
import Information from '@/components/Member/information/information.vue'
import Changepassword from '@/components/Member/changepassword/changepassword.vue'
import Mycollections from '@/components/Member/mycollections/mycollections.vue'
import Coupons from '@/components/Member/coupons/coupons.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/news',
      name: 'News',
      component: News,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/About.vue'),
    },
    {
      path: '/annualevent',
      name: 'AnnualEvent',
      component: AnnualEvent,
    },
    {
      path: '/professorsintroduction',
      name: 'ProfessorsIntroduction',
      component: ProfessorIntroduction,
    },
    {
      path: '/shop',
      name: 'Diagon Alley',
      component: Shop,
    },
    {
      path: '/survivalguide',
      name: 'SurvivalGuide',
      component: SurvivalGuide,
    },
    {
      path: '/classes',
      name: 'Classes',
      component: Classes,
    },
    {
      path: '/member',
      name: 'Member',
      component: Member,
    },

    {
      path: '/policy',
      name: 'Policy',
      component: Policy,
      children:[
        {path: 'information', component: Information},  
        {path: 'changepassword', component: Changepassword},  
        {path: 'Orderslist', component: MemberOrders},  
        {path: 'mycollections', component: Mycollections},  
        {path: 'coupons', component: Coupons},  
      ]
    },
    
  ],
})

export default router
