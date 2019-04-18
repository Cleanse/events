# Events Plugin (WIP)
A round-robin and bracket events plugin for OctoberCMS.

###### Brainstorming
- Events Admin '/events/a/:slug'
  - Admin{ComponentId}
- Events Broadcast '/broadcast/event/latest' and/or '/broadcast/event/:slug/:scene?'
  - Broadcast{ComponentId}
- Events Frontend  '/events/:slug'
  - {ComponentId} (list of events and event)
- Events CRUD API  ?? '/api/broadcast/event/:slug'
- *Events Sign up system? (maybe whoever takes over can add it in. I can if SAAS project is interesting.*
- ~~Database will contain the access to different formats. "formats" directory will have sub-folders with a proper
naming scheme to import the entire format config, creation, update scheme.~~
- ValidateEvent, ManageEvent, UpdateEvent, <??>
- Overlay ('/event/:id/broadcast') will work similarly to '/event/:id/placement' does.
- Editing a match will pop out similarly to editing a team (using a modal).
- Needs 'user' or 'role' support. Right now, all admin can control all events etc.
- After matches are generated, hide 'event type settings' from Settings tab.

###### Frontend? Controllers (October backend)
- !!! Eventual idea: Event/Overlay SAAS.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?
- [ ] '/event/:id/manage' route needs to be added for managing the overlay.

### Week of April 15th TODO
- [ ] Get list of scenes needed for The Feast World Cup from Kaze [broadcast]
- [ ] Use https://github.com/xoco70/laravel-tournaments/blob/master/src/TreeGen/CreateSingleEliminationTree.php
  - Get Round names and use for backend display.
  - Need to create round robin overlay system that can load groups individually? Maybe a way to to fit a big Round
    Robin stage (JP).