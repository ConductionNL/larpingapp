<script setup>
import { abilityStore, navigationStore, searchStore } from '../../store/store.js'
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
					<NcActionButton @click="abilityStore.refreshAbilityList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="navigationStore.setModal('addAbility')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Vaardigheid toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="abilityStore.abilityList  && abilityStore.abilityList.length > 0">
				<NcListItem v-for="(ability, i) in abilityStore.abilityList"
					:key="`${ability}${i}`"
					:name="ability?.name"
					:active="abilityStore.abilityItem.id === ability?.id"
					:details="'1h'"
					:counter-number="44"
					@click="abilityStore.setAbilityItem(ability)">
					<template #icon>
						<ShieldSwordOutline :class="abilityStore.abilityItem?.id === ability.id && 'selected'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ ability?.description }}
					</template>
					<template #actions>
						<NcActionButton @click="abilityStore.setAbilityStoreItem(ability), navigationStore.setModal('editAbility')">
							Bewerken
						</NcActionButton>
						<NcActionButton>
							Verwijderen
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="!abilityStore.abilityList  || abilityStore.abilityList.length === 0"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="Vaardigheden aan het laden" />
	</NcAppContentList>
</template>
<script>
// Components
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import ShieldSwordOutline from 'vue-material-design-icons/ShieldSwordOutline.vue'

export default {
	name: 'AbilitiesList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		ShieldSwordOutline,
		Magnify,
	},
	mounted() {
		abilityStore.refreshAbilityList()
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

.selected>svg {
    fill: white;
}

.loadingIcon {
    margin-block-start: var(--zaa-margin-20);
}
</style>
