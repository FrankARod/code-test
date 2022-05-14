<template>
    <button :disabled="loading" type="submit" class="btn">
        <span v-show="loading" class="spinner-border spinner-border-sm text-white" role="status"
            aria-hidden="true"></span>
        <span v-show="loading" class="visually-hidden">Loading...</span>

        <span v-show="!loading">
            <slot></slot>
        </span>
    </button>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            required: false
        }
    }
}
</script>