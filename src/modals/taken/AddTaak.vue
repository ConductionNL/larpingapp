<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'addTaak'" ref="modalRef" @close="store.setModal(false)">
		<div class="modalContent">
			<h2>Taak toevoegen</h2>
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
				:disabled="!store.taakItem.onderwerp || loading"
				type="primary"
				@click="addTaak()">
				<template #icon>
					<NcLoadingIcon v-if="loading" :size="20" />
					<Plus v-if="!loading" :size="20" />
				</template>
				Toevoegen
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
import Plus from 'vue-material-design-icons/Plus.vue'

export default {
	name: 'AddTaak',
	components: {
		NcModal,
		NcTextField,
		NcTextArea,
		NcButton,
		NcSelect,
		NcLoadingIcon,
		NcNoteCard,
		// Icons
		Plus,
	},
	data() {
		return {
			succes: false,
			loading: false,
			error: false,
			// Opties
			statusOptions: [{
				key: 'key',
				value: 'value',
			}],
		}
	},
	mounted() {
		// Lets create an empty zaak item
		store.setTaakItem([])
	},
	methods: {
		addTaak() {
			this.loading = true
			fetch(
				'/index.php/apps/zaakafhandelapp/api/taken',
				{
					method: 'POST',
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
