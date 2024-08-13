/* eslint-disable no-console */
import { Effect } from './effect'
import { mockEffect } from './effect.mock'

describe('Listing Store', () => {
	it('create Listing entity with full data', () => {
		const listing = new Listing(mockListings()[0])

		expect(listing).toBeInstanceOf(Listing)
		expect(listing).toEqual(mockListings()[0])

		expect(listing.validate().success).toBe(true)
	})

	it('create Listing entity with partial data', () => {
		const listing = new Listing(mockListings()[1])

		expect(listing).toBeInstanceOf(Listing)
		expect(listing).toEqual(mockListings()[1])

		expect(listing.validate().success).toBe(true)
	})

	it('create Listing entity with falsy data', () => {
		const listing = new Listing(mockListings()[2])

		expect(listing).toBeInstanceOf(Listing)
		expect(listing).toEqual(mockListings()[2])

		expect(listing.validate().success).toBe(false)
	})
})
