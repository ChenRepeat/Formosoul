import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/Home.vue'
import Member from '@/views/Member.vue'
import ProfessorIntroduction from '@/views/ProfessorIntroduction.vue'
import Shop from '@/views/Shop.vue'
import ShoppingCart from '@/views/ShoppingCart.vue'
import ShoppingCheck from '@/components/Cart/ShoppingCheck.vue'
import OrderSuccess from '@/components/Cart/OrderSuccess.vue'
import News from '@/views/News.vue'
import Policy from '@/views/Policy.vue'
import Classes from '@/views/Classes.vue'
import AnnualEvent from '@/views/AnnualEvent.vue'
import FestivalDetail from '@/views/FestivalDetail.vue' 
import MemberOrders from '@/components/Member/Orders/MemberOrders.vue'
import Information from '@/components/Member/information/information.vue'
import Changepassword from '@/components/Member/changepassword/changepassword.vue'
import Mycollections from '@/components/Member/mycollections/mycollections.vue'
import Coupons from '@/components/Member/coupons/coupons.vue'
import MemberOrderscontain from '@/components/Member/Orders/MemberOrderscontain.vue'
import SurvivalRules from "@/views/SurvivalRules.vue";
import SurvivalGuide from '@/components/SurvivalGuides/SurvivalGuide.vue'
import NightMarketMap from '@/components/SurvivalGuides/NightMarketMap.vue'
import ConvenienceStore from '@/components/SurvivalGuides/ConvenienceStore.vue'
import ProductDetail from '@/components/SHOP/ProductDetail.vue'
import ProductList from '@/components/SHOP/ProductList.vue'
import Delivery from '@/components/policy/delivery.vue'
import Returns from '@/components/policy/returns.vue'
import Privacypolicy from '@/components/policy/privacypolicy.vue'
import Payment from '@/components/policy/payment.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
      meta:{ 
        display: 'none',
      },
    },
    {
      path: '/news',
      name: 'News',
      meta:{ 
        bgColor:'transparent',
      },
      component: News,
    },
    {
    // :id 是動態參數
    path: '/news/:id', 
    name: 'NewsCardDetail',
    component: () => import('../components/News/NewsCardDetail.vue')
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
      path: '/annualevent/:slug',          
      name: 'FestivalDetail',              
      component: FestivalDetail,
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
      // 重新導向 能夠讓點入頁面時導向另一個網址
      redirect: '/shop/productList',
      meta:{ 
        bgColor:'transparent',
      },
      children: [
        {
          path: "productlist",
          name: "ProductList",
          component: ProductList,
        },
        {
          path: "productdetail",
          name: "ProductDetail",
          component: ProductDetail,
        },
      ],
    },
    {
      path: '/shoppingcart',
      name: 'ShoppingCart',
      component: ShoppingCart,
      meta:{ 
        bgColor:'transparent',
      },
      children: [
        {
          path: "shoppingcheck",
          name: "ShoppingCheck",
          component: ShoppingCheck,
        },
        {
          path: "ordersuccess",
          name: "OrderSuccess",
          component: OrderSuccess,
        },
      ],
    },
    {
      path: '/survivalrules',
      name: 'SurvivalRules',
      component: SurvivalRules,
      meta:{ 
        bgColor:'transparent',
      },
      children: [
        {
          path: '',
          name: 'SurvivalGuide',
          component: SurvivalGuide,
        },
        {
          path: "nightmarketmap",
          name: "NightMarketMap",
          component: NightMarketMap,
        },
        {
          path: "conveniencestore",
          name: "ConvenienceStore",
          component: ConvenienceStore,
        },
        { path: '/survivalguide', redirect: '/survivalrules' },
      ],
    },
    {
      path: '/classes',
      name: 'Classes',
      component: Classes,
    },
    
    {
      path: '/member',
      name: 'Member',
      // 重新導向 能夠讓點入頁面時導向另一個網址
      redirect: '/member/information',
      component: Member,
      meta:{ 
        logo: `${import.meta.env.BASE_URL}logo_black.svg`,
        bgColor:'white',
      },
      children: [
        { path: 'information', component: Information },
        { path: 'changepassword', component: Changepassword },
        {
          path: 'orderslist', component: MemberOrders,
          children: [
            { path: 'orderscontain', component: MemberOrderscontain },
          ],
        },
        { path: 'mycollections', component: Mycollections },
        { path: 'coupons', component: Coupons },
        { path: 'orderscontain', component: MemberOrderscontain }
      ],
    },
    
    {
      path: '/policy',
      name: 'Policy',
      redirect: '/policy/delivery',
      component: Policy,
      meta:{ 
        logo: `${import.meta.env.BASE_URL}logo_black.svg`,
        bgColor:'white',
      },
      children: [
        { path: 'delivery', component: Delivery},
        { path: 'returns', component: Returns},
        { path: 'privacypolicy', component: Privacypolicy},
        { path: 'payment', component: Payment},
      ]
    },
    //後台
    {
      path: "/admin",
      component: () => import ('@/views/Admin/AdminDashboard.vue'),
      meta: { layout: 'admin' },
      children: [
        {
          path: '', 
          redirect: { name: 'MemberManagement' }
        },
        {
          path: "member-management",
          name: "MemberManagement",
          component: () => import ('@/components/Admin/MemberManagement.vue')
        },
        {
          path: "product-management",
          name: "ProductManagement",
          component: () => import ('@/components/Admin/ProductManagement.vue')
        },
        {
          path: "order-management",
          name: "OrderManagement",
          component: () => import ('@/components/Admin/OrderManagement.vue')
        },
                {
          path: "coupon-management",
          name: "CouponManagement",
          component: () => import ('@/components/Admin/CouponManagement.vue')
        },
                {
          path: "news-management",
          name: "NewsManagement",
          component: () => import ('@/components/Admin/NewsManagement.vue')
        },
                {
          path: "annual-event-management",
          name: "AnnualEventManagement",
          component: () => import ('@/components/Admin/AnnualEventManagement.vue')
        },
      ],
    },
  ],
scrollBehavior(to, from, savedPosition) {
    return new Promise((resolve) => {

        if (savedPosition) {
          resolve(savedPosition)
        } 
        // 2. 如果是去新頁面 (按連結進去的)
        else {
          resolve({ top: 0 })
        }

    })
  }
})
router.beforeEach((to, from, next) => {
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual'; 
  }
  next();
});

export default router
