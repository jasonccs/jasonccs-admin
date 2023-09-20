import { useRouter,useRoute } from 'vue-router';
const mRouter = useRouter()
export default {
    install: (app) => {
        app.config.globalProperties.$router = mRouter;
        // 注册全局的localStorage对象
        app.config.globalProperties.$localStorage = {
            // 存储数据
            set(key, value) {
                localStorage.setItem(key, JSON.stringify(value));
            },
            // 获取数据
            get(key) {
                const value = localStorage.getItem(key);
                return value ? JSON.parse(value) : null;
            },
            // 删除数据
            remove(key) {
                localStorage.removeItem(key);
            },
            // 清空数据
            clear() {
                localStorage.clear();
            }
        };
    },
};
