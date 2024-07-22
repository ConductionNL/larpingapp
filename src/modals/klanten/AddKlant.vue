<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'addKlant'" ref="modalRef" @close="store.setModal(false)">
		<div class="modal__content">
			<h2>Klant toevoegen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>KLant succesvol toegevoegd</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<div v-if="!succes" class="form-group">
				<NcTextField :disabled="loading"
					label="Voornaam"
					maxlength="255"
					:value.sync="store.klantItem.voornaam" />

				<NcTextField :disabled="loading"
					label="Tussenvoegsel"
					maxlength="255"
					:value.sync="store.klantItem.tussenvoegsel" />

				<NcTextField :disabled="loading"
					label="Achternaam"
					maxlength="255"
					:value.sync="store.klantItem.achternaam" />

				<NcTextField :disabled="loading"
					label="Telefoonnummer"
					maxlength="255"
					:value.sync="store.klantItem.telefoonnummer" />

				<NcTextField :disabled="loading"
					label="Email adres"
					maxlength="255"
					:value.sync="store.klantItem.emailadres" />

				<NcTextField :disabled="loading"
					label="Functie"
					maxlength="255"
					:value.sync="store.klantItem.functie" />

				<NcTextField :disabled="loading"
					label="Aanmaak kanaal"
					maxlength="255"
					:value.sync="store.klantItem.aanmaakkanaal" />

				<NcTextField :disabled="loading"
					label="Bron organisatie"
					maxlength="255"
					:value.sync="store.klantItem.bronorganisatie" />

				<NcTextField :disabled="loading"
					label="Bedrijfsnaam"
					maxlength="255"
					:value.sync="store.klantItem.bedrijfsnaam" />

				<NcTextField :disabled="loading"
					label="Website Url"
					maxlength="255"
					:value.sync="store.klantItem.websiteUrl" />

				<NcTextField :disabled="loading"
					label="Url"
					maxlength="255"
					:value.sync="store.klantItem.url" />

				<NcTextField :disabled="loading"
					label="Geverifieerd"
					maxlength="255"
					:value.sync="store.klantItem.geverifieerd" />

				<NcTextField :disabled="loading"
					label="Subject"
					maxlength="255"
					:value.sync="store.klantItem.subject" />

				<NcTextField :disabled="loading"
					label="Subject Identificatie"
					maxlength="255"
					:value.sync="store.klantItem.subjectIdentificatie" />

				<NcTextField :disabled="loading"
					label="Subject Type"
					maxlength="255"
					:value.sync="store.klantItem.subjectType" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="!store.klantItem.voornaam || loading"
				type="primary"
				@click="addKlant()">
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
	NcLoadingIcon,
	NcNoteCard,
} from '@nextcloud/vue'
import Plus from 'vue-material-design-icons/Plus.vue'

export default {
	name: 'AddKlant',
	components: {
		NcModal,
		NcTextField,
		NcButton,
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
		}
	},
	mounted() {
		// Lets create an empty zaak item
		store.setKlantItem([])
	},
	methods: {
		addKlant() {
			this.loading = true
			fetch(
				'/index.php/apps/zaakafhandelapp/api/klanten',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(store.klantItem),
				},
			)
				.then((response) => {
					this.succes = true
					this.loading = false
					store.getKlantenList()
					response.json().then((data) => {
						store.setKlantItem(data)
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

.zaakDetailsContainer {
    margin-block-start: var(--zaa-margin-20);
    margin-inline-start: var(--zaa-margin-20);
    margin-inline-end: var(--zaa-margin-20);
}

.success {
    color: green;
}
</style>
