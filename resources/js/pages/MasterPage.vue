<template>
    <div class="master-page">
        <v-app style="background-color: #f3f3f3;">
            <router-view />
            <footer-component />
        </v-app>
    </div>
</template>
<script>
import FooterComponent from "../components/FooterComponent";
export default {
    components: {FooterComponent}
}
</script>
