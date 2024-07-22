<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'editBericht'" ref="modalRef" @close="store.setModal(false)">
		<div class="modal__content">
			<h2>Bericht aanpassen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Bijlage succesvol toegevoegd</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<div v-if="!succes" class="form-group">
				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.title"
					label="Title" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.onderwerp"
					label="Onderwerp" />

				<NcTextArea
					:disabled="loading"
					:value.sync="store.berichtItem.berichttekst"
					label="Berichttekst" />

				<NcTextArea
					:disabled="loading"
					:value.sync="store.berichtItem.inhoud"
					label="Inhoud (base64)" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.bijlageType"
					label="Bijlage type" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.soortGebruiker"
					label="Soort gebruiker" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.publicatieDatum"
					label="Publicatiedatum" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.aanmaakDatum"
					label="Aanmaak datum" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.berichtType"
					label="Bericht type" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.referentie"
					label="Referentie" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.berichtID"
					label="Bericht ID" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.batchID"
					label="Batch ID" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.gebruikerID"
					label="Gebruiker ID" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.berichtItem.onderwerp"
					label="Volgorde" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="loading"
				type="primary"
				@click="editBericht()">
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
import { NcButton, NcLoadingIcon, NcModal, NcTextField, NcTextArea } from '@nextcloud/vue'
import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'

export default {
	name: 'EditBericht',
	components: {
		NcModal,
		NcTextField,
		NcTextArea,
		NcButton,
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
		if (store.modal === 'editBericht' && this.hasUpdated) {
			if (this.bericht === store.berichtItem) return
			this.hasUpdated = false
		}
		if (store.modal === 'editBericht' && !this.hasUpdated) {
			this.hasUpdated = true
			this.bericht = store.berichtItem
		}
	},
	methods: {
		editBericht() {
			fetch(
				`/index.php/apps/zaakafhandelapp/api/berichten/${store.berichtItem.id}`,
				{
					method: 'PUT',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(store.berichtItem),
				},
			)
				.then((response) => {
					this.succes = true
					this.loading = false
					store.getBerichtenList()
					response.json().then((data) => {
						store.setBerichtItem(data)
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

<style>
.modal__content {
    margin: var(--zaa-margin-50);
    text-align: center;
}

.berichtDetailsContainer {
    margin-block-start: var(--zaa-margin-20);
    margin-inline-start: var(--zaa-margin-20);
    margin-inline-end: var(--zaa-margin-20);
}

.success {
    color: green;
}
</style>
