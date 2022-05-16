<template>
    <div class="delete-category-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="300"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Voulez-vous supprimer la ville ?
                </v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="close"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="green"
                        text
                        @click="destroy"
                    >
                        <span v-if="!loading">Confirmer</span>
                        <v-progress-circular
                            v-else
                            indeterminate
                        ></v-progress-circular>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props : ['dialog','city_id'],
    data : ()=>({
        loading : false,
    }),
    methods : {
        destroy()
        {
            this.loading = true
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.delete(`/api/city/${this.city_id}`).then(e => {
                    if(e.status == 204)
                    {
                        this.$toast.open({
                            message : "Opération effectué",
                            type : 'success'
                        })
                        window.location.reload()
                    }
                })
            })
        },
        close()
        {
            this.$emit('close')
        }
    }
}
</script>
