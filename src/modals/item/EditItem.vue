<script setup>
import { itemStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcModal v-if="navigationStore.modal === 'editItem'" ref="modalRef" @close="navigationStore.setModal(false)">
		<div class="modalContent">
			<h2>Taak aanpassen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Bijlage succesvol toegevoegd</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<div v-if="!succes" class="form-group">
				<NcTextField
					:disabled="loading"
					:value.sync="abilityStore.abilityItem.name"
					label="Naam"
					maxlength="255" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="loading"
				type="primary"
				@click="editItem()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<ContentSaveOutline v-if="!loading" :size="20" />
				</template>
				Opslaan
			</NcButton>
		</div>
	</NcModal>
</template>

<script>
import {
	NcButton,
	NcModal,
	NcTextField,
	NcTextArea,
	NcSelect,
	NcLoadingIcon,
	NcNoteCard,
} from '@nextcloud/vue'
import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'

export default {
	name: 'EditItem',
	components: {
		NcModal,
		NcTextField,
		NcTextArea,
		NcButton,
		NcSelect,
		NcLoadingIcon,
		NcNoteCard,
		// Icons
		ContentSaveOutline,
	},
	data() {
		return {
			succes: false,
			loading: false,
			error: false,
		}
	},
	methods: {
		async editItem() {
			this.loading = true
			try {
				await this.itemStore.saveItem(this.itemStore.itemItem)
				// Close modal or show success message
				this.succes = true
				this.loading = false
				setTimeout(function() {
					this.navigationStore.setModal(false)
				}, 2000)
			} catch (error) {
				this.loading = false
				this.succes = error
			}
		}
	},
}
</script>
