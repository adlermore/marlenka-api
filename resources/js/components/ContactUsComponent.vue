<template>
    <div>
        <div class="row justify-content-center " style="margin-top: 50px; ">
            <div>
<!--                <b-row class="justify-content-end">
                    <b-col sm="5" md="5" class="d-flex table_navigate justify-content-end pb-1">
                        <b-button
                                size="sm"
                                class="addButton toggleTableBtm"
                                @click="toggleTable"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-border-all" viewBox="0 0 16 16">
                                <path d="M0 0h16v16H0V0zm1 1v6.5h6.5V1H1zm7.5 0v6.5H15V1H8.5zM15 8.5H8.5V15H15V8.5zM7.5 15V8.5H1V15h6.5z"/>
                            </svg>
                        </b-button>

                    </b-col>
                </b-row>-->
                <div class="parent_card_pagination">
                    <div class="card">
                        <div>
                            <b-table
                                    :class="showTableLine ? 'table_line': 'table_grid'"
                                    :stacked="!showTableLine"
                                    :responsive="showTableLine"
                                    ref="table"
                                    :busy="isBusy"
                                    :hover="showTableLine"
                                    :items="getData"
                                    :fields="fields"
                                    :sort-by.sync="sortBy"
                                    :sort-desc.sync="sortDesc"
                                    :apiUrl="getRout('contact_us.index')"
                                    :perPage="paginate.perPage"
                                    :currentPage="paginate.currentPage"
                                    :striped="showTableLine"
                                    :small="showTableLine"
                                    :bordered="showTableLine"
                                    :filter="filter"
                            >
                                <template #table-busy>
                                    <div class="text-center text-danger my-2">
                                        <b-spinner class="align-middle"></b-spinner>
                                        <strong>loaded</strong></div>
                                </template>
                                <template #cell(actions)="row">
                                    <a class="btn btn-inverse-warning  p-1 a_position"
                                       href="javascript:void(0)" @click="showAddOrEditForm(row.item, 'edit')">
                                        <i class="bi bi-pencil title_hov_top" data-title="edit">
                                            <svg v-b-tooltip.hover
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 fill="currentColor"
                                                 class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                            </svg>
                                        </i>
                                    </a>
                                </template>
                            </b-table>
                        </div>
                    </div>
                    <PaginationComponent ref="PaginationComponent" v-if="Object.keys(contactUsData).length > 0"
                                     :paginate="paginate"
                                     @loadAfterChangePage="loadAfterChangePage">
                </PaginationComponent>
                </div>
                <b-modal
                        class="addModal"
                        v-model="show"
                        :header-text-variant="headerTextVariant"
                        :header-bg-variant="headerBgVariant"
                >
                    <template #modal-title>
                        <b-row>
                            <b-col sm="10" md="10" class="title_popup fs-15 lh-2">Edit</b-col>
                        </b-row>
                    </template>
                    <b-container fluid>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="phone_number">
                                <b-form-input
                                        :class="[errors['phone_number'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.phone_number"
                                        type="text"
                                        placeholder="phone_number"
                                ></b-form-input>
                                <small v-if="errors['phone_number']" class="error-msg">{{errors['phone_number'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="whatsapp">
                                <b-form-input
                                        :class="[errors['whatsapp'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.whatsapp"
                                        type="text"
                                        placeholder="whatsapp"
                                ></b-form-input>
                                <small v-if="errors['whatsapp']" class="error-msg">{{errors['whatsapp'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="email">
                                <b-form-input
                                        :class="[errors['email'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.email"
                                        type="text"
                                        placeholder="email"
                                ></b-form-input>
                                <small v-if="errors['email']" class="error-msg">{{errors['email'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="facebook_link">
                                <b-form-input
                                        :class="[errors['facebook_link'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.facebook_link"
                                        type="text"
                                        placeholder="facebook_link"
                                ></b-form-input>
                                <small v-if="errors['facebook_link']" class="error-msg">{{errors['facebook_link'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="instagram_link">
                                <b-form-input
                                        :class="[errors['instagram_link'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.instagram_link"
                                        type="text"
                                        placeholder="instagram_link"
                                ></b-form-input>
                                <small v-if="errors['instagram_link']" class="error-msg">{{errors['instagram_link'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="gmail_link">
                                <b-form-input
                                        :class="[errors['gmail_link'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.gmail_link"
                                        type="text"
                                        placeholder="gmail_link"
                                ></b-form-input>
                                <small v-if="errors['gmail_link']" class="error-msg">{{errors['gmail_link'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="linkedin_link">
                                <b-form-input
                                        :class="[errors['linkedin_link'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.linkedin_link"
                                        type="text"
                                        placeholder="linkedin_link"
                                ></b-form-input>
                                <small v-if="errors['linkedin_link']" class="error-msg">{{errors['linkedin_link'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="twitter_link">
                                <b-form-input
                                        :class="[errors['twitter_link'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.twitter_link"
                                        type="text"
                                        placeholder="twitter_link"
                                ></b-form-input>
                                <small v-if="errors['twitter_link']" class="error-msg">{{errors['twitter_link'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">

                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="youtube_link">
                                <b-form-input
                                        :class="[errors['youtube_link'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="contactUs.youtube_link"
                                        type="text"
                                        placeholder="youtube_link"
                                ></b-form-input>
                                <small v-if="errors['youtube_link']" class="error-msg">{{errors['youtube_link'] }}</small>
                            </b-col>
                        </b-row>
                    </b-container>

                    <template #modal-footer>
                        <div class="w-100 handle_button">
                            <b-button
                                    :disabled = "buttonDisabled"
                                    size="sm"
                                    class="float-right addButton"
                                    @click="sendData(contactUs_id)"
                            >
                               Edit
                            </b-button>
                        </div>
                    </template>
                </b-modal>

            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2/dist/sweetalert2.js';
    import 'sweetalert2/src/sweetalert2.scss';
    import Multiselect from 'vue-multiselect';
    import PaginationComponent from "./PaginationComponent";
    import {PageName} from '../PageName';

    export default {

        components: {
            Swal,
            Multiselect,
            PaginationComponent,
        },

        name: "ContactUsComponent",

        props: [
            'authuser',
        ],

        watch: {
            authuser: function (newVal, oldVal) {
                this.authUser = newVal;
            },
            filter: {
                handler(newVal, oldVal) {
                    this.paginate.currentPage  = 1;
                },
                deep: true,
            },
        },

        data() {
            return {

                //default
                authUser: this.authuser,
                paginate: {
                    perPage: 10,
                    currentPage: 1,
                    total : '',
                    currentPageInput : '',
                    lastPage : '',
                },
                errors: {},
                //default

                //BTable
                showTableLine: false,
                fields: [
                    {
                        key: 'id',
                        label: 'ID',
                        sortable: true
                    },
                    {
                        key: 'actions',
                        label: "Action",
                        sortable: false,
                        formatter: (val, key, row) => {
                            return '';
                        }
                    }
                ],
                isBusy: false,
                total: '',
                sortBy: 'id',
                sortDesc: false,
                filter: [
                    {
                        text : '',
                        key : '',
                    }
                ],
                //BTable

                //Modal
                show: false,
                headerBgVariant: 'custom',
                headerTextVariant: 'white',
                //Modal

                //Data
                contactUsData: [],
                contactUs: {
                    phone_number: '',
                    whatsapp: '',
                    email: '',
                    facebook_link: '',
                    instagram_link: '',
                    gmail_link: '',
                    linkedin_link: '',
                    twitter_link: '',
                    youtube_link: '',
                },
                contactUs_id: '',
                addOrEdit: 'add',
                buttonDisabled: false,
                //Data


            }
        },

        mounted() {
            let self = this;
            PageName.$emit('updatePageName', "Contact Us");

        },

        created() {

        },

        methods: {

            updateFilter(newFilter){
                this.filter = newFilter;
            },

            toggleTable() {
                this.showTableLine = !this.showTableLine;
            },

            loadAfterChangePage(currentPage,perPage){
                if(currentPage){
                    this.paginate.currentPage = currentPage;
                }
                if(perPage){
                    this.paginate.perPage = perPage;

                }
            },

            getData(data) {
                let self = this;
                return axios.get(route('contact_us.index') + `?page=${data.currentPage}`, {params: data})
                    .then((resp) => {
                        if (resp && resp.data && resp.data.data) {
                            self.contactUsData = resp.data.data;
                            self.paginate.rows = resp.data.total;
                            self.paginate.total = resp.data.total;
                            self.paginate.currentPage = resp.data.current_page;
                            self.paginate.currentPageInput = self.paginate.currentPage;
                            self.paginate.lastPage = resp.data.last_page;
                            self.isBusy = false;
                            return self.contactUsData;
                        }
                    })

                    .catch((err) => {
                        self.showCatchError(err);
                    });
            },

            sendData(id) {
                if (this.buttonDisabled) {
                    return;
                }
                this.buttonDisabled = true;
                let self = this;
                self.errors = {};
                let data = Object.assign({}, self.contactUs);

                if (id) {
                    data.id = id;
                }
                axios.post(route('contact_us.store'), data)
                    .then((data) => {
                        if (data && data.data && data.data.isSuccess) {
                            self.show = false;
                            self.resetData();
                            self.$refs.table.refresh();
                        }
                        this.buttonDisabled = false;
                    })
                    .catch((err) => {
                        if (err && err.response && err.response.data) {
                            let errors = err.response.data;
                            for (let i in errors) {
                                Vue.set(self.errors, i, errors[i][0]);
                            }
                        }
                        this.buttonDisabled = false;

                    })
            },


            getRout(param) {
                return getRout(param)
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

            showAddOrEditForm(obj = '', addOrEdit = 'add') {

                let self = this;
                self.errors = {};
                self.addOrEdit = addOrEdit;
                self.contactUs_id = obj ? obj.id : "";
                self.contactUs.phone_number = obj ? obj.phone_number : "";
                self.contactUs.whatsapp = obj ? obj.whatsapp : "";
                self.contactUs.email = obj ? obj.email : "";
                self.contactUs.facebook_link = obj ? obj.facebook_link : "";
                self.contactUs.instagram_link = obj ? obj.instagram_link : "";
                self.contactUs.gmail_link = obj ? obj.gmail_link : "";
                self.contactUs.linkedin_link = obj ? obj.linkedin_link : "";
                self.contactUs.twitter_link = obj ? obj.twitter_link : "";
                self.contactUs.youtube_link = obj ? obj.youtube_link : "";

                self.show = true;
            },

            resetData() {
                let self = this;
                self.errors = {};
                self.contactUsData = [];
                self.contactUs = {
                    phone_number : "",
                    whatsapp : "",
                    email : "",
                    facebook_link : "",
                    instagram_link : "",
                    gmail_link : "",
                    linkedin_link : "",
                    twitter_link : "",
                    youtube_link : "",
                };
                self.contactUs_id = '';
                self.addOrEdit = 'add';
                self.buttonDisabled = false;
                self.show = false;
            },

        },
    }
</script>
