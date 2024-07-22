<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'editTaak'" ref="modalRef" @close="store.setModal(false)">
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
					:value.sync="store.taakItem.title"
					label="Titel"
					maxlength="255" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.taakItem.type"
					label="Type"
					maxlength="255" />

				<NcSelect
					v-bind="statusOptions"
					v-model="store.taakItem.status"
					:disabled="loading"
					input-label="Status"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="store.taakItem.onderwerp"
					label="Onderwerp"
					maxlength="255" />

				<NcTextArea
					:disabled="loading"
					:value.sync="store.taakItem.toelichting"
					label="Toelichting" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="loading"
				type="primary"
				@click="editTaak()">
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
	name: 'EditTaak',
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
		editTaak() {
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
						store.setModal(false)
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
