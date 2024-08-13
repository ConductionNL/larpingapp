/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Ability } from '../../entities/index.js'

export const useAbilityStore = defineStore(
	'ability', {
		state: () => ({
			abilityItem: false,
			abilityList: [],
		}),
		actions: {
			setAbilityItem(abilityItem) {
				this.abilityItem = abilityItem && new Ability(abilityItem)
				console.log('Active ability item set to ' + abilityItem && abilityItem?.id)
			},
			setAbilityList(abilityList) {
				this.abilityList = abilityList.map(
					(abilityItem) => new Ability(abilityItem),
				)
				console.log('Ability list set to ' + abilityList.length + ' item')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshAbilityList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/abilities'
				if (search !== null && search !== '') {
					endpoint = endpoint + '?_search=' + search
				}
				return fetch(endpoint, {
					method: 'GET',
				})
					.then((response) => {
						response.json().then((data) => {
							this.setAbilityList(data.results)
						})
					})
					.catch((err) => {
						console.error(err)
					})
			},
		},
	},
)
