/* eslint-disable no-console */
import { createPinia, setActivePinia } from 'pinia'

import { mockOrganisation, Organisation } from '../../entities/index.js'
import { useOrganisationStore } from './organisation.js'

describe(
	'Organisation Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets organisation item correctly', () => {
				const store = useOrganisationStore()

				store.setOrganisationItem(mockOrganisation()[0])

				expect(store.organisationItem).toBeInstanceOf(Organisation)
				expect(store.organisationItem).toEqual(mockOrganisation()[0])
				expect(store.organisationItem.validate().success).toBe(true)

				store.setOrganisationItem(mockOrganisation()[1])

				expect(store.organisationItem).toBeInstanceOf(Organisation)
				expect(store.organisationItem).toEqual(mockOrganisation()[1])
				expect(store.organisationItem.validate().success).toBe(true)

				store.setOrganisationItem(mockOrganisation()[2])

				expect(store.organisationItem).toBeInstanceOf(Organisation)
				expect(store.organisationItem).toEqual(mockOrganisation()[2])
				expect(store.organisationItem.validate().success).toBe(false)
			},
		)

		it(
			'sets organisation list correctly', () => {
				const store = useOrganisationStore()

				store.setOrganisationList(mockOrganisation())

				expect(store.organisationList).toHaveLength(mockOrganisation().length)

				expect(store.organisationList[0]).toBeInstanceOf(Organisation)
				expect(store.organisationList[0]).toEqual(mockOrganisation()[0])
				expect(store.organisationList[0].validate().success).toBe(true)

				expect(store.organisationList[1]).toBeInstanceOf(Organisation)
				expect(store.organisationList[1]).toEqual(mockOrganisation()[1])
				expect(store.organisationList[1].validate().success).toBe(true)

				expect(store.organisationList[2]).toBeInstanceOf(Organisation)
				expect(store.organisationList[2]).toEqual(mockOrganisation()[2])
				expect(store.organisationList[2].validate().success).toBe(false)
			},
		)
	},
)
