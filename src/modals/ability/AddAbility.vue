<script setup>
import { abilityStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcModal v-if="navigationStore.modal === 'addAbility'" ref="modalRef" @close="navigationStore.setModal(false)">
		<div class="modalContent">
			<h2>Vaardigheid toevoegen</h2>
			<NcNoteCard v-if="succes" type="success">
				<p>Bijlage succesvol toegevoegd</p>
			</NcNoteCard>
			<NcNoteCard v-if="error" type="error">
				<p>{{ error }}</p>
			</NcNoteCard>

			<div v-if="!succes" class="form-group">
				<NcTextField
					:disabled="loading"
					:value.sync="ability.name"
					label="Naam"
					maxlength="255" />
			</div>

			<NcButton
				v-if="!succes"
				:disabled="!abilityStore.abilityItem.name || loading"
				type="primary"
				@click="addAbility()">
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
	name: 'AddAbility',
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
			ability: {
				name: '',
			},
		}
	},
	methods: {
		addAbility() {
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
