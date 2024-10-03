<template>
    <div>
        <div class="infoAfterTable">
            <b-row class="justify-content-start">
                <b-col class="showCount mt-1 fontWeight mw-120">
                    Total
                </b-col>
                <b-col class="totalRows"> {{total}}</b-col>
            </b-row>
            <div>
                <b-row class="d-flex flex-nowrap justify-content-center align-items-center" v-if="total > perPage">
                    <b-col sm="3" md="3" class="my-1 pagination_block me-5">
                        <b-pagination
                                v-model="currentPage"
                                @change="currentPageChange"
                                :total-rows="total"
                                :per-page="perPage"
                                align="fill"
                                size="sm"
                                class="my-0"
                        ></b-pagination>
                    </b-col>
                    <b-col sm="1" md="1" class="my-1">
                        <b-form-input
                                class="currentPageInput"
                                @change="changePageInp"
                                v-model="currentPageInput"
                                type="text"
                                placeholder=""
                        ></b-form-input>
                    </b-col>
                </b-row>
                <div class="parentShowCount">
                    <b-row class="justify-content-start">
                        <b-col sm="7" md="7" class="showCount mt-1 fontWeight">
                            Showed count
                        </b-col>
                        <b-col sm="3" md="3">
                            <b-form-input
                                    class="currentPageInput"
                                    v-model="perPage"
                                    type="number"
                                    placeholder=""
                            ></b-form-input>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PaginationComponent",

        props: ['paginate'],

        data() {
            return {
                currentPage: this.paginate.currentPage,
                currentPageInput: this.paginate.currentPageInput,
                perPage: this.paginate.perPage,
                lastPage: this.paginate.lastPage,
                rows: this.paginate.rows,
                total: this.paginate.total,
            }
        },

        watch: {
            'paginate.currentPage': function(n, o) {
                this.currentPage = n;
            },
            'paginate.currentPageInput': function(n, o) {
                this.currentPageInput = n;
            },
            'paginate.perPage': function(n, o) {
                if (n < 1) {
                    this.perPage = 1;
                    this.paginate.perPage = 1;
                }else{
                    this.perPage = n;
                }
                this.currentPage = 1;
                this.paginate.currentPage = 1;

            },
            'perPage': function(n, o) {
                if (n < 1) {
                    this.perPage = 1;
                }else{
                    this.perPage = n;
                }
                this.currentPage = 1;
                this.$emit('loadAfterChangePage', this.currentPage,this.perPage);

            },
            'paginate.lastPage': function(n, o) {
                this.lastPage = n;
            },
            'paginate.rows': function(n, o) {
                this.rows = n;
            },
            'paginate.total': function(n, o) {
                this.total = n;
            },
        },

        methods: {

            changePageInp() {
                this.currentPage = ((this.currentPageInput > 1) ? this.currentPageInput : 1);
                if (this.currentPage > this.lastPage)
                    this.currentPage = parseInt(this.lastPage);
                if (this.currentPageInput > this.lastPage);
                    this.currentPageInput = parseInt(this.lastPage);
                this.$emit('loadAfterChangePage', this.currentPage,'');
            },

            currentPageChange(currentPage) {
                this.currentPageInput = currentPage;
                this.changePageInp();
            },
        }
    }
</script>

<style scoped>

</style>
