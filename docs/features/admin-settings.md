# Admin Settings

## Overview

The Admin Settings feature provides per-object-type data source configuration for LarpingApp, allowing administrators to choose whether each of the 10 entity types (ability, character, condition, effect, event, item, player, setting, skill, template) is stored in the internal Nextcloud database or in an OpenRegister instance.

## Current State

The LarpingApp admin settings section is **not accessible** in the Nextcloud admin panel. Navigating to `/settings/admin/larpingapp` returns "Access forbidden". This is a known bug: the `lib/Settings/LarpingAppAdmin.php` class implements `IIconSection` instead of `ISettings`, which prevents the settings panel content from rendering.

The settings section does not appear in the admin sidebar navigation.

![Admin Settings - Nextcloud admin panel](../screenshots/admin-settings.png)
*The Nextcloud admin panel -- note the absence of a "Larping" section in the sidebar.*

## Features

When properly configured, the admin settings panel provides:

- A list of all 10 configurable object types
- A source selector for each type: "Internal" or "Open Register"
- When "Open Register" is selected: a register dropdown populated from available OpenRegister registers
- When a register is selected: a schema dropdown populated from that register's schemas
- Settings persistence via Nextcloud's `IAppConfig`
- A REST API for programmatic configuration

## Technical Details

| Component | Path |
|-----------|------|
| Section class | `lib/Sections/LarpingAppAdmin.php` |
| Settings class | `lib/Settings/LarpingAppAdmin.php` |
| Vue component | `src/views/settings/Settings.vue` |
| Entry point | `settings` (webpack) |
| Priority | 55 |

### Known Bugs

- **SET-003**: `lib/Settings/LarpingAppAdmin.php` implements `IIconSection` instead of `ISettings`, preventing the admin panel from rendering content.

### Configuration Keys (IAppConfig)

Each object type has three config keys:
- `{type}_source` -- "internal" or "openregister"
- `{type}_register` -- OpenRegister register ID
- `{type}_schema` -- OpenRegister schema ID

## Related Specs

- [Admin Settings Spec](../../openspec/specs/admin-settings/spec.md)
