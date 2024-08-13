/* eslint-disable no-console */
import { Item } from './item'
import { mocItem } from './item.mock'

describe('Organisation Store', () => {
	it('create Organisation entity with full data', () => {
		const organisation = new Organisation(mockOrganisation()[0])

		expect(organisation).toBeInstanceOf(Organisation)
		expect(organisation).toEqual(mockOrganisation()[0])

		expect(organisation.validate().success).toBe(true)
	})

	it('create Organisation entity with partial data', () => {
		const organisation = new Organisation(mockOrganisation()[1])

		expect(organisation).toBeInstanceOf(Organisation)
		expect(organisation.id).toBe(mockOrganisation()[1].id)
		expect(organisation.title).toBe(mockOrganisation()[1].title)
		expect(organisation.summary).toBe(mockOrganisation()[1].summary)
		expect(organisation.description).toBe(mockOrganisation()[1].description)
		expect(organisation.oin).toBe('')
		expect(organisation.tooi).toBe('')
		expect(organisation.rsin).toBe('')
		expect(organisation.pki).toBe('')

		expect(organisation.validate().success).toBe(true)
	})

	it('create Organisation entity with falsy data', () => {
		const organisation = new Organisation(mockOrganisation()[2])

		expect(organisation).toBeInstanceOf(Organisation)
		expect(organisation).toEqual(mockOrganisation()[2])

		expect(organisation.validate().success).toBe(false)
	})
})
