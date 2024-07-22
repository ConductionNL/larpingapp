/* eslint-disable no-console */
// The store script handles app whide variables (or state), for the use of these variables and there governing concepts read the design.md
import { reactive } from 'vue'

export const store = reactive({
	// The curently active menu item, defaults to '' wich triggers the dashboard
	selected: 'dashboard',
	search: '',
	// The currently active modal, managed trought the state to ensure that only one modal can be active at the same time
	modal: false,
	modalData: [], // optional data to pass to the modal
	// The curently active item (or object) , managed trought the state to ensure that only one modal can be active at the same time
	zaakItem: false,
	zaakTypeItem: false,
	klantItem: false,
	klantenList: false,
	taakItem: false,
	takenList: false,
	taakZaakId: false,
	berichtItem: false,
	berichtenList: false,
	rolItem: false,
	// Lets add some setters
	setSelected(selected) { // The general state for menu tiemts
		this.selected = selected
		console.log('Active menu item set to ' + selected)
	},
	setModal(modal) { // The general state for modals
		this.modal = modal
		console.log('Active modal item set to ' + modal)
	},
	setDialog(modal) { // The general state for dialogs
		this.modal = modal
		console.log('Active modal item set to ' + modal)
	},
	// The Bussens logic for zaken
	setZaakItem(zaakItem) {
		const zaakDefault = {
			identificatie: '',
			omschrijving: '',
			bronorganisatie: '',
			verantwoordelijkeOrganisatie: '',
			startdatum: '',
			archiefstatus: '',
			registratiedatum: '',
			toelichting: '',
		}
		this.zaakItem = { ...zaakDefault, ...zaakItem }
		console.log('Active zaak item set to ' + zaakItem)
	},
	// The Bussens logic for zaaktypen
	setZaakTypeItem(zaakTypeItem) {
		const zaakTypeItemDefault = {
			identificatie: '',
			omschrijving: '',
			omschrijvingGeneriek: '',
			vertrouwelijkheidaanduiding: '',
			doel: '',
			aanleiding: '',
			toelichting: '',
			indicatieInternOfExtern: '',
			handelingInitiator: '',
			onderwerp: '',
			handelingBehandelaar: '',
			doorlooptijd: '',
			servicenorm: '',
			opschortingEnAanhoudingMogelijk: '',
			verlengingMogelijk: '',
			verlengingstermijn: '',
			trefwoorden: [],
			publicatieIndicatie: '',
			publicatietekst: '',
			verantwoordingsrelatie: '',
			productenOfDiensten: '',
			selectielijstProcestype: '',
			referentieproces: '',
			verantwoordelijke: '',
			broncatalogus: '',
			catalogus: '',
			besluittypen: '',
			deelzaaktypen: '',
			gerelateerdeZaaktypen: '',
			beginGeldigheid: '',
			eindeGeldigheid: '',
			beginObject: '',
			eindeObject: '',
			versiedatum: '',
		}
		this.zaakTypeItem = { ...zaakTypeItemDefault, ...zaakTypeItem }
		console.log('Active zaak type item set to ' + zaakTypeItem)
	},
	// The Bussens logic for klanten
	setKlantItem(klantItem) {
		const klantItemDefault = {
			voornaam: '',
			tussenvoegsel: '',
			achternaam: '',
			telefoonnummer: '',
			emailadres: '',
			functie: '',
			aanmaakkanaal: '',
			bronorganisatie: '',
			bedrijfsnaam: '',
			websiteUrl: '',
			url: '',
			geverifieerd: '',
			subject: '',
			subjectIdentificatie: '',
			subjectType: '',
		}
		this.klantItem = { ...klantItemDefault, ...klantItem }
		console.log('Active klant item set to ' + klantItem)
	},
	getKlantenList() {
		this.klantenList = false
		fetch(
			'/index.php/apps/zaakafhandelapp/api/klanten',
			{
				method: 'GET',
			},
		)
			.then((response) => {
				response.json().then((data) => {
					this.klantenList = data
				})
			})
			.catch((err) => {
				console.error(err)
			})
	},
	// The Bussens logic for taken
	setTaakItem(taakItem) {
		const taakItemDefault = {
			title: '',
			zaak: 'https://www.example.com/1',
			type: 'https://www.example.com',
			url: 'https://www.example.com',
			status: '',
			onderwerp: '',
			toelichting: '',
			actie: [],
			data: [],
		}
		this.taakItem = { ...taakItemDefault, ...taakItem }
		console.log('Active taak item set to ' + taakItem)
	},
	getTakenList() {
		this.takenList = false
		fetch(
			'/index.php/apps/zaakafhandelapp/api/taken',
			{
				method: 'GET',
			},
		)
			.then((response) => {
				response.json().then((data) => {
					this.takenList = data
				})
			})
			.catch((err) => {
				console.error(err)
			})
	},
	// The Bussens logic for berichten
	setBerichtItem(berichtItem) {
		const berichtItemDefault = {
			title: '',
			onderwerp: '',
			berichttekst: '',
			inhoud: '',
			bijlageType: '',
			soortGebruiker: '',
			publicatieDatum: '',
			aanmaakDatum: '',
			berichtType: '',
			referentie: '',
			berichtID: '',
			batchID: '',
			gebruikerID: '',
			volgorde: '',
		}
		this.berichtItem = { ...berichtItemDefault, ...berichtItem }
		console.log('Active bericht item set to ' + berichtItem)
	},
	getBerichtenList() {
		this.berichtenList = false
		fetch(
			'/index.php/apps/zaakafhandelapp/api/berichten',
			{
				method: 'GET',
			},
		)
			.then((response) => {
				response.json().then((data) => {
					this.berichtenList = data
				})
			})
			.catch((err) => {
				console.error(err)
			})
	},
	// The Bussens logic for rollen
	setRolItem(rolItem) {
		const rolItemDefault = {
			versiedatum: '',
		}
		this.rolItem = { ...rolItemDefault, ...rolItem }
		console.log('Active rol item set to ' + rolItem)
	},
})
