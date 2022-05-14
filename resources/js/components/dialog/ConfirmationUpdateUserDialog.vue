<template>
    <div class="lock-user-dialog">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="300"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Voulez-vous modifier ?
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
                        @click="update"
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
    props : ['dialog'],
    data : ()=>({
        loading : false,
    }),
    methods : {
        update()
        {
            this.$emit('update')
        },
        close()
        {
            this.$emit('close')
        }
    }
}
</script>
