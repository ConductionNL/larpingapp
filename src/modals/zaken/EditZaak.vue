<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'editZaak'" ref="modalRef" @close="store.setModal(false)">
		<div class="modalContent">
			<h2>Zaak aanpassen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Bijlage succesvol toegevoegd</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>
			<div v-if="!succes" class="form-group">
				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.identificatie"
					label="Identificatie"
					maxlength="255"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.omschrijving"
					label="Omschrijving"
					maxlength="255" />

				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.bronorganisatie"
					label="Bronorganisatie"
					maxlength="9"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.verantwoordelijkeOrganisatie"
					label="VerantwoordelijkeOrganisatie"
					maxlength="9"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.startdatum"
					label="Startdatum"
					maxlength="9"
					required />

				<NcSelect
					v-bind="store.zaakItem.zaaktype"
					v-model="zaaktype.value"
					:disabled="loading"
					input-label="Zaaktype"
					required />

				<NcSelect
					v-bind="store.zaakItem.archiefstatus"
					v-model="archiefstatus.value"
					:disabled="loading"
					input-label="Archiefstatus"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="zaak.registratiedatum"
					label="Registratiedatum"
					maxlength="255" />

				<NcTextArea
					:disabled="loading"
					:value.sync="store.zaakItem.toelichting"
					label="Toelichting" />
			</div>
			<NcButton
				v-if="!succes"
				:disabled="!store.zaakItem.title || loading"
				type="primary"
				@click="addAttachment()">
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
import { NcButton, NcModal, NcTextField, NcSelect, NcTextArea, NcLoadingIcon } from '@nextcloud/vue'
import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'

export default {
	name: 'EditZaak',
	components: {
		NcModal,
		NcTextField,
		NcButton,
		NcSelect,
		NcTextArea,
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
		if (store.modal === 'editZaak' && this.hasUpdated) {
			if (this.zaak === store.zaakItem) return
			this.hasUpdated = false
		}
		if (store.modal === 'editZaak' && !this.hasUpdated) {
			this.zaak = store.zaakItem
			this.setArchiefStatusOptions()
			this.fetchZaakType()
			this.hasUpdated = true
		}
	},
	methods: {
		editZaak() {
			this.zaakLoading = true
			fetch(
				`index.php/apps/zaakafhandelapp/api/zrc/zaken/${this.zaak.uuid}`,
				{
					method: 'PUT',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						identificatie: this.identificatie,
						omschrijving: this.omschrijving,
						bronorganisatie: this.bronorganisatie,
						verantwoordelijkeOrganisatie: this.verantwoordelijkeOrganisatie,
						startdatum: this.startdatum,
						zaaktype: this.zaaktype.value,
						archiefstatus: this.archiefstatus.value,
						registratiedatum: this.registratiedatum,
						toelichting: this.toelichting,
					}),
				},
			)
				.then((response) => {
					this.succesMessage = true
					this.zaakLoading = false
					setTimeout(() => (this.succesMessage = false), 2500)
				})
				.catch((err) => {
					this.zaakLoading = false
					console.error(err)
				})
		},
		setArchiefStatusOptions() {
			const archiefStatusOptions = [
				{
					id: 'nog_te_archiveren',
					label: 'Nog te archiveren',
				},
				{
					id: 'gearchiveerd',
					label: 'Gearchiveerd',
				},
				{
					id: 'gearchiveerd_procestermijn_onbekend',
					label: 'Gearchiveerd procestermijn onbekend',
				},
				{
					id: 'overgedragen',
					label: 'Overgedragen',
				},
			]

			const selectedArchiefStatusOption = archiefStatusOptions.find((options) => options.id === this.zaak.archiefstatus)

			this.archiefstatus = {
				options: archiefStatusOptions,
				value: {
					id: selectedArchiefStatusOption.id ?? '',
					label: selectedArchiefStatusOption.label ?? '',
				},
			}
		},
		fetchZaakType() {
			this.zaakTypeLoading = true
			fetch('/index.php/apps/zaakafhandelapp/api/ztc/zaaktypen', {
				method: 'GET',
			})
				.then((response) => {
					response.json().then((data) => {
						const selectedZaakType = Object.entries(data.results).find((zaaktype) => zaaktype[1].id === this.zaak.zaaktype)

						this.zaaktype = {
							options: Object.entries(data.results).map((zaaktype) => ({
								id: zaaktype[1].id,
								label: zaaktype[1].name,
							})),
							value: {
								id: selectedZaakType[1].id ?? '',
								label: selectedZaakType[1].name ?? '',
							},
						}
					})
					this.zaakTypeLoading = false
				})
				.catch((err) => {
					console.error(err)
					this.zaakTypeLoading = false
				})
		},
	},
}
</script>
