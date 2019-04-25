# Events Plugin (WIP)
A round-robin and bracket events plugin for OctoberCMS.

###### Brainstorming
- Events Admin '/events/a/:slug'
  - Admin{ComponentId}
- Events Broadcast '/event/broadcast/:broadcast_id/:overlay_type'
  - cleanseEventOverlay{overlay_type}
- Events CRUD API  ?? '/api/broadcast/:broadcast_id/:overlay_type'
- Events Frontend  '/events/:slug'
  - {ComponentId} (list of events and event)
- *Events Sign up system? (maybe whoever takes over can add it in. I can if SAAS project is interesting.*
- ValidateEvent, ManageEvent, UpdateEvent, <??>
- Overlay ('/event/:id/broadcast') will work similarly to '/event/:id/placement' does.
- Editing a match will pop out similarly to editing a team (using a modal).
- Needs 'user' or 'role' support. Right now, all admin can control all events etc.
- After matches are generated, hide 'event type settings' from Settings tab.
- Broadcast Panel looks poorly done with the lack of descriptors for matches without teams.
- Manage Teams searchable, sortable (goes for most lists).
- User system.
- Backend panel to add people to event rank.
- Description/short tutorial how to create unique templates.
- User access to events should be creator or God admin.

###### Frontend? Controllers (October backend)
- !!! Eventual idea: Event/Overlay SAAS.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?
- [ ] '/event/:id/manage' route needs to be added for managing the overlay.

### TODO before FWC
- Write UpdateEvent for match handling by type.
- Match Updating Form. [important]
- Broadcast Control Panel.
- [ ] Get list of scenes needed for The Feast World Cup from Kaze [broadcast]
- Need to create round robin overlay system that can load groups individually? Maybe a way to to fit a big Round
Robin stage (JP).