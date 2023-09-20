
import { createRouter,createWebHistory } from 'vue-router'

// 2. 定义一些路由：每个路由都需要映射到一个组件。
const Main = { render(){ return '月影WEB 欢迎大家来学习各种技术知识！'} }
const routes = [
    { path: '/', component:() => import('../components/Clients.vue') },
    { path: '/telescope', component: Main },
    {
        path: '/',
        name: 'supplier',
        component: () =>import('../components/Clients.vue'),
        meta: {
            icon: 'ion:grid-outline',
            title: '供应商',
            orderNo: 4,
        },
    },
    {
        path: '/login2',
        name: 'login2',
        component: () =>import('../components/Clients.vue'),
        meta: {
            title: '登录',
        },
        children: [
            // 公司列表-所有公司的列表
            {
                path: "company",
                component: (resolve) => require(["@/views/basics/company/index"], resolve),
                name: "Company",
                meta: { title: "公司列表" },
            },
        ]
    }
]

// 3. 创建路由实例并传递 `routes` 配置。
const router = createRouter({
    // 内部提供了 history 模式的实现。为了简单起见，我们在这里使用 hash 模式。
    history: createWebHistory(),
    routes, // `routes: routes` 的缩写
})
export default router;

// const install  = (app) => {
//     const router = createRouter({
//         history: createWebHistory(),
//         routes
//     });
//     // 将router实例绑定到app的全局属性$router上
//     app.config.globalProperties.$router = router;
//     // 在app中使用router
//     app.use(router);
// };
//
// // 导出插件
// export default {
//     install
// };

