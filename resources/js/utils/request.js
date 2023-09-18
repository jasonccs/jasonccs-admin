import axios from 'axios'
import store from '../store'

// create an axios instance
const service = axios.create({
    baseURL: 'http://jason-admin.com/a/', // url = base url + request url
    timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
    config => {
        // do something before request is sent
        config.headers['Content-Type'] = 'application/json;charset=utf-8'
        config.headers['Authorization'] = 'Bearer ' + '2255555'
        return config
    },
    error => {
        // do something with request error
        console.log(error) // for debug
        return Promise.reject(error)
    }
)

const subscribers = []
let isRefreshing = true

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
        const res = response.data

        if (res.code !== 0) {
            // ACCESS TOKEN TIMEOUT
            if (res.code === 106) {
                if (isRefreshing) {
                    store.dispatch('refreshToken').then(() => {
                        subscribers.forEach((callback) => {
                            callback()
                        })
                        isRefreshing = true
                    })
                }
                isRefreshing = false

                return new Promise((resolve) => {
                    subscribers.push(() => {
                        resolve(service(response.config))
                    })
                })
            }

            // Token expires;
            else if (res.code === 104 || res.code === 107) {
                // to re-login
                this.$message.confirm('登陆已过期，是否重新登陆？', '登陆提示', {
                    confirmButtonText: '重登',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    store.dispatch('resetToken').then(() => {
                        location.reload()
                    })
                }).catch(() => {
                    isRefreshing = true
                })

                return Promise.reject("登陆已过期")
            }

            // catch 返回数据
            let defaultConfig = response.config
            if (defaultConfig["catchReturnData"] != undefined && defaultConfig["catchReturnData"]) {
                return Promise.reject(res)
            }

            // 通用错误
            Message({
                message: res.message || 'Error',
                type: 'error',
                duration: 3 * 1000
            })

            return Promise.reject(new Error(res.message || 'Error'))
        } else {
            return res
        }
    },
    error => {
        console.log('err' + error) // for debug
        Message({
            message: error.message,
            type: 'error',
            duration: 3 * 1000
        })
        return Promise.reject(error)
    }
)

export default service
