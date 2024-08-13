/* eslint-disable no-console */
import { Condition } from './condition'
import { mockCondition } from './condition.mock'

describe('Configuration Store', () => {
	it('create Configuration entity with full data', () => {
		const configuration = new Configuration(mockConfiguration()[0])

		expect(configuration).toBeInstanceOf(Configuration)
		expect(configuration).toEqual(mockConfiguration()[0])

		expect(configuration.validate().success).toBe(true)
	})

	it('create Configuration entity with partial data', () => {
		const configuration = new Configuration(mockConfiguration()[1])

		expect(configuration).toBeInstanceOf(Configuration)
		expect(configuration.useElastic).toBe(mockConfiguration()[1].useElastic)

		expect(configuration.validate().success).toBe(true)
	})

	it('create Configuration entity with falsy data', () => {
		const configuration = new Configuration(mockConfiguration()[2])

		expect(configuration).toBeInstanceOf(Configuration)
		expect(configuration).toEqual(mockConfiguration()[2])

		expect(configuration.validate().success).toBe(false)
	})
})
