/* eslint-disable no-console */
// The store script handles app wide variables (or state), for the use of these variables and there governing concepts read the design.md
import pinia from '../pinia.js'
import { useObjectStore } from './modules/object.js'
import { useNavigationStore } from './modules/navigation.js'

const objectStore = useObjectStore(pinia)
const navigationStore = useNavigationStore(pinia)

export {
	// generic
	navigationStore,
	objectStore,
}
