<template>
    <div class="footer-component">
        <v-footer padless>
            <v-col
                class="text-center"
                cols="12"
            >
                {{ date }} — <strong>Peakify</strong>
            </v-col>
        </v-footer>
    </div>
</template>

<script>
export default {
    data : ()=>({
        date : 2022,
    }),
    mounted() {
        let date = new Date().getFullYear()

        this.date = (date > this.date) ? `${date}-${this.date}` : this.date
    }
}
</script>
