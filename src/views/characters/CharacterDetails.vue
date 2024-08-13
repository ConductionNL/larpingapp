<script setup>
	import { characterStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<div class="detailContainer">
		<div v-if="!loading" id="app-content">
			<!-- app-content-wrapper is optional, only use if app-content-list  -->
			<div>
				<div class="head">
					<h1 class="h1">
						{{ store.characterItem.name }}
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
							Vaardigheid toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addRoll')">
							<template #icon>
								<AccountPlus :size="20" />
							</template>
							Item toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addTaak')">
							<template #icon>
								<CalendarPlus :size="20" />
							</template>
							Conditie toevoegen
						</NcActionButton>
						<NcActionButton @click="store.setModal('addBericht')">
							<template #icon>
								<MessagePlus :size="20" />
							</template>
							Als pdf downloaden
						</NcActionButton>
						<NcActionButton @click="store.setModal('editZaak')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</NcActions>
				</div>
				<div class="detailGrid">
					<div>
						<b>Omschrijving:</b>
						<span>{{ store.characterItem.description }}</span>
					</div>
				</div>
				<div class="tabContainer">
					<BTabs content-class="mt-3" justified>
						<BTab title="Eigenschappen" active>
							Eigenschappen
						</BTab>
						<BTab title="Vaardigheden">
							Vaardigheden
						</BTab>
						<BTab title="Items">
							Items
						</BTab>
						<BTab title="Conditions">
							Conditions
						</BTab>
						<BTab title="Events">
							Events
						</BTab>
					</BTabs>
				</div>
			</div>
		</div>
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
