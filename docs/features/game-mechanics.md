# Game Mechanics

## Overview

Game Mechanics covers the interconnected system of Skills, Items, Conditions, Effects, and Abilities (stats) that form the LARP rule engine. Effects are the fundamental building blocks -- they modify ability scores. Skills, items, conditions, and events each contain arrays of effects that are applied to characters.

## Current State

The game mechanics pages (skills, items, conditions, effects) are currently blocked by the OpenRegister availability check in the compiled frontend.

**Source routes:**
- `/#/skills` -- Skills list
- `/#/items` -- Items list
- `/#/conditions` -- Conditions list
- `/#/effects` -- Effects list

![Game Mechanics - Skills page](../screenshots/game-mechanics.png)

## Features

### Skills
- Create skills with name, description, and effect text
- Skills contain arrays of effects that modify character abilities
- Prerequisite system requiring other skills, stats, conditions, effects, or a minimum score
- Skills can have a cost (e.g., XP cost to acquire)

### Items
- Create items with name, description, and effect text
- Items contain arrays of effects applied to characters who carry them
- Items can be assigned to characters

### Conditions
- Create conditions with name and description
- Conditions contain arrays of effects applied while the condition is active
- Conditions can be temporary or permanent

### Effects
- The fundamental building block of the game mechanics system
- Each effect targets a specific ability with a modifier value
- Effects are the bridge between game elements and character stats
- Applied during stat calculation by the CharacterService

### Abilities (Stats)
- Define numeric values characters are scored on (XP, mana, HP, DEX, etc.)
- Each ability has a base value (default 0)
- `allowed_negative` flag controls whether values can go below zero
- Abilities are the targets of effect modifiers

## Technical Details

| Component | Path |
|-----------|------|
| Skill entity | `lib/Db/Skill.php` |
| Item entity | `lib/Db/Item.php` |
| Condition entity | `lib/Db/Condition.php` |
| Effect entity | `lib/Db/Effect.php` |
| Ability entity | `lib/Db/Ability.php` |
| Stat calculation | `lib/Service/CharacterService.php` |
| JSON schemas | `docs/Schema/Skill.json`, `Item.json`, `Condition.json`, `Effect.json`, `Stats.json` |

## Related Specs

- [Game Mechanics Spec](../../openspec/specs/game-mechanics/spec.md)
