/* eslint-disable no-console */
import { setActivePinia, createPinia } from 'pinia'

import { useDirectoryStore } from './directory.js'
import { Listing, mockListings } from '../../entities/index.js'

describe(
	'Directory Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets listing item correctly', () => {
				const store = useDirectoryStore()

				store.setListingItem(mockListings()[0])

				expect(store.listingItem).toBeInstanceOf(Listing)
				expect(store.listingItem).toEqual(mockListings()[0])

				expect(store.listingItem.validate().success).toBe(true)
			})

		it(
			'sets listings list correctly', () => {
				const store = useDirectoryStore()

				store.setListingList(mockListings())

				expect(store.listingList).toHaveLength(mockListings().length)

				// list item 1
				expect(store.listingList[0]).toBeInstanceOf(Listing)
				expect(store.listingList[0]).toEqual(mockListings()[0])

				expect(store.listingList[0].validate().success).toBe(true)

				// list item 2
				expect(store.listingList[1]).toBeInstanceOf(Listing)
				expect(store.listingList[1]).toEqual(mockListings()[1])

				expect(store.listingList[1].validate().success).toBe(true)

				// list item 3
				expect(store.listingList[2]).toBeInstanceOf(Listing)
				expect(store.listingList[2]).toEqual(mockListings()[2])

				expect(store.listingList[2].validate().success).toBe(false)
			})
	})
