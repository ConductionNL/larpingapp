/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Event } from '../../entities/index.js'

export const useEventStore = defineStore(
	'event', {
		state: () => ({
			eventItem: false,
			eventList: [],
		}),
		actions: {
			setEventItem(eventItem) {
				this.eventItem = eventItem && new Event(eventItem)
				console.log('Active event item set to ' + eventItem)
			},
			setEventList(eventList) {
				this.eventList = eventList.map(
					(eventItem) => new Event(eventItem),
				)
				console.log('Ability list set to ' + eventList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshEventList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/events'
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
									this.setEventList(data.results)
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