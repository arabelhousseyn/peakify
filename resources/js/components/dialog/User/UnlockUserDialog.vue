<template>
    <div class="lock-user-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Voulez-vous débloquer l'utilisateur ?
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
                        @click="lock"
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
    props : ['dialog','user_id'],
    data : ()=>({
        loading : false,
    }),
    methods : {
        lock()
        {
            this.loading = true
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/user/ban/${this.user_id}/0`).then(e => {
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
