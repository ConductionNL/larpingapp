# PDF Character Sheet Export

## Overview

Enables game masters and players to export character data as downloadable PDF files. PDF rendering and template management are delegated to the DocuDesk app -- LarpingApp's controller resolves DocuDesk's `PdfService` and `TemplateService` via Nextcloud's DI container.

This feature has no dedicated UI page; it is accessed through the character detail view via a download button.

## Features

### PDF Generation (via DocuDesk)
- Generate a PDF from a character and a selected template
- Character data (including computed stats) is passed as the data context
- Template content is fetched from DocuDesk's TemplateService
- PDF returned as a `DataDownloadResponse` with filename `{characterName}_character_sheet.pdf`
- Download URL format: `/characters/{characterId}/download/{templateId}`

### DocuDesk Dependency Check
- Checks `IAppManager::isEnabledForUser('docudesk')` before attempting PDF generation
- Returns 424 (Failed Dependency) if DocuDesk is not installed/enabled
- Error message: "PDF generation requires the DocuDesk app to be installed and enabled"

### Template Management
- Templates are managed in DocuDesk, scoped to LarpingApp via `namespace=larpingapp` filter
- Template listing endpoint: `GET /templates` (filtered by namespace)
- Templates can include Twig-style variables for character data

## Technical Details

| Component | Path |
|-----------|------|
| Controller | `lib/Controller/CharactersController.php` (downloadPdf method) |
| DocuDesk PdfService | Resolved via DI container |
| DocuDesk TemplateService | Resolved via DI container |

### API Endpoint

```
GET /api/characters/{characterId}/download/{templateId}
```

**Response:** Binary PDF file with `application/pdf` MIME type

**Error responses:**
- 424: DocuDesk not installed/enabled
- 404: Character or template not found

## Related Specs

- [PDF Export Spec](../../openspec/specs/pdf-export/spec.md)
