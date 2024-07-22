<script setup>
import { store } from '../../store.js'
</script>

<template>
	<div class="detailContainer">
		<div v-if="!loading" id="app-content">
			<!-- app-content-wrapper is optional, only use if app-content-list  -->
			<div>
				<div class="head">
					<h1 class="h1">
						{{ zaak.identificatie }}
					</h1>
					<NcActions :primary="true" menu-name="Acties">
						<template #icon>
							<DotsHorizontal :size="20" />
						</template>
						<NcActionButton @click="store.setModal('editZaak')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="store.setModal('addDocument')">
							<template #icon>
								<FileDocumentPlusOutline :size="20" />
							</template>
							Document toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addRoll')">
							<template #icon>
								<AccountPlus :size="20" />
							</template>
							Rol toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addTaak')">
							<template #icon>
								<CalendarPlus :size="20" />
							</template>
							Taak toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addBericht')">
							<template #icon>
								<MessagePlus :size="20" />
							</template>
							Bericht toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addBesluit')">
							<template #icon>
								<MessagePlus :size="20" />
							</template>
							Besluit toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('updateZaakStatus')">
							<template #icon>
								<VectorPolylineEdit :size="20" />
							</template>
							Status wijzigen
						</NcActionButton>
					</NcActions>
				</div>
				<div class="detailGrid">
					<div>
						<b>Omschrijving:</b>
						<span>{{ zaak.omschrijving }}</span>
					</div>
					<div>
						<b>Zaaktype:</b>
						<span>{{ zaak.zaaktype }}</span>
					</div>
					<div>
						<b>Archiefstatus:</b>
						<span>{{ zaak.archiefstatus }}</span>
					</div>
					<div>
						<b>Registratiedatum:</b>
						<span>{{ zaak.registratiedatum }}</span>
					</div>
					<div>
						<b>Bronorganisatie:</b>
						<span>{{ zaak.bronorganisatie }}</span>
					</div>
					<div>
						<b>VerantwoordelijkeOrganisatie:</b>
						<span>{{ zaak.verantwoordelijkeOrganisatie }}</span>
					</div>
					<div>
						<b>Startdatum:</b>
						<span>{{ zaak.startdatum }}</span>
					</div>
					<div>
						<b>Toelichting:</b><span>{{ zaak.toelichting }}
						</span>
					</div>
				</div>
				<div class="tabContainer">
					<BTabs content-class="mt-3" justified>
						<BTab title="Eigenschappen" active>
							sadas
						</BTab>
						<BTab title="Vaardigheden">
							asd
						</BTab>
						<BTab title="Items">
							asd
						</BTab>
						<BTab title="Conditions">
							ad
						</BTab>
						<BTab title="Events">
							asd
						</BTab>
					</BTabs>
				</div>
			</div>
		</div>
		<NcLoadingIcon v-if="loading"
			:size="100"
			appearance="dark"
			name="Zaak details aan het laden" />
	</div>
</template>

<script>
// Components
import { BTabs, BTab } from 'bootstrap-vue'
import { NcLoadingIcon, NcActions, NcActionButton } from '@nextcloud/vue'

// Icons
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import AccountPlus from 'vue-material-design-icons/AccountPlus.vue'
import CalendarPlus from 'vue-material-design-icons/CalendarPlus.vue'
import MessagePlus from 'vue-material-design-icons/MessagePlus.vue'
import FileDocumentPlusOutline from 'vue-material-design-icons/FileDocumentPlusOutline.vue'
import VectorPolylineEdit from 'vue-material-design-icons/VectorPolylineEdit.vue'

export default {
	name: 'CharacterDetails',
	components: {
		// Components
		NcLoadingIcon,
		NcActions,
		NcActionButton,
		BTabs,
		BTab,
		// Icons
		DotsHorizontal,
		Pencil,
		AccountPlus,
		CalendarPlus,
		FileDocumentPlusOutline,
		VectorPolylineEdit,

	},
	props: {
		zaakId: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			zaak: [],
			loading: true,
		}
	},
	watch: {
		zaakId: {
			handler(zaakId) {
				this.fetchData(zaakId)
			},
			deep: true,
		},
	},
	mounted() {
		this.fetchData(store.zaakId)
	},
	methods: {
		addTaakToZaak() {
			store.setModal('addTaak')
			store.setTaakZaakId(this.zaak.uuid)
		},
		fetchData(zaakId) {
			this.loading = true
			fetch(
				'/index.php/apps/zaakafhandelapp/api/zrc/zaken/' + zaakId,
				{
					method: 'GET',
				},
			)
				.then((response) => {
					response.json().then((data) => {
						this.zaak = data
						this.loading = false
					})
				})
				.catch((err) => {
					console.error(err)
					this.loading = false
				})
		},
	},
}
</script>

<style>

h4 {
  font-weight: bold;
}

.head{
	display: flex;
	justify-content: space-between;
}

.button{
	max-height: 10px;
}

.h1 {
  display: block !important;
  font-size: 2em !important;
  margin-block-start: 0.67em !important;
  margin-block-end: 0.67em !important;
  margin-inline-start: 0px !important;
  margin-inline-end: 0px !important;
  font-weight: bold !important;
  unicode-bidi: isolate !important;
}

.dataContent {
  display: flex;
  flex-direction: column;
}

</style>
