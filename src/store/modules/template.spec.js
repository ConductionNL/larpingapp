/* eslint-disable no-console */
import { setActivePinia, createPinia } from 'pinia'

import { useThemeStore } from './theme.js'
import { mockTheme, Theme } from '../../entities/index.js'

describe(
	'Theme Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets theme item correctly', () => {
				const store = useThemeStore()

				store.setThemeItem(mockTheme()[0])

				expect(store.themeItem).toBeInstanceOf(Theme)
				expect(store.themeItem).toEqual(mockTheme()[0])
				expect(store.themeItem.validate().success).toBe(true)

				store.setThemeItem(mockTheme()[1])

				expect(store.themeItem).toBeInstanceOf(Theme)
				expect(store.themeItem).toEqual(mockTheme()[1])
				expect(store.themeItem.validate().success).toBe(true)

				store.setThemeItem(mockTheme()[2])

				expect(store.themeItem).toBeInstanceOf(Theme)
				expect(store.themeItem).toEqual(mockTheme()[2])
				expect(store.themeItem.validate().success).toBe(false)
			},
		)

		it(
			'sets theme list correctly', () => {
				const store = useThemeStore()

				store.setThemeList(mockTheme())

				expect(store.themeList).toHaveLength(mockTheme().length)

				expect(store.themeList[0]).toBeInstanceOf(Theme)
				expect(store.themeList[0]).toEqual(mockTheme()[0])
				expect(store.themeList[0].validate().success).toBe(true)

				expect(store.themeList[1]).toBeInstanceOf(Theme)
				expect(store.themeList[1]).toEqual(mockTheme()[1])
				expect(store.themeList[1].validate().success).toBe(true)

				expect(store.themeList[2]).toBeInstanceOf(Theme)
				expect(store.themeList[2]).toEqual(mockTheme()[2])
				expect(store.themeList[2].validate().success).toBe(false)
			},
		)
	},
)
