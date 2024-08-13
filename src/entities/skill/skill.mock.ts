import { Skill } from './skill'
import { TSkill } from './skill.types'

export const mockSkillData = (): TSkill[] => [
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

export const mockSkill = (data: TSkill[] = mockSkillData()): TSkill[] => data.map(item => new Skill(item))
