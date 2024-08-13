/* eslint-disable no-console */
import { Ability } from './ability'
import { mockAbility } from './ability.mock'

describe('Attachment Store', () => {
	it('create Attachment entity with full data', () => {
		const attachment = new Attachment(mockAttachments()[0])

		expect(attachment).toBeInstanceOf(Attachment)
		expect(attachment).toEqual(mockAttachments()[0])

		expect(attachment.validate().success).toBe(true)
	})

	it('create Attachment entity with partial data', () => {
		const attachment = new Attachment(mockAttachments()[1])

		expect(attachment).toBeInstanceOf(Attachment)
		expect(attachment).toEqual(mockAttachments()[1])

		expect(attachment.validate().success).toBe(true)
	})

	it('create Attachment entity with falsy data', () => {
		const attachment = new Attachment(mockAttachments()[2])

		expect(attachment).toBeInstanceOf(Attachment)
		expect(attachment).toEqual(mockAttachments()[2])

		expect(attachment.validate().success).toBe(false)
	})
})
