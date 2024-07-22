<template>
	<!-- Placeholder -->
	<div>
		<AddZaak />
		<EditZaak />
		<AddZaakType />
		<EditZaakType />
		<AddKlant />
		<EditKlant />
		<AddBericht />
		<EditBericht />
		<AddTaak />
		<EditTaak />
		<AddRol />
		<EditRol />
	</div>
</template>

<script>
import AddZaak from './zaken/AddZaak.vue'
import EditZaak from './zaken/EditZaak.vue'
import AddZaakType from './zaakTypen/AddZaakType.vue'
import EditZaakType from './zaakTypen/EditZaakType.vue'
import AddKlant from './klanten/AddKlant.vue'
import EditKlant from './klanten/EditKlant.vue'
import AddBericht from './berichten/AddBericht.vue'
import EditBericht from './berichten/EditBericht.vue'
import AddTaak from './taken/AddTaak.vue'
import EditTaak from './taken/EditTaak.vue'
import AddRol from './rollen/AddRol.vue'
import EditRol from './rollen/EditRol.vue'

export default {
	name: 'Modals',
	components: {
		AddZaak,
		EditZaak,
		AddZaakType,
		EditZaakType,
		AddKlant,
		EditKlant,
		AddBericht,
		EditBericht,
		AddTaak,
		EditTaak,
		AddRol,
		EditRol,
	},
}
</script>
