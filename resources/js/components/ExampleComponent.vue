<template>
    <div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import 'sweetalert2/src/sweetalert2.scss'
    import Multiselect from 'vue-multiselect'
    import PaginationComponent from "./PaginationComponent";
    import {PageName} from '../PageName';

    export default {

        components: {
            Swal,
            Multiselect,
            PaginationComponent,
        },

        props: [
            'authuser',
        ],

        watch: {

            authuser: function (newVal, oldVal) {
                this.authUser = newVal;
            },

        },

        data() {
            return {
                authUser: this.authuser,
            }
        },

        mounted() {
            PageName.$emit('updatePageName', "Home");

        },

        created() {
        },

        methods: {

            getRout(name, param = '') {

                return getRout(name, param)
            },



            showCatchError(err) {
                if (err.response && err.response.data) {
                    Swal.fire({
                        icon: 'error',
                        title: `${err.response.data.message}`,
                        confirmButtonText: "ok"
                    });
                }
            },

        },
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">
</style>
