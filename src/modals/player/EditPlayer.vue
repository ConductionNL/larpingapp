<script setup>
import { playerStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcModal v-if="navigationStore.modal === 'editPlayer'" ref="modalRef" @close="navigationStore.setModal(false)">
		<div class="modalContent">
			<h2>Speler {{ playerStore.playerItem.id ? 'Aanpassen' : 'Aanmaken' }}</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Speler succesvol toegevoegd</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<form v-if="!succes" @submit.prevent="handleSubmit">
				<div class="form-group">
					<label for="name">Name:</label>
					<input v-model="playerStore.playerItem.name" id="name" required>
				</div>
				<div class="form-group">
					<label for="description">Description:</label>
					<textarea v-model="playerStore.playerItem.description" id="description"></textarea>
				</div>
			</form>

			<NcButton
				v-if="!succes"
				:disabled="loading"
				type="primary"
				@click="editPlayer()">
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
	name: 'EditPlayer',
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
		async editPlayer() {
			this.loading = true
			try {
				await playerStore.savePlayer()
				// Close modal or show success message
				this.succes = true
				this.loading = false
				setTimeout(() => {
					this.succes = false
					this.loading = false
					this.error = false
					navigationStore.setModal(false)
				}, 2000)
			} catch (error) {
				this.loading = false
				this.succes = false
				this.error = error.message || 'An error occurred while saving the character'
			}
		}
	},
}
</script>
