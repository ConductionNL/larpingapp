<script setup>
import { characterStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcModal v-if="navigationStore.modal === 'editCharacter'" ref="modalRef" @close="navigationStore.setModal(false)">
		<div class="modalContent">
			<h2>Karakter aanpassen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Karakter succesvol aangepast</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<form v-if="!succes" @submit.prevent="handleSubmit">
				<div class="form-group">
					<label for="name">Name:</label>
					<input v-model="characterStore.characterItem.name" id="name" required>
				</div>
				<div class="form-group">
					<label for="OCName">OC Name:</label>
					<input v-model="characterStore.characterItem.OCName" id="OCName" required>
				</div>
				<div class="form-group">
					<label for="description">Description:</label>
					<textarea v-model="characterStore.characterItem.description" id="description"></textarea>
				</div>
				<div class="form-group">
					<label for="type">Type:</label>
					<select v-model="characterStore.characterItem.type" id="type">
					<option value="player">Player</option>
					<option value="npc">NPC</option>
					<option value="other">Other</option>
					</select>
				</div>
				<div class="form-group">
					<label for="approved">Approved:</label>
					<select v-model="characterStore.characterItem.approved" id="approved">
					<option value="no">No</option>
					<option value="approved">Approved</option>
					</select>
				</div>
			</form>


			<NcButton
				v-if="!succes"
				:disabled="loading"
				type="primary"
				@click="editCharacter()">
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
	name: 'EditCharacter',
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
		async editCharacter() {
			this.loading = true
			try {
				await characterStore.saveCharacter()
				// Close modal or show success message
				this.succes = true
				this.loading = false
				setTimeout(() => {
					this.succes = false
					navigationStore.setModal(false)
				}, 2000)
			} catch (error) {
				this.loading = false
				this.error = error.message || 'An error occurred while saving the character'
			}
		}
	},
}
</script>
