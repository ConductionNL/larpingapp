/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Effect } from '../../entities/index.js'

export const useEffectStore = defineStore(
	'effect', {
		state: () => ({
			effectItem: false,
			effectList: [],
		}),
		actions: {
			setEffectItem(effectItem) {
				this.effectItem = effectItem && new Effect(effectItem)
				console.log('Active effect item set to ' + effectItem)
			},
			setEffectList(effectList) {
				this.effectList = effectList.map(
					(effectItem) => new Effect(effectItem),
				)
				console.log('Effects list set to ' + effectList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshEffectList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/effects'
				if (search !== null && search !== '') {
					endpoint = endpoint + '?_search=' + search
				}
				return fetch(endpoint, {
					method: 'GET',
				})
					.then(
						(response) => {
							response.json().then(
								(data) => {
									this.setEffectList(data.results)
								},
							)
						},
					)
					.catch(
						(err) => {
							console.error(err)
						},
					)
			},
		},
	},
)