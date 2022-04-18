<template>
    <div class="dashboard-page">
        <header-component />
        <router-view />
    </div>
</template>
<script>
import HeaderComponent from "../components/HeaderComponent";
export default {
    components: {HeaderComponent}
}
</script>
