/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Character } from '../../entities/index.js'

export const useCharacterStore = defineStore(
	'character', {
		state: () => ({
			characterItem: false,
			characterList: [],
		}),
		actions: {
			setCharacterItem(characterItem) {
				this.characterItem = characterItem && new Character(characterItem)
				console.log('Active character item set to ' + characterItem)
			},
			setCharacterList(characterList) {
				this.characterList = characterList.map(
					(characterItem) => new Character(characterItem),
				)
				console.log('Character list set to ' + characterList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshCharacterList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/characters'
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
									this.setCharacterList(data.results)
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
