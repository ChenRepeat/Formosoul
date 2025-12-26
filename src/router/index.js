import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/Home.vue'
import Member from '@/views/Member.vue'
import ProfessorIntroduction from '@/views/ProfessorIntroduction.vue'
import Shop from '@/views/Shop.vue'
import ShoppingCart from '@/views/ShoppingCart.vue'
import MyCart from '@/components/Cart/MyCart.vue'
import CheckOut from '@/components/Cart/CheckOut.vue'
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
import Payment from '@/components/policy/payment.vue'
import BlackLogo from '@/assets/LOGO_black.svg';
import Privacypolicy from '@/components/policy/privacypolicy.vue'
import Returns from '@/components/policy/returns.vue'
import Cookies from 'js-cookie'
import { useAuthStore } from '@/stores/autoStore'

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
          path: "",     // 預設顯示，網址不改變
          name: "MyCart",
          component: MyCart,
        },
        {
          path: "checkout",
          name: "CheckOut",
          component: CheckOut,
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
        logo: BlackLogo,
        bgColor:'white',
        requiresAuth: true,
      },
      children: [
        { 
          path: 'information', 
          name: 'Information',
          component: () => import ('../components/Member/information/information.vue')  },
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
        logo: BlackLogo,
        bgColor:'white',
      },
      children: [
        { path: 'delivery', component: Delivery},
        { path: 'returns', name: 'Returns' ,component: () => import ('@/components/policy/returns.vue') },
        { path: 'privacypolicy', name: 'Privacypolicy' ,component: () => import ('@/components/policy/privacypolicy.vue') },
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
                {
          path: "product-add",
          name: "ProductAdd",
          component: () => import ('@/components/Admin/ProductAdd.vue')
        },
                {
          path: "order-details",
          name: "OrderDetails",
          component: () => import ('@/components/Admin/OrderDetails.vue')
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
    // 從 Cookie 中讀取 token 與 userRole
    const isLogin = Cookies.get('token')
    // const isAdmin = Cookies.get('userRole') === 'admin'

    // console.log(`從 ${from.path} 跳轉到 ${to.path}`)
    const authStore = useAuthStore();
    if (to.meta.requiresAuth && !isLogin) {
        alert('請先登入')
        // authStore.openLoginModal();
        // authStore.setmemberView('login');
        // authStore.setloginView('loginpage');
        return next('/')
    }

    // if (to.meta.requiresAdmin && !isAdmin) {
    //     alert('權限不足')
    //     return next('/')
    // }

    if (to.path === '/login' && isLogin) {
        return next('/')
    }

    next();
})

router.beforeEach((to, from, next) => {
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual'; 
  }
  next();
});

export default router
