<script setup>
import { templateStore, navigationStore, searchStore } from '../../store/store.js'
</script>

<template>
	<NcAppContentList>
		<ul>
			<div class="listHeader">
				<NcTextField
					:value="templateStore.searchTerm"
					:show-trailing-button="templateStore.searchTerm !== ''"
					label="Search"
					class="searchField"
					trailing-button-icon="close"
					@input="templateStore.setSearchTerm($event.target.value)"
					@trailing-button-click="templateStore.clearSearch()">
					<Magnify :size="20" />
				</NcTextField>
				<NcActions>
					<NcActionButton @click="templateStore.refreshTemplateList()">
						<template #icon>
							<Refresh :size="20" />
						</template>
						Ververs
					</NcActionButton>
					<NcActionButton @click="templateStore.setTemplateItem(null); navigationStore.setModal('editTemplate')">
						<template #icon>
							<Plus :size="20" />
						</template>
						Template toevoegen
					</NcActionButton>
				</NcActions>
			</div>
			<div v-if="templateStore.templateList && templateStore.templateList.length > 0 && !templateStore.isLoadingTemplateList">
				<NcListItem v-for="(template, i) in templateStore.templateList"
					:key="`${template}${i}`"
					:name="template?.name"
					:active="templateStore.templateItem?.id === template?.id"
					:details="'1h'"
					:counter-number="44"
					:force-display-actions="true"
					@click="templateStore.setTemplateItem(template)">
					<template #icon>
						<ChatOutline :class="templateStore.templateItem?.id === template.id && 'selected'"
							disable-menu
							:size="44" />
					</template>
					<template #subname>
						{{ template?.description }}
					</template>
					<template #actions>
						<NcActionButton @click="templateStore.setTemplateItem(template); navigationStore.setModal('editTemplate')">
							<template #icon>
								<Plus />
							</template>
							Bewerken
						</NcActionButton>
						<NcActionButton @click="templateStore.setTemplateItem(template), navigationStore.setDialog('deleteTemplate')">
							<template #icon>
								<TrashCanOutline />
							</template>
							Verwijderen
						</NcActionButton>
					</template>
				</NcListItem>
			</div>
		</ul>

		<NcLoadingIcon v-if="templateStore.isLoadingTemplateList"
			class="loadingIcon"
			:size="64"
			appearance="dark"
			name="Templates aan het laden" />

		<div v-if="templateStore.templateList.length === 0 && !templateStore.isLoadingTemplateList">
			Er zijn nog geen templates gedefinieerd.
		</div>
	</NcAppContentList>
</template>
<script>
// Components
import { NcListItem, NcActions, NcActionButton, NcAppContentList, NcTextField, NcLoadingIcon } from '@nextcloud/vue'

// Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import ChatOutline from 'vue-material-design-icons/ChatOutline.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import Pencil from 'vue-material-design-icons/Pencil.vue'
import TrashCanOutline from 'vue-material-design-icons/TrashCanOutline.vue'
import Refresh from 'vue-material-design-icons/Refresh.vue'

export default {
	name: 'TemplatesList',
	components: {
		// Components
		NcListItem,
		NcActions,
		NcActionButton,
		NcAppContentList,
		NcTextField,
		NcLoadingIcon,
		// Icons
		ChatOutline,
		Magnify,
		Plus,
		Pencil,
		TrashCanOutline,
		Refresh,
	},
	mounted() {
		templateStore.refreshTemplateList()
	},
	methods: {
		/**
		 * Handle template selection
		 * @param {object} template - The selected template object
		 * @returns {Promise<void>}
		 */
		async handleTemplateSelect(template) {
			// Set the selected template in the store
			templateStore.setTemplateItem(template)
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

.selected>svg {
    fill: white;
}

.loadingIcon {
    margin-block-start: var(--OC-margin-20);
}
</style>
