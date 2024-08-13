export type TAbility = {
    id: string
	reference: string
	title: string
	summary: string
	description: string
	labels: string[]
	accessUrl: string
	downloadUrl: string
	type: string
	extension: string
	size: string
	anonymization: {
        anonymized: boolean
        results: string
    }
	language: {
        code: string
        level: string
    }
    versionOf: string
    hash: string
    published: string | Date
    modified: string | Date
    license: string
}
