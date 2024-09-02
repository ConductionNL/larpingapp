/* eslint-disable no-console */
import { setActivePinia, createPinia } from 'pinia'

import { useCatalogiStore } from './catalogi.js'
import { Catalogi, mockCatalogi } from '../../entities/index.js'

describe(
	'Catalogi Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets catalogi item correctly', () => {
				const store = useCatalogiStore()

				store.setCatalogiItem(mockCatalogi()[0])

				expect(store.catalogiItem).toBeInstanceOf(Catalogi)
				expect(store.catalogiItem).toEqual(mockCatalogi()[0])

				expect(store.catalogiItem.validate().success).toBe(true)
			})

		it(
			'sets catalogi list correctly', () => {
				const store = useCatalogiStore()

				store.setCatalogiList(mockCatalogi())

				expect(store.catalogiList).toHaveLength(mockCatalogi().length)

				// list item 1
				expect(store.catalogiList[0]).toBeInstanceOf(Catalogi)
				expect(store.catalogiList[0]).toEqual(mockCatalogi()[0])

				expect(store.catalogiList[0].validate().success).toBe(true)

				// list item 2
				expect(store.catalogiList[1]).toBeInstanceOf(Catalogi)
				expect(store.catalogiList[1]).toEqual(mockCatalogi()[1])

				expect(store.catalogiList[1].validate().success).toBe(true)

				// list item 3
				expect(store.catalogiList[2]).toBeInstanceOf(Catalogi)
				expect(store.catalogiList[2]).toEqual(mockCatalogi()[2])

				expect(store.catalogiList[2].validate().success).toBe(false)
			})
	})
