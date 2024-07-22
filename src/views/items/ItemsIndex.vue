<script setup>
import { store } from '../../store.js'
</script>

<template>
	<NcAppContent>
		<template #list>
			<ItemsList />
		</template>
		<template #default>
			<NcEmptyContent v-if="!store.itemItem || !store.itemItem.id || store.selected != 'items' "
				class="detailContainer"
				name="Geen item"
				description="Nog geen item geselecteerd">
				<template #icon>
					<Sword />
				</template>
				<template #action>
					<NcButton type="primary" @click="store.setModal('addItem')">
						Item aanmaken
					</NcButton>
				</template>
			</NcEmptyContent>
			<ItemDetails v-if="store.itemItem && store.itemItem.id && store.selected === 'items'" />
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
