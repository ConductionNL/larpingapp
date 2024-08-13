<script setup>
	import { itemStore, navigationStore, searchStore } from '../../store/store.js'
</script>

<template>
	<NcAppContentList>
		<ul>
			<div class="listHeader">
				<NcTextField class="searchField"
					disabled
					:value.sync="searchStore.search"
					label="Search"
					trailing-button-icon="close"
					:show-trailing-button="search !== ''"
					@trailing-button-click="searchStore.setSearch('')">
					<Magnify :size="20" />
				</NcTextField>
				<NcActions>
					<NcActionButton @click="store.getBerichtenList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="store.setModal('addBericht')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Bericht toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="store.berichtenList">
				<NcListItem v-for="(bericht, i) in itemStore.berichtenList.results"
					:key="`${bericht}${i}`"
					:name="bericht?.onderwerp"
					:active="store.berichtId === bericht?.id"
					:details="'1h'"
					:counter-number="44"
					:force-display-actions="true"
					@click="store.setBerichtItem(bericht)">
					<template #icon>
						<ChatOutline :class="store.berichtId === bericht.id && 'selectedZaakIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ bericht?.berichttekst }}
					</template>
					<template #actions>
						<NcActionButton @click="store.setBerichtItem(bericht); store.setModal('editBericht')">
							<template #icon>
								<Pencil :size="20" />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="store.setBerichtItem(bericht); store.setDialog('deleteBericht')">
							<template #icon>
								<TrashCanOutline :size="20" />
							</template>
							Verwijderen
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="!store.berichtenList"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="berichten aan het laden" />
	</NcAppContentList>
</template>
<script>
//  Components
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import ChatOutline from 'vue-material-design-icons/ChatOutline.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'

export default {
	name: 'ItemsList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		Magnify,
		Refresh,
		Plus,
		ChatOutline,
		Pencil,
		TrashCanOutline,
	},
	data() {
		return {
			search: '',
			loading: true,
			berichtenList: [],
		}
	},
	mounted() {
		itemStore.refreshItemsList()
	},
	methods: {
		clearText() {
			this.search = ''
		},
	},
}
</script>
<style>

.listHeader {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: var(--color-main-background);
    border-bottom: 1px solid var(--color-border);
}

.searchField {
    padding-inline-start: 65px;
    padding-inline-end: 20px;
    margin-block-end: 6px;
}

.selectedZaakIcon>svg {
    fill: white;
}

.loadingIcon {
    margin-block-start: var(--zaa-margin-20);
}
</style>
