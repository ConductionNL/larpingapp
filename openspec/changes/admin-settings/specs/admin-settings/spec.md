---
status: active
type: delta
parent: openspec/specs/admin-settings/spec.md
---

# Admin Settings Delta Spec

## Modified Requirements

### SET-050 (MODIFIED)
- `SettingsController.index()` MUST be admin-only: remove `@NoAdminRequired` annotation
- Status: Bug -> Fixed

## Added Requirements

### TEST-001: SettingsController unit tests must mock all constructor dependencies
### TEST-002: SettingsService unit tests must cover CONFIG_KEYS filtering
### TEST-003: SettingsService unit tests must cover getSettings/updateSettings
