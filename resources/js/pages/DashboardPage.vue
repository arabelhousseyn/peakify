<template>
    <div class="dashboard-page">
        <header-component />
        <router-view />
        <footer-component />
    </div>
</template>
<script>
import HeaderComponent from "../components/HeaderComponent";
import FooterComponent from "../components/FooterComponent";
export default {
    components: {FooterComponent, HeaderComponent},
    mounted() {
        this.$store.commit('CHECK_AUTH')
        console.log(this.$store.state)
    }
}
</script>
