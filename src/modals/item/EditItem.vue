<script setup>
import { itemStore, navigationStore } from '../../store/store.js'
</script>

<template>
    <NcDialog v-if="navigationStore.modal === 'editItem'"
        name="Voorwerp"
        size="normal"
        :can-close="false">

        <NcNoteCard v-if="success" type="success">
            <p>Voorwerp succesvol aangepast</p>
        </NcNoteCard>
        <NcNoteCard v-if="error" type="error">
            <p>{{ error }}</p>
        </NcNoteCard>

        <div v-if="!success" class="formContainer">
            <NcTextField :disabled="loading"
                label="Name *"
                required
                :value.sync="itemStore.itemItem.name" />
            <NcTextArea :disabled="loading"
                label="Description"
                type="textarea"
                :value.sync="itemStore.itemItem.description" />
            <NcTextField :disabled="loading"
                label="Effect"
                :value.sync="itemStore.itemItem.effect" />
            <NcCheckboxRadioSwitch :disabled="loading"
                label="Unique"
                type="switch"
                :checked.sync="itemStore.itemItem.unique" />
        </div>

        <template #actions>
            <NcButton
                @click="navigationStore.setModal(false)">
                <template #icon>
                    <Cancel :size="20" />
                </template>
                {{ success ? 'Sluiten' : 'Annuleer' }}
            </NcButton>
            <NcButton
                @click="openLink('https://conduction.gitbook.io/opencatalogi-nextcloud/gebruikers/publicaties', '_blank')">
                <template #icon>
                    <Help :size="20" />
                </template>
                Help
            </NcButton>
            <NcButton
                v-if="!success"
                :disabled="loading"
                type="primary"
                @click="editItem()">
                <template #icon>
                    <NcLoadingIcon v-if="loading" :size="20" />
                    <ContentSaveOutline v-if="!loading && itemStore.itemItem.id" :size="20" />
                    <Plus v-if="!loading && !itemStore.itemItem.id" :size="20" />
                </template>
                {{ itemStore.itemItem.id ? 'Opslaan' : 'Aanmaken' }}
            </NcButton>
        </template>
    </NcDialog>
</template>

<script>
import {
    NcButton,
    NcDialog,
    NcTextField,
    NcTextArea,
    NcCheckboxRadioSwitch,
    NcLoadingIcon,
    NcNoteCard,
} from '@nextcloud/vue'

import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'
import Cancel from 'vue-material-design-icons/Cancel.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Help from 'vue-material-design-icons/Help.vue'

export default {
    name: 'EditItem',
    components: {
        NcDialog,
        NcTextField,
        NcTextArea,
        NcButton,
        NcCheckboxRadioSwitch,
        NcLoadingIcon,
        NcNoteCard,
        // Icons
        ContentSaveOutline,
        Cancel,
        Plus,
        Help,
    },
    data() {
        return {
            success: false,
            loading: false,
            error: false,
        }
    },
    methods: {
        async editItem() {
            this.loading = true
            try {
                await itemStore.saveItem()
                this.success = true
                this.loading = false
                this.error = false
                setTimeout(() => {
                    this.success = false
					this.loading = false
					this.error = false
                    navigationStore.setModal(false)
                }, 2000)
            } catch (error) {
                this.loading = false
                this.success = false
                this.error = error.message || 'An error occurred while saving the item'
            }
        },
        openLink(url, target) {
            window.open(url, target)
        }
    },
}
</script>
