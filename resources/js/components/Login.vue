<template>
    <div class="login-container">
        <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" autocomplete="on" label-position="left">

            <div class="title-container">
                <h3 class="title">
                   授权登录
                </h3>
            </div>

            <el-form-item prop="username">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
                <el-input
                    ref="username"
                    v-model="loginForm.username"
                    placeholder="输入用户名"
                    name="username"
                    type="text"
                    tabindex="1"
                    autocomplete="off"
                />
            </el-form-item>

            <el-tooltip v-model="capsTooltip" content="Caps lock is On" placement="right" manual>
                <el-form-item prop="password">
          <span class="svg-container">
            <svg-icon icon-class="password" />
          </span>
                    <el-input
                        :key="passwordType"
                        ref="password"
                        v-model="loginForm.password"
                        :type="passwordType"
                        placeholder="输入密码"
                        name="password"
                        tabindex="2"
                        autocomplete="off"
                        @keyup.native="checkCapslock"
                        @blur="capsTooltip = false"
                        @keyup.enter.native="handleLogin"
                    />
                    <span class="show-pwd" @click="showPwd">
            <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
          </span>
                </el-form-item>
            </el-tooltip>

            <el-form-item prop="captcha">
        <span class="svg-container">
          <i class="el-icon-picture-outline" />
        </span>
                <el-input
                    ref="captcha"
                    v-model="loginForm.captcha"
                    placeholder="输入验证码"
                    name="captcha"
                    type="text"
                    tabindex="3"
                    autocomplete="off"
                    class="captcha-input"
                />

                <el-tooltip effect="light" content="刷新验证码" placement="top">
          <span class="captcha-img" @click="refreshCaptcha">
            <img :src="captchaImg" title="刷新验证码" alt="刷新验证码">
          </span>
                </el-tooltip>
            </el-form-item>

            <el-button
                :loading="loading"
                type="primary"
                style="width:100%;margin-bottom:30px;"
                @click.native.prevent="handleLogin">
               登录
            </el-button>

        </el-form>

    </div>
</template>

<script>
import { validUsername } from '../utils/validate'

export default {
    name: 'Login',
    components: {  },
    data() {
        const validateUsername = (rule, value, callback) => {
            if (!validUsername(value)) {
                callback(new Error('请输入用户名'))
            } else {
                callback()
            }
        }
        const validatePassword = (rule, value, callback) => {
            if (value.length < 6) {
                callback(new Error('密码最少6位'))
            } else {
                callback()
            }
        }
        const validateCaptcha = (rule, value, callback) => {
            if (value.length != 4) {
                callback(new Error('验证码必须4位'))
            } else {
                callback()
            }
        }
        return {
            captchaImg: import('../../assets/captcha.png'),
            pubkey: "",
            loginForm: {
                username: '',
                password: '',
                captcha: '',
                captchaKey: '',
                passkeyId: '',
                passkey: '',
            },
            loginRules: {
                username: [{ required: true, trigger: 'blur', validator: validateUsername }],
                password: [{ required: true, trigger: 'blur', validator: validatePassword }],
                captcha: [{ required: true, trigger: 'blur', validator: validateCaptcha }]
            },
            passwordType: 'password',
            capsTooltip: false,
            loading: false,
            showDialog: false,
            redirect: undefined,
            otherQuery: {}
        }
    },
    watch: {

    },
    created() {
        // window.addEventListener('storage', this.afterQRScan)

        this.getPasskey()

        this.refreshCaptcha()
    },
    mounted() {
        if (this.loginForm.username === '') {
            this.$refs.username.focus()
        } else if (this.loginForm.password === '') {
            this.$refs.password.focus()
        } else if (this.loginForm.captcha === '') {
            this.$refs.captcha.focus()
        }
    },
    destroyed() {
        // window.removeEventListener('storage', this.afterQRScan)
    },
    methods: {
        checkCapslock(e) {
            const { key } = e
            this.capsTooltip = key && key.length === 1 && (key >= 'A' && key <= 'Z')
        },
        showPwd() {
            if (this.passwordType === 'password') {
                this.passwordType = ''
            } else {
                this.passwordType = 'password'
            }
            this.$nextTick(() => {
                this.$refs.password.focus()
            })
        },
        getPasskey() {
            this.$store.dispatch('passkey')
                .then(response => {
                    const headers = response.headers
                    const res = response.data
                    this.loginForm.passkeyId = headers['larke-admin-passkey-id']
                    this.loginForm.passkey = res.data.key
                })
                .catch(err => {
                    return false
                })

        },
        refreshCaptcha() {
            this.$store.dispatch('captcha')
                .then(response => {
                    const headers = response.headers
                    const res = response.data
                    const captchaKey = headers['larke-admin-captcha-id']
                    const captchaImg = res.data.captcha
                    this.loginForm.captchaKey = captchaKey
                    this.captchaImg = captchaImg
                })
                .catch(err => {
                    return false
                })
        },
        handleLogin() {
            this.$refs.loginForm.validate(valid => {
                if (valid) {
                    this.loading = true
                    this.$store.dispatch('login', this.loginForm)
                        .then(response => {
                            this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
                            this.loading = false
                        })
                        .catch(err => {
                            console.log(err)
                            this.refreshCaptcha()

                            this.loading = false
                        })
                } else {
                    console.log('error submit!!')
                    return false
                }
            })
        },

    }
}
</script>

<style lang="scss">
/* 修复input 背景不协调 和光标变色 */
/* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

$bg:#283443;
$light_gray:#fff;
$cursor: #fff;

@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
    .login-container .el-input input {
        color: $cursor;
    }
}

/* reset element-ui css */
.login-container {
    .el-input {
        display: inline-block;
        height: 47px;
        width: 85%;

        input {
            background: transparent;
            border: 0px;
            -webkit-appearance: none;
            border-radius: 0px;
            padding: 12px 5px 12px 15px;
            color: $light_gray;
            height: 47px;
            caret-color: $cursor;

            &:-webkit-autofill {
                box-shadow: 0 0 0px 1000px $bg inset !important;
                -webkit-text-fill-color: $cursor !important;
            }
        }
    }

    .el-form-item {
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        color: #454545;
    }
}
</style>

<style lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;

.login-container {
    min-height: 100%;
    width: 100%;
    background-color: $bg;
    overflow: hidden;

    .login-form {
        position: relative;
        width: 520px;
        max-width: 100%;
        padding: 160px 35px 0;
        margin: 0 auto;
        overflow: hidden;
    }

    .tips {
        font-size: 14px;
        color: #fff;
        margin-bottom: 10px;

        span {
            &:first-of-type {
                margin-right: 16px;
            }
        }
    }

    .svg-container {
        padding: 6px 5px 6px 15px;
        color: $dark_gray;
        vertical-align: middle;
        width: 30px;
        display: inline-block;
    }

    .title-container {
        position: relative;

        .title {
            font-size: 26px;
            color: $light_gray;
            margin: 0px auto 40px auto;
            text-align: center;
            font-weight: bold;
        }

        .set-language {
            color: #fff;
            position: absolute;
            top: 3px;
            font-size: 18px;
            right: 0px;
            cursor: pointer;
        }
    }

    .show-pwd {
        position: absolute;
        right: 10px;
        top: 7px;
        font-size: 16px;
        color: $dark_gray;
        cursor: pointer;
        user-select: none;
    }

    .thirdparty-button {
        position: absolute;
        right: 0;
        bottom: 6px;
    }

    .captcha-input {
        width: 65%;
    }
    .captcha-img {
        width: 100px;
        height: 39px;
        vertical-align: middle;
        display: inline-block;
        position: absolute;
        top: 5px;
        right: 10px;
    }
    .captcha-img img {
        width: 100px;
        cursor: pointer;
    }

    @media only screen and (max-width: 470px) {
        .thirdparty-button {
            display: none;
        }
    }
}
</style>
