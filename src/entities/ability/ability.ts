import { SafeParseReturnType, z } from 'zod'
import { TAbility } from './ability.types'

export class Ability implements TAbility {

	public id: string
	public reference: string
	public title: string
	public summary: string
	public description: string
	public labels: string[]
	public accessUrl: string
	public downloadUrl: string
	public type: string
	public extension: string
	public size: string
	public anonymization: {
        anonymized: boolean
        results: string
    }

	public language: {
        code: string
        level: string
    }

	public versionOf: string
	public hash: string
	public published: string | Date
	public modified: string | Date
	public license: string

	constructor(data: TAbility) {
		this.hydrate(data)
	}

	/* istanbul ignore next */ // Jest does not recognize the code coverage of these 2 methods
	private hydrate(data: TAbility) {
		this.id = data.id?.toString() || ''
		this.reference = data.reference || ''
		this.title = data.title || ''
		this.summary = data.summary || ''
		this.description = data.description || ''
		this.labels = data.labels || []
		this.accessUrl = data.accessUrl || ''
		this.downloadUrl = data.downloadUrl || ''
		this.type = data.type || ''
		this.extension = data.extension || ''
		this.size = data.size || ''
		this.anonymization = (!Array.isArray(data.anonymization) && data.anonymization) || {
			anonymized: false,
			results: '',
		}

		this.language = (!Array.isArray(data.language) && data.language) || {
			code: '',
			level: '',
		}

		this.versionOf = data.versionOf || ''
		this.hash = data.hash || ''
		this.published = data.published || ''
		this.modified = data.modified || ''
		this.license = data.license || ''
	}

	/* istanbul ignore next */
	public validate(): SafeParseReturnType<TAbility, unknown> {
		// https://conduction.stoplight.io/docs/open-catalogi/lsigtx7cafbr7-create-attachment
		const schema = z.object({
			reference: z.string().max(255),
			title: z.string().max(255), // .min(1) on a string functionally works the same as a nonEmpty check (SHOULD NOT BE COMBINED WITH .OPTIONAL())
			summary: z.string().max(255),
			description: z.string().max(2555),
			labels: z.string().array(),
			accessUrl: z.string().url().or(z.literal('')),
			downloadUrl: z.string().url().or(z.literal('')),
			type: z.string(),
			anonymization: z.object({
				anonymized: z.boolean(),
				results: z.string().max(2500),
			}),
			language: z.object({
				// this regex checks if the code has either 2 or 3 characters per group, and the -aaa after the first is optional
				code: z.string()
					.max(7)
					.regex(/([a-z]{2,3})(-[a-z]{2,3})?/g, 'language code is not a valid ISO 639-1 code (e.g. en-us)')
					.or(z.literal('')),
				level: z.string()
					.max(2)
					.regex(/(A|B|C)(1|2)/g, 'language level is not a valid CEFRL level (e.g. A1)')
					.or(z.literal('')),
			}),
			versionOf: z.string(),
			published: z.string().datetime().or(z.literal('')),
			license: z.string(),
		})

		const result = schema.safeParse({
			...this,
		})

		return result
	}

}
