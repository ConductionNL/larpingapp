/* eslint-disable no-console */
import { Event } from './event'
import { mockEvent } from './event.mock'

describe('Metadata entity', () => {
	it('create Metadata entity with full data', () => {
		const metadata = new Metadata(mockMetadata()[0])

		expect(metadata).toBeInstanceOf(Metadata)
		expect(metadata).toEqual(mockMetadata()[0])

		expect(metadata.validate().success).toBe(true)
	})

	it('create Metadata entity with partial data', () => {
		const metadata = new Metadata(mockMetadata()[1])

		expect(metadata).toBeInstanceOf(Metadata)
		expect(metadata).toEqual(mockMetadata()[1])

		expect(metadata.validate().success).toBe(true)
	})

	it('create Metadata entity with falsy data', () => {
		const metadata = new Metadata(mockMetadata()[2])

		expect(metadata).toBeInstanceOf(Metadata)
		expect(metadata).toEqual(mockMetadata()[2])

		expect(metadata.validate().success).toBe(false)
	})
})
