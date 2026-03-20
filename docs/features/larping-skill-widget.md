# Larping Skill Widget

## Overview

The Larping Skill Widget was listed as a planned feature for the Nextcloud Dashboard widget system. It would provide a dashboard widget showing skill-related information for the logged-in user's characters.

## Status: No Spec Found

There is no spec file for `larping-skill-widget` in the `openspec/specs/` directory. This feature may be planned but has not yet been specified.

## Potential Features (Based on Other Conduction Apps)

Based on the pattern used by other Conduction apps (e.g., OpenCatalogi's `catalogiWidget`, Pipelinq's `dealsOverviewWidget`), a skill widget would likely:

- Display as a dashboard widget on the Nextcloud Dashboard
- Show the logged-in user's character skills at a glance
- Provide quick links to the full skills list
- Be registered via an entry point in `webpack.config.js`
- Use the `OCA.Dashboard.register()` API

## Technical Notes

No widget-specific entry point was found in the current LarpingApp webpack configuration. The app currently has a `main` entry point and a `settings` entry point but no widget entry points.
