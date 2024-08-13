<script setup>
	import { templateStore, navigationStore } from '../../store/store.js'
</script>

<template>
	<NcAppContent>
		<template #list>
			<TemplatesList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!store.templateItem || navigationStore.selected != 'templates' "
				class="detailContainer"
				name="Geen template"
				description="Nog geen template geselecteerd">
				<template #icon>
					<FileDocumentOutline />
				</template>
				<template #action>
					<NcButton type="primary" @click="navigationStore.setModal('addTemplate')">
						Vaardigheid aanmaken
					</NcButton>
				</template>
			</NcEmptyContent>
			<TemplateDetails v-if="store.templateItem && navigationStore.selected === 'templates'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent, NcEmptyContent, NcButton } from '@nextcloud/vue'
import TemplatesList from './TemplatesList.vue'
import TemplateDetails from './TemplateDetails.vue'
import FileDocumentOutline from 'vue-material-design-icons/FileDocumentOutline.vue'

export default {
	name: 'TemplatesIndex',
	components: {
		NcAppContent,
		NcEmptyContent,
		NcButton,
		TemplatesList,
		TemplateDetails,
		FileDocumentOutline,
	},
}
</script>
