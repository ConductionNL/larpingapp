/* eslint-disable no-console */
import { Skill } from './skill'
import { mockSkill } from './skill.mock'

describe('Theme Store', () => {
	it('create Theme entity with full data', () => {
		const theme = new Theme(mockTheme()[0])

		expect(theme).toBeInstanceOf(Theme)
		expect(theme).toEqual(mockTheme()[0])

		expect(theme.validate().success).toBe(true)
	})

	it('create Theme entity with partial data', () => {
		const theme = new Theme(mockTheme()[1])

		expect(theme).toBeInstanceOf(Theme)
		expect(theme.id).toBe(mockTheme()[1].id)
		expect(theme.title).toBe(mockTheme()[1].title)
		expect(theme.summary).toBe(mockTheme()[1].summary)
		expect(theme.description).toBe(mockTheme()[1].description)
		expect(theme.image).toBe('')

		expect(theme.validate().success).toBe(true)
	})

	it('create Theme entity with falsy data', () => {
		const theme = new Theme(mockTheme()[2])

		expect(theme).toBeInstanceOf(Theme)
		expect(theme).toEqual(mockTheme()[2])

		expect(theme.validate().success).toBe(false)
	})
})
