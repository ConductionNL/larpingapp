<script setup>
	import { itemStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<div class="detailContainer">
		<div id="app-content">
			<!-- app-content-wrapper is optional, only use if app-content-list  -->
			<div>
				<div class="head">
					<h1 class="h1">
						{{ store.berichtItem.onderwerp }}
					</h1>

					<NcActions :primary="true" menu-name="Acties">
						<template #icon>
							<DotsHorizontal :size="20" />
						</template>
						<NcActionButton @click="store.setModal('editBericht')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="store.setDialog('deleteBericht')">
							<template #icon>
								<TrashCanOutline :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</NcActions>
				</div>
				<div class="detailGrid">
					<div>
						<b>Berichttekst:</b>
						<p>{{ store.berichtItem.berichttekst }}</p>
					</div>
					<div>
						<b>Inhoud:</b>
						<p>{{ store.berichtItem.inhoud }}</p>
					</div>
					<div>
						<b>Soort gebruiker:</b>
						<span>{{ store.berichtItem.soortGebruiker }}</span>
					</div>
					<div>
						<b>Publicatiedatum:</b>
						<span>{{ store.berichtItem.publicatieDatum }}</span>
					</div>
					<div>
						<b>Aanmaak datum:</b>
						<span>{{ store.berichtItem.aanmaakDatum }}</span>
					</div>
					<div>
						<b>Bericht type:</b>
						<span>{{ store.berichtItem.berichtType }}</span>
					</div>
					<div>
						<b>Referentie:</b>
						<span>{{ store.berichtItem.referentie }}</span>
					</div>
					<div>
						<b>Bericht ID:</b>
						<span>{{ store.berichtItem.berichtID }}</span>
					</div>
					<div>
						<b>Batch ID:</b>
						<span>{{ store.berichtItem.batchID }}</span>
					</div>
					<div>
						<b>Gebruiker ID:</b>
						<span>{{ store.berichtItem.gebruikerID }}</span>
					</div>
					<div>
						<b>Volgorde:</b>
						<span>{{ store.berichtItem.volgorde }}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
// Components
import { NcActions, NcActionButton } from '@nextcloud/vue'

// Icons
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'ItemDetails',
	components: {
		// Components
		NcActions,
		NcActionButton,
		// Icons
		Pencil,
		DotsHorizontal,
		TrashCanOutline,
	},
}
</script>

<style>
h4 {
  font-weight: bold
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

.grid {
  display: grid;
  grid-gap: 24px;
  grid-template-columns: 1fr 1fr;
  margin-block-start: var(--zaa-margin-50);
  margin-block-end: var(--zaa-margin-50);
}

.gridContent {
  display: flex;
  gap: 25px;
}

.tabPanel {
  padding: 20px 10px;
  min-height: 100%;
  max-height: 100%;
  height: 100%;
  overflow: auto;
}
</style>
