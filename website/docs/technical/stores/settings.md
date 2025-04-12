# Settings Store

The Settings Store is responsible for managing application-wide settings such as register and schema slugs for each object type in the LARPingApp.

## Overview

The Settings Store provides a centralized location for storing and managing configuration settings for the application. It maintains default values for register and schema slugs which will later be connected to a settings API endpoint.

## State

The store maintains the following state:

- `registerSlugs`: An object containing register slugs for each object type (ability, character, event, etc.)
- `schemaSlugs`: An object containing schema slugs for each object type
- `isLoadingSettings`: A boolean indicating whether settings are being loaded
- `isInitialized`: A boolean indicating whether the store has been initialized

## Getters

The store provides the following getters:

- `getSettings`: Returns the complete settings object
- `getRegisterSlug(objectType)`: Returns the register slug for a specific object type
- `getSchemaSlug(objectType)`: Returns the schema slug for a specific object type

## Actions

The store provides the following actions:

- `initialize()`: Initializes the store with default values
- `updateRegisterSlugs(slugs)`: Updates register slugs for all or specific object types
- `updateSchemaSlugs(slugs)`: Updates schema slugs for all or specific object types
- `saveSettings()`: Saves all settings to the backend (currently a stub for future implementation)
- `loadSettings()`: Loads settings from the backend (currently a stub for future implementation)
- `resetToDefaults()`: Resets all settings to default values

## Usage Examples

### Initializing the store

```javascript
import { useSettingsStore } from '../store/modules/settings'

const settingsStore = useSettingsStore()
await settingsStore.initialize()
```

### Getting settings values

```javascript
// Get a specific register slug
const abilityRegister = settingsStore.getRegisterSlug('ability')

// Get a specific schema slug
const characterSchema = settingsStore.getSchemaSlug('character')

// Get all settings
const allSettings = settingsStore.getSettings
```

### Updating settings

```javascript
// Update specific register slugs
settingsStore.updateRegisterSlugs({
  ability: 'custom-register',
  character: 'another-register'
})

// Update specific schema slugs
settingsStore.updateSchemaSlugs({
  ability: 'custom-ability',
  player: 'custom-player'
})

// Reset all settings to defaults
settingsStore.resetToDefaults()
```

## Future Enhancements

The Settings Store is designed to be connected to a backend API in the future. The `saveSettings()` and `loadSettings()` methods are currently implemented as stubs that will be enhanced to communicate with a settings endpoint when it becomes available. 