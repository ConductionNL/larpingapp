/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Player} from '../../entities/index.js'

export const usePlayerStore = defineStore(
	'player', {
		state: () => ({
			playerItem: false,
			playerList: [],
		}),
		actions: {
			setPlayerItem(playerItem) {
				this.playerItem = playerItem && new Player(playerItem)
				console.log('Active player item set to ' + playerItem)
			},
			setPlayerList(playerList) {
				this.playerList = playerList.map(
					(playerItem) => new Player(playerItem),
				)
				console.log('Player list set to ' + playerList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshPlayerList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larping/api/player'
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
									this.refreshPlayerList(data.results)
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