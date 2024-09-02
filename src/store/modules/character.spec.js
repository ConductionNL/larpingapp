/* eslint-disable no-console */
import { createPinia, setActivePinia } from 'pinia'
import { Configuration, mockConfiguration } from '../../entities/index.js'
import { useConfigurationStore } from './configuration.js'

describe(
	'Configuration Store', () => {
		beforeEach(
			() => {
				setActivePinia(createPinia())
			},
		)

		it(
			'sets configuration item correctly', () => {
				const store = useConfigurationStore()

				store.setConfigurationItem(mockConfiguration()[0])

				expect(store.configurationItem).toBeInstanceOf(Configuration)
				expect(store.configurationItem).toEqual(mockConfiguration()[0])
				expect(store.configurationItem.validate().success).toBe(true)

				store.setConfigurationItem(mockConfiguration()[1])

				expect(store.configurationItem).toBeInstanceOf(Configuration)
				expect(store.configurationItem).toEqual(mockConfiguration()[1])
				expect(store.configurationItem.validate().success).toBe(true)

				store.setConfigurationItem(mockConfiguration()[2])

				expect(store.configurationItem).toBeInstanceOf(Configuration)
				expect(store.configurationItem).toEqual(mockConfiguration()[2])
				expect(store.configurationItem.validate().success).toBe(false)
			},
		)
	},
)
