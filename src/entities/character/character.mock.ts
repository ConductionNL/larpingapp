import { Catalogi } from './character'
import { TCatalogi } from './character.types'

export const mockCatalogiData = (): TCatalogi[] => [
	{
		id: '1',
		title: 'Decat',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		image: 'string',
		listed: false,
		organisation: '23',
		metadata: ['1', '3'],
	},
	{
		id: '2',
		title: 'Woo',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		image: '',
		listed: false,
		organisation: '23',
		metadata: [],
	},
	{
		id: '3',
		title: 'Foo',
		summary: 'a short form summary',
		description: 'a really really long description about this catalogus',
		image: 'string',
		// @ts-expect-error -- listed needs to be a boolean
		listed: 0.2,
		organisation: '23',
		metadata: ['1', '3'],
	},
]

export const mockCatalogi = (data: TCatalogi[] = mockCatalogiData()): TCatalogi[] => data.map(item => new Catalogi(item))
