<template>
    <div>
        <div v-if="ready">
            <div v-if="!loggedIn">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form action="" method="POST" @submit.prevent="login">
                            <div class="mb-3">
                                <label for="">Email</label>
                                <input v-model="loginCreds.email" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="">Password</label>
                                <input v-model="loginCreds.password" type="text" class="form-control">
                            </div>

                            <input type="submit" value="Login" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>

            <div v-else>

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
    data() {
        return {
            loggedIn: false,
            token: "",
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
        // this.loadProducts();
        axios.get('/sanctum/csrf-cookie').then(response => {
            this.ready = true;
        });
    },
    methods: {
        loadProducts() {
            axios.get('/api/products', { page: this.products.page }, {
                headers: {
                    Authorization: `Bearer ${this.token}`
                }
            }).then(resp => {
                this.products.records = resp.data.products.data;
                this.products.lastPage = resp.data.products.last_page;
            }).catch(err => {
                this.$router.push('/login');
            })
        },
        login() {
            axios.post("/api/login", this.loginCreds).then(res => {
                this.token = res.data.token;
                this.loggedIn = true;
                this.loadProducts();
            }).catch();
        }
    }
}
</script>