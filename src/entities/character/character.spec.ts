/* eslint-disable no-console */
import { Character } from './character'
import { mockCharacter } from './character.mock'

describe('Catalogi Store', () => {
	it('create Catalogi entity with full data', () => {
		const catalogi = new Catalogi(mockCatalogi()[0])

		expect(catalogi).toBeInstanceOf(Catalogi)
		expect(catalogi).toEqual(mockCatalogi()[0])

		expect(catalogi.validate().success).toBe(true)
	})

	it('create Catalogi entity with partial data', () => {
		const catalogi = new Catalogi(mockCatalogi()[1])

		expect(catalogi).toBeInstanceOf(Catalogi)
		expect(catalogi).toEqual(mockCatalogi()[1])

		expect(catalogi.validate().success).toBe(true)
	})

	it('create Catalogi entity with falsy data', () => {
		const catalogi = new Catalogi(mockCatalogi()[2])

		expect(catalogi).toBeInstanceOf(Catalogi)
		expect(catalogi).toEqual(mockCatalogi()[2])

		expect(catalogi.validate().success).toBe(false)
	})
})
