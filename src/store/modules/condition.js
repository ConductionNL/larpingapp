/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Condition } from '../../entities/index.js'

export const useConditionStore = defineStore(
	'condition', {
		state: () => ({
			conditionItem: false,
			conditionList: [],
		}),
		actions: {
			setConditionItem(conditionItem) {
				this.conditionItem = conditionItem && new Condition(conditionItem)
				console.log('Active condition item set to ' + conditionItem)
			},
			setConditionList(conditionList) {
				this.conditionList = conditionList.map(
					(conditionItem) => new Condition(conditionItem),
				)
				console.log('Condition list set to ' + conditionList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshConditionList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larping/api/condition'
				if (search !== null && search !== '') {
					endpoint = endpoint + '?_search=' + search
				}
				return fetch(
					endpoint, {
						method: 'GET',
					},
				)
					.then(
						(response) => {
							response.json().then(
								(data) => {
									this.setConditionList(data.results)
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
