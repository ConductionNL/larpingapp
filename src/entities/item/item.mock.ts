import { Item } from './item'
import { TItem } from './item.types'

export const mockOrganisationData = (): TItem[] => [
	{ // full data
		id: '1',
		title: 'Decat',
		summary: 'a short form summary',
		description: 'a really really long description about this organisation',
		oin: 'string',
		tooi: 'string',
		rsin: 'string',
		pki: 'string',
	},
	// @ts-expect-error -- missing oin, tooi, rsin and pki properties
	{
		id: '2',
		title: 'Woo',
		summary: 'a short form summary',
		description: 'a really really long description about this organisation',
	},
	{ // invalid data
		id: '3',
		title: '',
		summary: 'a short form summary',
		description: 'a really really long description about this organisation',
		oin: 'string',
		tooi: 'string',
		rsin: 'string',
		pki: 'string',
	},
]

export const mockOrganisation = (data: TOrganisation[] = mockOrganisationData()): TOrganisation[] => data.map(item => new Organisation(item))
