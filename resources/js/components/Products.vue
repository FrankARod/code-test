<template>
    <div>
        <h1 class="mt-3 px-3">Stuff for Sale</h1>
        <div v-if="ready">
            <div v-if="!loginComplete">
                <div class="card mt-3">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="POST" @submit.prevent="login">
                            <div class="mb-3">
                                <label for="">Email</label>
                                <input v-model="loginCreds.email"
                                    :class="{ 'is-invalid': loginErrors.email && loginErrors.email.length > 0 }"
                                    type="text" class="form-control">
                                <div v-if="loginErrors.email && loginErrors.email.length > 0" class="invalid-feedback">
                                    {{ loginErrors.email[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input v-model="loginCreds.password"
                                    :class="{ 'is-invalid': loginErrors.password && loginErrors.password.length > 0 }"
                                    type="text" class="form-control">
                                <div v-if="loginErrors.password && loginErrors.password.length > 0"
                                    class="invalid-feedback">{{ loginErrors.password[0] }}</div>
                            </div>

                            <spinner-button :loading="loggingIn" class="btn-primary">
                                Login
                            </spinner-button>
                        </form>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="text-end px-3">
                    <button class="btn btn-primary me-1" @click="openCreateModal">Add Product</button>
                    <spinner-button :loading="loggingOut" class="btn-secondary" @click.native="logout">
                        Logout
                    </spinner-button>
                </div>

                <div v-show="products.records.length === 0" class="px-3 my-3 text-center">No Products</div>

                <div v-for="product in products.records" :key="product.id" class="card m-3">
                    <div class="card-header">{{ product.name }}</div>
                    <div class="card-body">
                        <img v-if="product.image_key" :src="product.image_url" class="img-fluid">

                        <p>{{ product.description }}</p>

                        <p>${{ product.price }}</p>

                        <div class="text-end">
                            <button class="btn btn-sm btn-danger" @click="openDeleteModal(product)">Delete
                                Product</button>
                        </div>
                    </div>
                </div>

                <nav aria-label="Product pagination" class="px-3">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: products.page == 1 }">
                            <a class="page-link" href="#" aria-label="Previous"
                                @click="products.page--; loadProducts();"><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <li v-for="n in products.lastPage" class="page-item" :class="{ active: n == products.page }"
                            :key="n" @click="products.page = n; loadProducts();">
                            <a class="page-link" href="#">{{ n }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: products.page == products.lastPage }">
                            <a class="page-link" href="#" aria-label="Next"
                                @click="products.page++; loadProducts();"><span aria-hidden="true">&raquo;</span></a>
                        </li>
                    </ul>
                </nav>

                <div ref="deleteProdModal" class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete {{ prodToDelete.name }}?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <spinner-button :loading="deleting" class="btn-danger" @click.native="deleteProduct">
                                    Delete
                                </spinner-button>
                            </div>
                        </div>
                    </div>
                </div>

                <div ref="editProdModal" class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form @submit.prevent="saveProduct">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="input-group">
                                            <input id="name" v-model="editProdInput.name"
                                                :class="{ 'is-invalid': editProdErrors.name && editProdErrors.name.length > 0 }"
                                                class="form-control" type="text" name="name" />
                                            <div v-if="editProdErrors.name && editProdErrors.name.length > 0"
                                                class="invalid-feedback">{{ editProdErrors.name[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <div class="input-group">
                                            <textarea id="description" v-model="editProdInput.description"
                                                :class="{ 'is-invalid': editProdErrors.description && editProdErrors.description.length > 0 }"
                                                class="form-control" type="text" name="description"></textarea>
                                            <div v-if="editProdErrors.description && editProdErrors.description.length > 0"
                                                class="invalid-feedback">{{ editProdErrors.description[0] }}</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <div class="input-group">
                                            <input id="price" v-model="editProdInput.price"
                                                :class="{ 'is-invalid': editProdErrors.price && editProdErrors.price.length > 0 }"
                                                class="form-control" type="number" step="0.01" name="price" />
                                            <div v-if="editProdErrors.price && editProdErrors.price.length > 0"
                                                class="invalid-feedback">{{ editProdErrors.price[0] }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                    <spinner-button :loading="saving" class="btn-primary" type="submit">
                                        Save Product
                                    </spinner-button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

    <script>
let bootstrap = require("bootstrap");

export default {
    props: {
        loggedIn: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loginComplete: this.loggedIn,
            loginErrors: {},
            products: {
                page: 1,
                records: [],
                lastPage: 1
            },
            loginCreds: {},
            ready: false,
            prodToDelete: {},
            prodToUpdateID: null,
            editProdInput: {},
            editProdErrors: {},
            editProdModal: null,
            deleteProdModal: null,
            deleting: false,
            saving: false,
            loggingIn: false,
            loggingOut: false
        }
    },
    mounted() {
        this.initialize();
    },
    methods: {
        initialize() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                this.ready = true;

                if (this.loginComplete) {
                    this.loadProducts();
                    this.$nextTick(() => {
                        this.editProdModal = new bootstrap.Modal(this.$refs.editProdModal);
                        this.deleteProdModal = new bootstrap.Modal(this.$refs.deleteProdModal);
                    });
                }
            });
        },
        login() {
            this.loggingIn = true
            axios.post("/api/login", this.loginCreds).then(res => {
                this.loginComplete = true;
                this.loadProducts();
                this.$nextTick(() => {
                    this.editProdModal = new bootstrap.Modal(this.$refs.editProdModal);
                    this.deleteProdModal = new bootstrap.Modal(this.$refs.deleteProdModal);
                });
            }).catch(err => {
                this.loginCreds.password = "";

                if (err.response.status == 422) {
                    this.loginErrors = err.response.data.errors;
                }
            }).finally(() => {
                this.loggingIn = false
            });
        },
        logout() {
            this.loggingOut = true;
            axios.post('api/logout')
                .then(() => {
                    this.loginComplete = false;
                    this.ready = false;
                    this.initialize();
                })
                .finally(() => {
                    this.loggingOut = false
                })
        },
        loadProducts() {
            axios.get('/api/products', { page: this.products.page }).then(resp => {
                this.products.records = resp.data.products.data;
                this.products.lastPage = resp.data.products.last_page;
            })
        },
        openCreateModal() {
            this.editProdInput = {};
            this.editProdModal.show();
        },
        saveProduct() {
            this.saving = true
            axios.post('/api/products', this.editProdInput)
                .then(() => {
                    this.loadProducts();
                    this.editProdModal.hide();
                    this.editProdInput = {};
                    this.editProdErrors = {};
                })
                .catch((err) => {
                    if (err.response.status == 422) {
                        this.editProdErrors = err.response.data.errors;
                    }
                })
                .finally(() => {
                    this.saving = false;
                });
        },
        openDeleteModal(product) {
            this.prodToDelete = Object.assign({}, product);
            this.deleteProdModal.show();
        },
        deleteProduct() {
            this.deleting = true;

            axios.delete(`/api/products/${this.prodToDelete.id}`).then(() => {
                this.loadProducts();
                this.deleteProdModal.hide();
            }).finally(() => {
                this.deleting = false;
            });
        }
    }
}
</script>
