<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcModal v-if="store.modal === 'editRol'" ref="modalRef" @close="store.setModal(false)">
		<div class="modal__content">
			<h2>Rol aanpassen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Rol succesvol aangepast</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<div v-if="!succes" class="form-group">
				<NcTextField
					:disabled="loading"
					:value.sync="rol.omschrijving"
					label="Omschrijving"
					required="true" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="!store.attachmentItem.title || loading"
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
import { NcButton, NcLoadingIcon, NcModal, NcTextField } from '@nextcloud/vue'
import ContentSaveOutline from 'vue-material-design-icons/ContentSaveOutline.vue'

export default {
	name: 'EditRol',
	components: {
		NcModal,
		NcTextField,
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
		if (store.modal === 'editRol' && this.hasUpdated) {
			if (this.rol === store.rolItem) return
			this.hasUpdated = false
		}
		if (store.modal === 'editRol' && !this.hasUpdated) {
			this.fetchData(store.rolId)
			this.hasUpdated = true
			this.rol = store.rolItem
		}
	},
	methods: {
		fetchData(id) {
			this.rolLoading = true
			fetch(
				`/index.php/apps/zaakafhandelapp/api/rollen/${id}`,
				{
					method: 'GET',
				},
			)
				.then((response) => {
					response.json().then((data) => {
						this.rol = data
						// this.oldZaakId = id
					})
					this.rolLoading = false
					// Get the modal to self close
					const self = this
					setTimeout(function() {
						self.succes = false
						store.setModal(false)
					}, 2000)
				})
				.catch((err) => {
					console.error(err)
					// this.oldZaakId = id
					this.rolLoading = false
				})
		},
		closeModal() {
			store.modal = false
		},
		editRol() {
			fetch(
				`/index.php/apps/zaakafhandelapp/api/rollen/${store.rolId}`,
				{
					method: 'PUT',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(this.rol),
				},
			).then((response) => {
				this.succesMessage = true
				setTimeout(() => (this.succesMessage = false), 2500)
			}).catch((err) => {
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

.rolDetailsContainer {
    margin-block-start: var(--zaa-margin-20);
    margin-inline-start: var(--zaa-margin-20);
    margin-inline-end: var(--zaa-margin-20);
}

.success {
    color: green;
}
</style>
