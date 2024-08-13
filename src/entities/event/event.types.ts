export type TEvent = {
    id: string
    title: string
    description: string
    version: string
    required: string[]
    properties: Record<string, {
        title: string
        description: string
        type: 'string' | 'number' | 'integer' | 'object' | 'array' | 'boolean' | 'dictionary'
        format: 'date' | 'time' | 'duration' | 'date-time' | 'url' | 'uri' | 'uuid' | 'email' | 'idn-email' | 'hostname' | 'idn-hostname' | 'ipv4' | 'ipv6' | 'uri-reference' | 'iri' | 'iri-reference' | 'uri-template' | 'json-pointer' | 'regex' | 'binary' | 'byte' | 'password' | 'rsin' | 'kvk' | 'bsn' | 'oidn' | 'telephone'
        pattern: number
        default: string
        behavior: string
        required: boolean
        deprecated: boolean
        minLength: number
        maxLength: number
        example: string
        minimum: number
        maximum: number
        multipleOf: number
        exclusiveMin: boolean
        exclusiveMax: boolean
        minItems: number
        maxItems: number
    }>
    archive: {
        valuation: 'b' | 'v' | 'n'
        class: 1 | 2 | 3 | 4 | 5
    }
}
