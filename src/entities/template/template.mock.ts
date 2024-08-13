import { Template } from './template'
import { TTemplate } from './template.types'

export const mockTemplateData = (): TTemplate[] => [
	{
		id: '1',
		name: 'Decat',
	},
	{
		id: '2',
		name: 'Woo',
	},
	{
		id: '3',
		name: 'Foo',
	},
]

export const mockTemplate = (data: TTemplate[] = mockTemplateData()): TTemplate[] => data.map(item => new Template(item))
