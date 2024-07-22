<script setup>
import { store } from '../store.js'
</script>

<template>
	<NcAppNavigation>
		<NcAppNavigationList>
			<NcAppNavigationNew text="Karakter toevoegen" @click="store.setModal('addCharacter')">
				<template #icon>
					<Plus :size="20" />
				</template>
			</NcAppNavigationNew>
			<NcAppNavigationItem
				:active="store.selected === 'dashboard'"
				name="Dashboard"
				@click="store.setSelected('dashboard')">
				<template #icon>
					<Finance :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'characters'"
				name="Karakters"
				@click="store.setSelected('characters')">
				<template #icon>
					<BriefcaseAccountOutline :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'players'"
				name="Spelers"
				@click="store.setSelected('players')">
				<template #icon>
					<AccountGroupOutline :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'items'"
				name="Items"
				@click="store.setSelected('items')">
				<template #icon>
					<Sword :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'events'"
				name="Events"
				@click="store.setSelected('events')">
				<template #icon>
					<CalendarMonthOutline :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'search'"
				name="Zoeken"
				@click="store.setSelected('search')">
				<template #icon>
					<Magnify :size="20" />
				</template>
			</NcAppNavigationItem>
		</NcAppNavigationList>

		<NcAppNavigationSettings>
			<NcAppNavigationItem
				:active="store.selected === 'abilities'"
				name="Abilities"
				@click="store.setSelected('abilities')">
				<template #icon>
					<ShieldSwordOutline :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'skills'"
				name="Skills"
				@click="store.setSelected('skills')">
				<template #icon>
					<SwordCross :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'effects'"
				name="Effects"
				@click="store.setSelected('effects')">
				<template #icon>
					<MagicStaff :size="20" />
				</template>
			</NcAppNavigationItem>
			<NcAppNavigationItem
				:active="store.selected === 'auditTrail'"
				name="Templates"
				@click="store.setSelected('auditTrail')">
				<template #icon>
					<FileDocumentOutline :size="20" />
				</template>
			</NcAppNavigationItem>
			<Configuration />
		</NcAppNavigationSettings>
	</NcAppNavigation>
</template>
<script>

import {
	NcAppNavigation,
	NcAppNavigationList,
	NcAppNavigationItem,
	NcAppNavigationNew,
	NcAppNavigationSettings,
} from '@nextcloud/vue'

// Configuration
import Configuration from './Configuration.vue'

// Icons
import Finance from 'vue-material-design-icons/Finance.vue'
import Sword from 'vue-material-design-icons/Sword.vue'
import AccountGroupOutline from 'vue-material-design-icons/AccountGroupOutline.vue'
import CalendarMonthOutline from 'vue-material-design-icons/CalendarMonthOutline.vue'
import BriefcaseAccountOutline from 'vue-material-design-icons/BriefcaseAccountOutline.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import ShieldSwordOutline from 'vue-material-design-icons/ShieldSwordOutline.vue'
import SwordCross from 'vue-material-design-icons/SwordCross.vue'
import MagicStaff from 'vue-material-design-icons/MagicStaff.vue'
import Magnify from 'vue-material-design-icons/Magnify.vue'
import FileDocumentOutline from 'vue-material-design-icons/FileDocumentOutline.vue'

export default {
	name: 'MainMenu',
	components: {
		NcAppNavigation,
		NcAppNavigationList,
		NcAppNavigationItem,
		NcAppNavigationNew,
		NcAppNavigationSettings,
		// Icons
		ShieldSwordOutline,
		SwordCross,
		MagicStaff,
		Finance,
		Sword,
		AccountGroupOutline,
		CalendarMonthOutline,
		BriefcaseAccountOutline,
		Plus,
		Configuration,
		Magnify,
		FileDocumentOutline,
	},
}
</script>
<style>
table {
	table-layout: fixed;
}

td.row-name {
	padding-inline-start: 16px;
}

td.row-size {
	text-align: right;
	padding-inline-end: 16px;
}

.table-header {
	font-weight: normal;
	color: var(--color-text-maxcontrast);
}

.sort-icon {
	color: var(--color-text-maxcontrast);
	position: relative;
	inset-inline: -10px;
}

.row-size .sort-icon {
	inset-inline: 10px;
}
</style>
