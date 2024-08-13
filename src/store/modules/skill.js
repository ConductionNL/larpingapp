/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Skill } from '../../entities/index.js'

export const useSkillStore = defineStore(
	'skill', {
		state: () => ({
			skillItem: false,
			skillList: [],
		}),
		actions: {
			setSkillItem(skillItem) {
				this.skillItem = skillItem && new Skill(skillItem)
				console.log('Active skill item set to ' + skillItem)
			},
			setSkillList(skillList) {
				this.skillList = skillList.map(
					(skillItem) => new Skill(skillItem),
				)
				console.log('Skill list set to ' + skillList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshSkillList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/skills'
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
									this.refreshSkillList(data.results)
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