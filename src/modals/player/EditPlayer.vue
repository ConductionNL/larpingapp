<script setup>
import { playerStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcModal v-if="navigationStore.modal === 'editPlayer'" ref="modalRef" @close="navigationStore.setModal(false)">
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
	updated() {
		if (store.modal === 'editTaak' && this.hasUpdated) {
			if (this.taak === store.taakItem) return
			this.hasUpdated = false
		}
		if (store.modal === 'editTaak' && !this.hasUpdated) {
			this.taak = store.taakItem
			this.fetchZaken()
			this.setStatusOptions()
			this.hasUpdated = true
		}
	},
	methods: {
		editPlayer() {
			this.loading = true
			fetch(
				`/index.php/apps/zaakafhandelapp/api/taken/${store.taakItem.id}`,
				{
					method: 'PUT',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(store.taakItem),
				},
			)
				.then((response) => {
					this.succes = true
					this.loading = false
					store.getTakenList()
					response.json().then((data) => {
						store.setTaakItem(data)
					})
					// Get the modal to self close
					const self = this
					setTimeout(function() {
						self.succes = false
						navigationStore.setModal(false)
					}, 2000)
				})
				.catch((err) => {
					this.loading = false
					this.error = err
					console.error(err)
				})
		},
	},
}
</script>
