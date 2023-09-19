import { createRouter, createWebHistory } from 'vue-router';


const routes= [
    {
        path: "/telescope",
        name: "About",
        component: () => import( "../components/Clients.vue")
    },
    {
        path: "/login2",
        name: "About",
        component: () => import( "../components/Clients.vue")
    }
];
const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

router.beforeEach((to, from, next) => {
    // 在导航之前执行的逻辑
    // 可以在此处设置授权头
    // 假设您的授权头名称为 'Authorization'，并且授权令牌存储在 localStorage 中
    const token = localStorage.getItem('token');
    if (token) {
        // 设置授权头
        to.headers = {
            ...to.headers,
            Authorization:   Bearer `${token}`  ,
        };
    }
    // refreshPage();
    next();
});
function refreshPage() {
    location.reload(); // 刷新页面
}
export default router;

