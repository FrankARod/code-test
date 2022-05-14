<template>
    <div>
        <div v-if="ready">
            <div v-if="!loginComplete">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form action="" method="POST" @submit.prevent="login">
                            <div class="mb-3">
                                <label for="">Email</label>
                                <input v-model="loginCreds.email" :class="{
                                    'is-invalid': loginErrors.email && loginErrors.email.length > 0,
                                }" type="text" class="form-control">
                                <div v-if="loginErrors.email && loginErrors.email.length > 0" class="invalid-feedback">
                                    {{ loginErrors.email[0] }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Password</label>
                                <input v-model="loginCreds.password" :class="{
                                    'is-invalid': loginErrors.password && loginErrors.password.length > 0,
                                }" type="text" class="form-control">
                                <div v-if="loginErrors.password && loginErrors.password.length > 0"
                                    class="invalid-feedback">
                                    {{ loginErrors.password[0] }}
                                </div>
                            </div>

                            <input type="submit" value="Login" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>

            <div v-else>
                <button class="btn btn-danger" @click="logout" type="button">Logout</button>

                <div v-for="product in products.records" :key="product.id" class="card m-3">
                    <div class="card-header">{{ product.name }}</div>
                    <div class="card-body">
                        <img v-if="product.image_key" :src="product.image_url">
                        <p>{{ product.description }}</p>
                    </div>
                </div>

                <nav aria-label="Product pagination">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: products.page == 1 }">
                            <a class="page-link" href="#" aria-label="Previous" @click="
    products.page--;
loadProducts();
                            ">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li v-for="n in products.lastPage" class="page-item" :class="{ active: n == products.page }"
                            :key="n" @click="
    products.page = n;
loadProducts();
                            ">
                            <a class="page-link" href="#">{{ n }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: products.page == products.lastPage }">
                            <a class="page-link" href="#" aria-label="Next" @click="
    products.page++;
loadProducts();
                            ">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</template>

<script>
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
            loginErrors: {

            },
            products: {
                page: 1,
                records: [],
                lastPage: 1
            },
            loginCreds: {
                email: '',
                password: '',
            },
            ready: false
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
                }
            });
        },
        loadProducts() {
            axios.get('/api/products', { page: this.products.page }).then(resp => {
                this.products.records = resp.data.products.data;
                this.products.lastPage = resp.data.products.last_page;
            })
        },
        login() {
            axios.post("/api/login", this.loginCreds).then(res => {
                this.token = res.data.token;
                this.loginComplete = true;
                this.loadProducts();
            }).catch(err => {
                this.loginCreds.password = "";

                if (err.response.status == 422) {
                    this.loginErrors = err.response.data.errors;
                }
            });
        },
        logout() {
            axios.post('api/logout').then(() => {
                this.loginComplete = false;
                this.ready = false;
                this.initialize();
            })
        }
    }
}
</script>