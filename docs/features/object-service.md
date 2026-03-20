# Object Service

## Overview

The `ObjectService` (`lib/Service/ObjectService.php`) is the central data access layer for LarpingApp. It provides a generic CRUD interface for all 10 entity types by dispatching operations to the appropriate mapper -- either an internal Nextcloud QBMapper or an OpenRegister mapper, based on per-type configuration.

This is a **backend-only** component with no dedicated UI.

## Features

### Mapper Dispatch
- Reads `{objectType}_source` from IAppConfig to determine data source
- Supports "internal" (Nextcloud QBMapper) and "openregister" (OpenRegister mapper) sources
- Throws exceptions if OpenRegister is configured but unavailable
- Throws InvalidArgumentException for unknown object types
- Covers all 10 types: ability, character, condition, effect, event, item, player, setting, skill, template

### Object Retrieval (`getObjects()`)
- Accepts parameters: objectType, limit, offset, filters, sort, search, extend
- Returns faceted search result envelopes with results, facets, count, limit, offset, total
- Supports entity extension (resolving UUID references to full objects)

### Object CRUD
- `getObject()` -- Get single object by ID
- `saveObject()` -- Create or update an object
- `deleteObject()` -- Delete an object by ID

### Additional Features
- Request parameter parsing from HTTP request objects
- Audit trail retrieval
- Object locking and unlocking
- Object version reverting
- File access and attachment management

## Technical Details

| Component | Path |
|-----------|------|
| Service | `lib/Service/ObjectService.php` |
| Configuration | IAppConfig keys: `{type}_source`, `{type}_register`, `{type}_schema` |

### Supported Object Types

| Type | Internal Mapper | OpenRegister Schema |
|------|----------------|-------------------|
| ability | `AbilityMapper` | Configurable |
| character | `CharacterMapper` | Configurable |
| condition | `ConditionMapper` | Configurable |
| effect | `EffectMapper` | Configurable |
| event | `EventMapper` | Configurable |
| item | `ItemMapper` | Configurable |
| player | `PlayerMapper` | Configurable |
| setting | `SettingMapper` | Configurable |
| skill | `SkillMapper` | Configurable |
| template | `TemplateMapper` | Configurable |

### Error Handling

- OpenRegister unavailable: throws `Exception`
- Missing register/schema config: throws `Exception`
- Unknown object type: throws `InvalidArgumentException`

## Related Specs

- [Object Service Spec](../../openspec/specs/object-service/spec.md)
