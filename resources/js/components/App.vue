<template>
    <div>
        <nav class="sidebar dark_sidebar">
            <div class="logo d-flex justify-content-between">
                <router-link class="large_logo" style="color: white; font-size: 26px;" :to="`/dashboard`">
                    Marlenka<!--<img src="/img/logo_white.png" alt="">--></router-link>
                <router-link class="small_logo" style="color: white; font-size: 18px;" :to="`/dashboard`">Marlenka
                    <!--<img src="/img/mini_logo.png" alt="">--></router-link>
                <div class="sidebar_close_icon d-lg-none">
                    <i class="ti-close"></i>
                </div>
            </div>


            <ul id="sidebar_menu" class="metismenu">

                <li  class="">
<!--
                    <router-link :to="`/dashboard/users`">
                        <div class="nav_icon_small">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                 class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                        </div>
                        <div class="nav_title">
                            <span>User</span>
                        </div>
                    </router-link>
-->
                    <router-link :to="`/dashboard/contact_us`">
                        <div class="nav_icon_small bi bi-arrow-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                            </svg>
                        </div>
                        <div class="nav_title">
                            <span>Contact Us</span>
                        </div>
                    </router-link>
                </li>
            </ul>
        </nav>

        <section class="main_content dashboard_part large_header_bg">
            <div class="container-fluid g-0">
                <div class="row">
                    <div class="col-lg-12 p-0 ">
                        <div class="header_iner d-flex justify-content-between align-items-center">
                            <div class="nav_div">
                                <div class="sidebar_icon d-lg-none">
                                    <i class="ti-menu"></i>
                                </div>
                                <div class="line_icon open_miniSide d-none d-lg-block">
                                    <img :src="'/img/line_img.png'" alt="">
                                </div>
                            </div>
                            <div> <span class="title_span">{{pageName}}</span></div>
                            <div class="header_right d-flex justify-content-between align-items-center">
                                <div class="header_notification_warp d-flex align-items-center">
                                </div>
                                <div class="profile_info d-flex align-items-center">
                                    <div class="profile_thumb mr_20"></div>
                                    <div class="author_name">
                                        <h4 class="f_s_15 f_w_500 mb-0">{{authUser.name}}</h4>
                                    </div>
                                    <div class="profile_info_iner">
                                        <div class="profile_info_details" @click.prevent="logout">
                                            <a href="#">
                                                LogOut
                                            </a>
                                            <span class="f_s_15 f_w_500 mb-0 authUserSpan">{{authUser.name}}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_content_iner overly_inner ">
                <div class="container-fluid p-0 ">
                    <div class="">
                        <router-view :authuser="authUser" />
                    </div>
                </div>
            </div>
            <div class="footer_part">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer_iner text-center">
<!--                                <p>2024 © <a href=""> <i class="ti-heart"></i> </a><a
                                        href=""></a></p>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2/dist/sweetalert2.js';
    import 'sweetalert2/src/sweetalert2.scss';
    import Multiselect from 'vue-multiselect';
    import {PageName} from '../PageName';

    export default {

        components: {
            Swal,
            Multiselect,
        },

        props: [
            'authuser',
        ],

        watch: {

            authuser: function (newVal, oldVal) {
                this.authUser = newVal;
            },
            $route (to, from){
                this.pageName = 'Marlenka';
            }
        },

        data() {
            return {
                authUser: this.authuser,
                pageName : 'Home',
            }
        },

        mounted() {

        },

        created() {
            PageName.$on('updatePageName', data => {
               this.pageName = data ? data : 'Marlenka';
            });

        },

        methods: {

            getRout(name, param = '') {

                return getRout(name, param)
            },

            logout() {
                let self = this;
                axios.post(route('logout'))
                    .then(response => {
                        this.$router.go('Dashboard')
                    })
                    .catch(error => {
                        this.showCatchError(error);
                    })

            },

            showCatchError(err) {
                if (err.response && err.response.data) {
                    Swal.fire({
                        icon: 'error',
                        title: `${err.response.data.message}`,
                        confirmButtonText: "Ok"
                    });
                }
            },
        },

        computed: {
            currentRouteName() {
                return this.$route.name;
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
    .nav_div{
        display: flex;
        justify-content: space-around;
    }
</style>
