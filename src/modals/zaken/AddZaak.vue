<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'addZaak'" ref="modalRef" @close="store.setModal(false)">
		<div class="modalContent">
			<h2>Zaak starten</h2>
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
					label="Bron organisatie"
					maxlength="9"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.verantwoordelijkeOrganisatie"
					label="Verantwoordelijke Organisatie"
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
					:disabled="loading"
					:value.sync="store.zaakItem.Archiefstatus"
					input-label="Archiefstatus"
					required />

				<NcTextField
					:disabled="loading"
					:value.sync="store.zaakItem.registratiedatum"
					label="Registratiedatum"
					maxlength="255" />

				<NcTextArea
					:disabled="loading"
					:value.sync="store.zaakItem.toelichting"
					label="Toelichting" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="!store.zaakItem.zaaktype || loading"
				type="primary"
				@click="addZaak()">
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
import { NcButton, NcModal, NcTextField, NcSelect, NcTextArea, NcLoadingIcon } from '@nextcloud/vue'
import Plus from 'vue-material-design-icons/Plus.vue'

export default {
	name: 'AddZaak',
	components: {
		NcModal,
		NcTextField,
		NcButton,
		NcSelect,
		NcTextArea,
		NcLoadingIcon,
		// Icons
		Plus,
	},
	data() {
		return {
			succes: false,
			loading: false,
			error: false,
			// Select options
			zaakTypeLoading: false,
			zaaktype: false,
		}
	},
	mounted() {
		// Lets create an empty zaak item
		store.setZaakItem([])
		// Get a zaaktype lisy  @todo should be part of a service
		this.fetchZaakType()
	},
	methods: {
		addZaak() {
			this.loading = true
			fetch(
				'index.php/apps/zaakafhandelapp/api/zrc/zaken',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(store.zaakItem),
				},
			)
				.then((response) => {
					this.succes = true
					this.loading = false
					// Get the modal to self close
					const self = this
					setTimeout(function() {
						self.succes = false
						store.setModal(false)
					}, 2000)
				})
				.catch((err) => {
					this.loading = false
					console.error(err)
				})
		},
		fetchZaakType() {
			this.zaakTypeLoading = true
			fetch('/index.php/apps/zaakafhandelapp/api/ztc/zaaktypen', {
				method: 'GET',
			})
				.then((response) => {
					response.json().then((data) => {

						this.zaaktype = {
							options: Object.entries(data.results).map((zaaktype) => ({
								id: zaaktype[1].id,
								label: zaaktype[1].name,
							})),

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

<style>
.modalContent {
    margin: var(--zaa-margin-50);
    text-align: center;
}

</style>
