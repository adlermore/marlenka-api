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
                                    :apiUrl="getRout('home_slider.index')"
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
                    <PaginationComponent ref="PaginationComponent" v-if="Object.keys(homeSliderData).length > 0"
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
                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="title">
                                <b-form-input
                                        :class="[errors['title'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="homeSlider.title"
                                        type="text"
                                        placeholder="title"
                                ></b-form-input>
                                <small v-if="errors['title']" class="error-msg">{{errors['title'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="description">
                                <b-form-input
                                        :class="[errors['description'] ? 'error-border': '', 'addFormInputs']"
                                        v-model="homeSlider.description"
                                        type="text"
                                        placeholder="description"
                                ></b-form-input>
                                <small v-if="errors['description']" class="error-msg">{{errors['description'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 images">
                            <b-col v-b-tooltip.hover data-title="image"
                                   class='blockInput images title_hov_center'>
                                <label class="inputLabel">image</label>
                                <label for="image_path">
                                    <img v-if="homeSlider && homeSlider.image_path && homeSlider.image_path_src"
                                         :src="homeSlider.image_path_src"
                                         alt="image_path"/>
                                    <img v-else-if="homeSlider && homeSlider.image_path_src"
                                         :src="`${homeSlider.image_path_src}`"
                                         alt="image_path"/>
                                    <img v-else :src="`/assets/images/photo.png`" alt="photo"/>
                                </label>
                                <small v-if="errors['image_path']" class="error-msg imgError">
                                    <svg fill="none" height="11" viewBox="0 0 12 11" width="12"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd"
                                              d="M4.77931 1.16908C5.31481 0.217078 6.68471 0.217078 7.21951 1.16908L11.1255 8.11308C11.6505 9.04688 10.9764 10.1991 9.90611 10.1991H2.09341C1.02241 10.1991 0.348305 9.04688 0.873306 8.11308L4.77931 1.16908ZM6.69941 8.09978C6.69941 8.28543 6.62566 8.46348 6.49438 8.59476C6.36311 8.72603 6.18506 8.79978 5.99941 8.79978C5.81376 8.79978 5.63571 8.72603 5.50443 8.59476C5.37316 8.46348 5.29941 8.28543 5.29941 8.09978C5.29941 7.91413 5.37316 7.73608 5.50443 7.60481C5.63571 7.47353 5.81376 7.39978 5.99941 7.39978C6.18506 7.39978 6.36311 7.47353 6.49438 7.60481C6.62566 7.73608 6.69941 7.91413 6.69941 8.09978ZM5.99941 2.49978C5.81376 2.49978 5.63571 2.57353 5.50443 2.7048C5.37316 2.83608 5.29941 3.01413 5.29941 3.19978V5.29978C5.29941 5.48543 5.37316 5.66348 5.50443 5.79475C5.63571 5.92603 5.81376 5.99978 5.99941 5.99978C6.18506 5.99978 6.36311 5.92603 6.49438 5.79475C6.62566 5.66348 6.69941 5.48543 6.69941 5.29978V3.19978C6.69941 3.01413 6.62566 2.83608 6.49438 2.7048C6.36311 2.57353 6.18506 2.49978 5.99941 2.49978Z"
                                              fill="#EB5757"
                                              fill-rule="evenodd"/>
                                    </svg>
                                    {{ errors['image_path'] }}
                                </small>
                                <input
                                    id="image_path"
                                    ref="fileInput"
                                    accept=".png, .jpg, .jpeg"
                                    type="file"
                                    @change="handleFileUploadImage($event)"
                                >
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 images">
                            <b-col v-b-tooltip.hover data-title="small image"
                                   class='blockInput images title_hov_center'>
                                <label class="inputLabel">small image</label>
                                <label for="small_image_path">
                                    <img v-if="homeSlider && homeSlider.small_image_path && homeSlider.small_image_path_src"
                                         :src="homeSlider.small_image_path_src"
                                         alt="small_image_path"/>
                                    <img v-else-if="homeSlider && homeSlider.small_image_path_src"
                                         :src="`${homeSlider.small_image_path_src}`"
                                         alt="small_image_path"/>
                                    <img v-else :src="`/assets/images/photo.png`" alt="photo"/>
                                </label>
                                <small v-if="errors['small_image_path']" class="error-msg imgError">
                                    <svg fill="none" height="11" viewBox="0 0 12 11" width="12"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd"
                                              d="M4.77931 1.16908C5.31481 0.217078 6.68471 0.217078 7.21951 1.16908L11.1255 8.11308C11.6505 9.04688 10.9764 10.1991 9.90611 10.1991H2.09341C1.02241 10.1991 0.348305 9.04688 0.873306 8.11308L4.77931 1.16908ZM6.69941 8.09978C6.69941 8.28543 6.62566 8.46348 6.49438 8.59476C6.36311 8.72603 6.18506 8.79978 5.99941 8.79978C5.81376 8.79978 5.63571 8.72603 5.50443 8.59476C5.37316 8.46348 5.29941 8.28543 5.29941 8.09978C5.29941 7.91413 5.37316 7.73608 5.50443 7.60481C5.63571 7.47353 5.81376 7.39978 5.99941 7.39978C6.18506 7.39978 6.36311 7.47353 6.49438 7.60481C6.62566 7.73608 6.69941 7.91413 6.69941 8.09978ZM5.99941 2.49978C5.81376 2.49978 5.63571 2.57353 5.50443 2.7048C5.37316 2.83608 5.29941 3.01413 5.29941 3.19978V5.29978C5.29941 5.48543 5.37316 5.66348 5.50443 5.79475C5.63571 5.92603 5.81376 5.99978 5.99941 5.99978C6.18506 5.99978 6.36311 5.92603 6.49438 5.79475C6.62566 5.66348 6.69941 5.48543 6.69941 5.29978V3.19978C6.69941 3.01413 6.62566 2.83608 6.49438 2.7048C6.36311 2.57353 6.18506 2.49978 5.99941 2.49978Z"
                                              fill="#EB5757"
                                              fill-rule="evenodd"/>
                                    </svg>
                                    {{ errors['small_image_path'] }}
                                </small>
                                <input
                                    id="small_image_path"
                                    ref="fileInput"
                                    accept=".png, .jpg, .jpeg"
                                    type="file"
                                    @change="handleFileUploadSmallImage($event)"
                                >
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_top' v-b-tooltip.hover data-title="Product">
                                <multiselect
                                    v-model="homeSlider.product_id"
                                    :options="products.map(item => item = {value: item.id, text : item.name})"
                                    :multiple="false"
                                    label="text"
                                    track-by="value"
                                    placeholder="Product"
                                    :showNoResults="false"
                                    :class="[errors['product_id'] ? 'error-border': '', '']"
                                >
                                </multiselect>
                                <small v-if="errors['product_id']" class="error-msg">{{errors['product_id'] }}</small>
                            </b-col>
                        </b-row>
                    </b-container>

                    <template #modal-footer>
                        <div class="w-100 handle_button">
                            <b-button
                                    :disabled = "buttonDisabled"
                                    size="sm"
                                    class="float-right addButton"
                                    @click="sendData(homeSlider_id)"
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

        name: "homeSliderComponent",

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
                products: [],
                homeSliderData: [],
                homeSlider: {
                    title: '',
                    description: '',
                    product_id: '',
                    image_path: '',
                    image_path_src: '',
                    small_image_path: '',
                    small_image_path_src: '',
                },
                homeSlider_id: '',
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

            handleFileUploadImage(event) {
                let self = this;
                if (event.target && event.target.files && event.target.files[0]) {
                    self.homeSlider.image_path = event.target.files[0];
                    self.homeSlider.image_path_src = URL.createObjectURL(event.target.files[0]);
                }
            },

            handleFileUploadSmallImage(event) {
                let self = this;
                if (event.target && event.target.files && event.target.files[0]) {
                    self.homeSlider.small_image_path = event.target.files[0];
                    self.homeSlider.small_image_path_src = URL.createObjectURL(event.target.files[0]);
                }
            },

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
                return axios.get(route('home_slider.index') + `?page=${data.currentPage}`, {params: data})
                    .then((resp) => {
                        if (resp && resp.data && resp.data.data) {
                            self.homeSliderData = resp.data.data;
                            self.paginate.rows = resp.data.total;
                            self.paginate.total = resp.data.total;
                            self.paginate.currentPage = resp.data.current_page;
                            self.paginate.currentPageInput = self.paginate.currentPage;
                            self.paginate.lastPage = resp.data.last_page;
                            self.isBusy = false;
                            return self.homeSliderData;
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
                let data = Object.assign({}, self.homeSlider);

                if (id) {
                    data.id = id;
                }
                const formData = new FormData();
                formData.append('imgage_path', self.homeSlider.imgage_path);
                formData.append('imgage_path_src', self.homeSlider.imgage_path_src);
                formData.append('small_imgage_path', self.homeSlider.small_imgage_path);
                formData.append('small_imgage_path_src', self.homeSlider.small_imgage_path_src);
                for (let key in data) {
                    formData.append(key, data[key]);
                }
                axios.post(route('home_slider.store'), formData)
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
                self.homeSlider_id = obj ? obj.id : "";
                self.homeSlider.title = obj ? obj.title : "";
                self.homeSlider.description = obj ? obj.description : "";
                self.homeSlider.product_id = obj ? obj.product_id : "";
                self.getProducts(function () {
                    self.homeSlider.product_id = {
                        value: obj.product_id,
                        text: self.products.find((item) => item.id === obj.branch_id)['name']
                    };
                })
                self.homeSlider.image_path = "";
                self.homeSlider.image_path_src = obj ? obj.image_path_src : "";
                self.homeSlider.small_image_path = "";
                self.homeSlider.small_image_path_src = obj ? obj.small_image_path_src : "";

                self.show = true;
            },

            getProducts(callback = null) {
                let self = this;
                axios.get(route('getProducts'))
                    .then((resp) => {
                        if (resp.data.isSuccess) {
                            self.products = resp.data.data;
                            if (callback) {
                                callback();
                            }
                        } else {
                            if (resp.data.message) {
                                Swal.fire({
                                    icon: 'error',
                                    title: resp.data.message,
                                    confirmButtonText: "ok"
                                });
                            }
                        }
                    })
                    .catch((err) => {
                        self.showCatchError(err);
                    });
            },



            resetData() {
                let self = this;
                self.errors = {};
                self.homeSliderData = [];
                self.homeSlider = {
                    title : "",
                    description : "",
                    product_id : "",
                    image_path : "",
                    image_path_src : "",
                    small_image_path : "",
                    small_image_path_src : "",
                };
                self.homeSlider_id = '';
                self.addOrEdit = 'add';
                self.buttonDisabled = false;
                self.show = false;
            },

        },
    }
</script>
