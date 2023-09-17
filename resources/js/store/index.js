import {createStore} from 'vuex'
import md5 from 'js-md5'
import JSEncrypt from 'jsencrypt'
import {captcha, login, passkey} from "../api/passport.js";

export default createStore({
    state: {
        token: '',
        name: '',
        nickname: '',
        roles: []
    },
    mutations: {
        SET_TOKEN: (state, token) => {
            state.token = token
        },
        SET_NAME: (state, name) => {
            state.name = name
        },
        SET_NICKNAME: (state, nickname) => {
            state.nickname = nickname
        },
        SET_ROLES: (state, roles) => {
            state.roles = roles
        }
    },
    actions:{
        captcha({ commit }) {
            return new Promise((resolve, reject) => {
                captcha().then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error)
                })
            })
        },

        passkey({ commit }) {
            return new Promise((resolve, reject) => {
                passkey().then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error)
                })
            })
        },
        // user login
        login({ commit }, userInfo) {
            const {
                username, password,
                captcha, captchaKey,
                passkeyId, passkey
            } = userInfo

            // 密码加密
            const encrypt = new JSEncrypt();
            encrypt.setPublicKey(passkey);
            const encryptedPwd = encrypt.encrypt(md5(password));

            return new Promise((resolve, reject) => {
                login({
                    name: username.trim(),
                    password: passkeyId + encryptedPwd,
                    captcha: captcha
                }, {
                    'Larke-Admin-Captcha-Id': captchaKey
                }).then(response => {
                    const { data } = response
                    const token = data.access_token.trim()
                    const expires_in = parseInt(data.expires_in) + (Date.parse(new Date())/1000)
                    const refresh_token = data.refresh_token
                    resolve(data)
                }).catch(error => {
                    reject(error)
                })
            })
        },

    }
})
