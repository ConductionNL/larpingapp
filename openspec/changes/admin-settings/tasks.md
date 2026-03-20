---
status: active
---

# Admin Settings Tasks

- [x] Fix SettingsController.index() to be admin-only (remove @NoAdminRequired)
- [x] Fix SettingsControllerTest to mock all required constructor dependencies
- [x] Add SettingsServiceTest covering getSettings, updateSettings, CONFIG_KEYS filtering
- [x] Add DashboardControllerTest covering page() returns correct TemplateResponse
