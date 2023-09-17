import axios from 'axios'
import store from '../store'

// create an axios instance
const service = axios.create({
    baseURL: 'http://jason-admin.com/a/', // url = base url + request url
    // withCredentials: true, // send cookies when cross-domain requests
    timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
    config => {
        // do something before request is sent
        config.headers['Content-Type'] = 'application/json;charset=utf-8'

        if (store.getters.token) {
            config.headers['Authorization'] = 'Bearer ' + '2222255555'
        }

        return config
    },
    error => {
        // do something with request error
        console.log(error) // for debug
        return Promise.reject(error)
    }
)
// request interceptor
service.interceptors.request.use(
    config => {
        // do something before request is sent
        config.headers['Content-Type'] = 'application/json;charset=utf-8'

        if (store.getters.token) {
            config.headers['Authorization'] = 'Bearer ' + '555555566666'
        }

        return config
    },
    error => {
        // do something with request error
        console.log(error) // for debug
        return Promise.reject(error)
    }
)

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        return response
    },
    error => {
        console.log('err' + error) // for debug
        this.$message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000
        })
        return Promise.reject(error)
    }
)

export default service
