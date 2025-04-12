<script setup>
import { useObjectStore } from '../../store/modules/object.js'
import { navigationStore } from '../../store/store.js'
import { onMounted } from 'vue'

const objectStore = useObjectStore()

// Set the object type to 'item'
onMounted(() => {
	objectStore.setObjectType('item')
})
</script>

<template>
	<NcAppContent>
		<template #list>
			<ItemsList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!objectStore.objectItem || navigationStore.selected != 'items'"
				class="detailContainer"
				name="Geen item"
				description="Nog geen item geselecteerd">
				<template #icon>
					<Sword />
				</template>
				<template #action>
					<NcButton type="primary" @click="objectStore.setObjectItem(null); navigationStore.setModal('editItem')">
						Item aanmaken
					</NcButton>
				</template>
			</NcEmptyContent>
			<ItemDetails v-if="objectStore.objectItem && navigationStore.selected === 'items'" />
		</template>
	</NcAppContent>
</template>

<script>
import { NcAppContent, NcEmptyContent, NcButton } from '@nextcloud/vue'
import ItemsList from './ItemsList.vue'
import ItemDetails from './ItemDetails.vue'
// eslint-disable-next-line n/no-missing-import
import Sword from 'vue-material-design-icons/Sword'

export default {
	name: 'ItemsIndex',
	components: {
		NcAppContent,
		NcEmptyContent,
		NcButton,
		ItemsList,
		ItemDetails,
		Sword,
	},
}
</script>
