/* eslint-disable no-console */
import { defineStore } from 'pinia'
import { Template } from '../../entities/index.js'

export const useTemplateStore = defineStore(
	'template', {
		state: () => ({
			templateItem: false,
			templateList: [],
		}),
		actions: {
			setTemplateItem(templateItem) {
				this.templateItem = templateItem && new Template(templateItem)
				console.log('Active template item set to ' + templateItem)
			},
			setTemplateList(templateList) {
				this.templateList = templateList.map(
					(templateItem) => new Template(templateItem),
				)
				console.log('Template list set to ' + templateList.length + ' items')
			},
			/* istanbul ignore next */ // ignore this for Jest until moved into a service
			async refreshTemplateList(search = null) {
				// @todo this might belong in a service?
				let endpoint = '/index.php/apps/larpingapp/api/templates'
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
									this.refreshTemplateList(data.results)
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