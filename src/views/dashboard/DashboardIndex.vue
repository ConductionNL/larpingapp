<template>
	<NcAppContent>
		<h2 class="pageHeader">
			Dashboard
		</h2>
		<b>Aantal zoekopdrachten afgelopen maand</B>
	</NcAppContent>
</template>

<script>
import { NcAppContent } from '@nextcloud/vue'

export default {
	name: 'DashboardIndex',
	components: {
		NcAppContent,
	},
}
</script>
<style>
.dashboardContainer {
    margin-inline-start: 75px;
    margin-block-start: 20px
}
</style>
