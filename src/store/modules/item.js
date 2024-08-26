/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Item } from '../../entities/index.js'

export const useItemStore = defineStore(
	'item', {
		state: () => ({
			itemItem: false,
			itemList: [],
		}),
		actions: {
			setItemItem(itemItem) {
				this.itemItem = itemItem && new Item(itemItem)
				console.log('Active item item set to ' + itemItem)
			},
			setItemList(itemList) {
				this.itemList = itemList.map(
					(itemItem) => new Item(itemItem),
				)
				console.log('Item list set to ' + itemList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshItemList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/items'
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
									this.setItemList(data.results)
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
