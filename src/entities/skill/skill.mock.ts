import { Theme } from './skill'
import { TTheme } from './skill.types'

export const mockThemeData = (): TTheme[] => [
	{ // full data
		id: '1',
		title: 'Decat',
		summary: 'a short form summary',
		description: 'a really really long description about this Theme',
		image: 'string',
	},
	// @ts-expect-error -- expected missing image property
	{ // partial data
		id: '2',
		title: 'Woo',
		summary: 'a short form summary',
		description: 'a really really long description about this Theme',
	},
	{ // invalid data
		id: '3',
		title: '',
		summary: 'a short form summary',
		description: 'a really really long description about this Theme',
		image: 'string',
	},
]

export const mockTheme = (data: TTheme[] = mockThemeData()): TTheme[] => data.map(item => new Theme(item))
