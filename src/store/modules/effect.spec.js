/* eslint-disable no-console */
import { setActivePinia, createPinia } from 'pinia'

import { useMetadataStore } from './metadata.js'
import { Metadata, mockMetadata } from '../../entities/index.js'

describe(
	'Metadata Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets metadata item correctly', () => {
				const store = useMetadataStore()

				store.setMetaDataItem(mockMetadata()[0])

				expect(store.metaDataItem).toBeInstanceOf(Metadata)
				expect(store.metaDataItem).toEqual(mockMetadata()[0])

				expect(store.metaDataItem.validate().success).toBe(true)
			},
		)

		it(
			'sets metadata item with string "properties" property', () => {
				const store = useMetadataStore()

				// stringify json data
				const mockData = mockMetadata()[0]
				mockData.properties = JSON.stringify(mockData.properties)

				store.setMetaDataItem(mockData)

				expect(store.metaDataItem).toBeInstanceOf(Metadata)
				expect(store.metaDataItem).toEqual(mockData)

				expect(store.metaDataItem.validate().success).toBe(true)
			},
		)

		it(
			'sets metadata list correctly', () => {
				const store = useMetadataStore()

				store.setMetaDataList(mockMetadata())

				expect(store.metaDataList[0]).toBeInstanceOf(Metadata)
				expect(store.metaDataList[0]).toEqual(mockMetadata()[0])

				expect(store.metaDataList[0].validate().success).toBe(true)
			},
		)

		it(
			'get metadata property from key', () => {
				const store = useMetadataStore()

				store.setMetaDataItem(mockMetadata()[0])
				store.setMetadataDataKey('test')

				expect(store.metaDataItem).toEqual(mockMetadata()[0])
				expect(store.metadataDataKey).toBe('test')
			},
		)
	},
)
