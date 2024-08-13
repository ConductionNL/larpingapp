<script setup>
	import { skillStore, navigationStore, searchStore } from '../../store/store.js'
</script>

<template>
	<NcAppContentList>
		<ul>
			<div class="listHeader">
				<NcTextField
					:value.sync="searchStore.search"
					:show-trailing-button="searchStore.search !== ''"
					label="Search"
					class="searchField"
					trailing-button-icon="close"
					@trailing-button-click="searchStore.setSearch('')">
					<Magnify :size="20" />
				</NcTextField>
				<NcActions>
					<NcActionButton @click="fetchData">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="store.setModal('addDocument')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Bericht toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="skillStore.skillList && skillStore.skillList.length > 0">
				<NcListItem v-for="(zaken, i) in store.skillsList.results"
					:key="`${zaken}${i}`"
					:name="zaken?.name"
					:active="store.zakenItem === zaken?.id"
					:details="'1h'"
					:counter-number="44"
					@click="store.setMetadataItem(zaken.id)">
					<template #icon>
						<BriefcaseAccountOutline :class="store.zakenItem === zaken.id && 'selectedZaakIcon'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ zaken?.summary }}
					</template>
					<template #actions>
						<NcActionButton>
							Button one
						</NcActionButton>
						<NcActionButton>
							Button two
						</NcActionButton>
						<NcActionButton>
							Button three
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="!skillStore.skillList  || skillStore.skillList.length === 0"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="Zaken aan het laden" />
	</NcAppContentList>
</template>
<script>
import { NcListItem, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'
// eslint-disable-next-line n/no-missing-import
import Magnify from 'vue-material-design-icons/Magnify'
// eslint-disable-next-line n/no-missing-import
import BriefcaseAccountOutline from 'vue-material-design-icons/BriefcaseAccountOutline'

export default {
	name: 'SkillsList',
	components: {
		NcListItem,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		BriefcaseAccountOutline,
		Magnify,
		NcLoadingIcon,
	},
	data() {
		return {
			search: '',
			loading: true,
			zakenList: [],
		}
	},
	mounted() {
		skillStore.refreshSkillList()
	},
	methods: {
		fetchData(newPage) {
			this.loading = true
			fetch(
				'/index.php/apps/zaakafhandelapp/api/zaken',
				{
					method: 'GET',
				},
			)
				.then((response) => {
					response.json().then((data) => {
						this.zakenList = data
					})
					this.loading = false
				})
				.catch((err) => {
					console.error(err)
					this.loading = false
				})
		},
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
