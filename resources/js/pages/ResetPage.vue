<template>
    <div class="reset-page">
        <v-container fluid>
            <v-row class="justify-content-center">
                <h1>Soon.</h1>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data : ()=>({
        token : window.location.search.replace('?token=','')
    }),
    methods : {
        check()
        {
            return (localStorage.getItem('token') == this.token) ? true : false
        }
    },
    mounted() {
       if(!this.check())
       {
           this.$router.push('/')
       }
    }
}
</script>
