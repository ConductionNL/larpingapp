---
status: active
---

# Admin Settings Implementation

## Why

The admin settings spec (SET-003) identified that `lib/Settings/LarpingAppAdmin.php` originally implemented `IIconSection` instead of `ISettings`. This has been fixed, but the SettingsController test is incomplete -- it skips required constructor parameters (container, appManager, groupManager, userSession) and the `index()` method has `@NoAdminRequired` which contradicts the spec requirement SET-050 that it should be admin-only. Additionally, the SettingsService and related services need comprehensive test coverage to verify spec compliance.
