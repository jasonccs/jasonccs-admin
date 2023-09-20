import { inject } from 'vue';

export default {
    setup() {
        const router = inject('router');

        const navigateToRoute = (path) => {
            router.push(path);
        };

        return {
            navigateToRoute
        };
    }
};
