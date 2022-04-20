<template>
    <div class="dashboard-page">
        <header-component />
        <v-main>
            <router-view />
        </v-main>
    </div>
</template>
<script>
import HeaderComponent from "../components/HeaderComponent";
export default {
    mounted() {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.$store.state.user.token}`
        this.$store.commit('CHECK_AUTH')
    }
}
</script>
