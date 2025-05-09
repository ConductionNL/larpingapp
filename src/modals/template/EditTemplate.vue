<script setup>
import { templateStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcDialog v-if="navigationStore.modal === 'editTemplate'"
		name="Sjabloon"
		size="normal"
		:can-close="false">
		<NcNoteCard v-if="success" type="success">
			<p>Sjabloon succesvol aangepast</p>
		</NcNoteCard>
		<NcNoteCard v-if="error" type="error">
			<p>{{ error }}</p>
		</NcNoteCard>

		<div v-if="!success" class="formContainer">
			<NcTextField :disabled="loading"
				label="Name *"
				required
				:value.sync="templateItem.name" />
			<NcTextArea :disabled="loading"
				label="Description"
				type="textarea"
                resize="vertical"
				:value.sync="templateItem.description" />
			<NcTextArea :disabled="loading"
				label="Content"
				type="textarea"
				resize="vertical"
				:value.sync="templateItem.template" />
		</div>

		<template #actions>
			<NcButton @click="closeModal">
				<template #icon>
					<Cancel :size="20" />
				</template>
				{{ success ? 'Sluiten' : 'Annuleer' }}
			</NcButton>
			<NcButton @click="openLink('https://conduction.gitbook.io/opencatalogi-nextcloud/gebruikers/publicaties', '_blank')">
				<template #icon>
					<Help :size="20" />
				</template>
				Help
			</NcButton>
			<NcButton v-if="!success"
				:disabled="loading"
				type="primary"
				@click="editTemplate()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<ContentSaveOutline v-if="!loading && templateItem?.id" :size="20" />
					<Plus v-if="!loading && !templateItem?.id" :size="20" />
				</template>
				{{ templateItem?.id ? 'Opslaan' : 'Aanmaken' }}
			</NcButton>
		</template>
	</NcDialog>
</template>

<script>
import {
	NcButton, NcDialog, NcTextField, NcTextArea, NcLoadingIcon, NcNoteCard,
} from '@nextcloud/vue'
import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'
import Cancel from 'vue-material-design-icons/Cancel.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Help from 'vue-material-design-icons/Help.vue'

export default {
	name: 'EditTemplate',
	components: {
		NcDialog,
		NcTextField,
		NcTextArea,
		NcButton,
		NcLoadingIcon,
		NcNoteCard,
		ContentSaveOutline,
		Cancel,
		Plus,
		Help,
	},
	data() {
		return {
			templateItem: {
				name: '',
				description: '',
				template: '',
			},
			success: false,
			loading: false,
			error: false,
		}
	},
	updated() {
		if (navigationStore.modal === 'editTemplate' && templateStore.templateItem?.id && !this.hasUpdated) {
			this.templateItem = {
				...templateStore.templateItem,
				name: templateStore.templateItem.name || '',
				description: templateStore.templateItem.description || '',
				template: templateStore.templateItem.template || '',
			}

			this.hasUpdated = true
		}
	},
	methods: {
		closeModal() {
			navigationStore.setModal(false)
			this.success = false
			this.loading = false
			this.error = false
			this.hasUpdated = false
			this.templateItem = {
				name: '',
				description: '',
				template: '',
			}
		},
		async editTemplate() {
			this.loading = true
			try {
				await templateStore.saveTemplate({
					...this.templateItem,
				})

				this.success = true
				this.loading = false
				setTimeout(this.closeModal, 2000)
			} catch (error) {
				this.loading = false
				this.success = false
				this.error = error.message || 'An error occurred while saving the template'
			}
		},
		openLink(url, target) {
			window.open(url, target)
		},
	},
}
</script>