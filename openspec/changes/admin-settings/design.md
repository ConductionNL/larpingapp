---
status: active
---

# Admin Settings Design

## Key Decisions

1. **Fix SettingsController.index() security**: Remove `@NoAdminRequired` annotation from `index()` to comply with SET-050 (admin-only access for GET settings).
2. **Fix SettingsControllerTest**: The existing test constructs SettingsController with only `request` and `settingsService`, but the controller requires `container`, `appManager`, `groupManager`, and `userSession` as well. Fix the test to mock all dependencies.
3. **Add SettingsService unit tests**: Cover `getSettings()`, `updateSettings()`, and `getConfigValue()`/`setConfigValue()`.
4. **Add DashboardController unit test**: Verify it returns a TemplateResponse with the correct template name.
5. **Add CharacterService unit tests**: Cover stat calculation engine including `calculateCharacter()` and edge cases.
