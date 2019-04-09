# Events Plugin (WIP)
A round-robin and bracket events plugin for OctoberCMS.

### Bracket Stage
- [ ] Generate *Best-of-**X*** single-elimination bracket.
- [ ] Generate *Best-of-**X*** double-elimination bracket.
- [ ] Option to grab the top **X** teams from a *Round Robin Group Stage* event.

###### Brainstorming
- Events Admin '/events/a/:slug'
  - Admin{ComponentId}
- Events Broadcast '/broadcast/event/latest' and/or '/broadcast/event/:slug/:scene?'
  - Broadcast{ComponentId}
- Events Frontend  '/events/:slug'
  - {ComponentId} (list of events and event)
- Events CRUD API  ?? '/api/broadcast/event/:slug'
- *Events Sign up system? (maybe whoever takes over can add it in. I can if SAAS project is interesting.*
- Database will contain the access to different formats. "formats" directory will have sub-folders with a proper
naming scheme to import the entire format config, creation, update scheme.
- ValidateEvent, ManageEvent, UpdateEvent, 

###### Frontend? Controllers (October backend)
- !!! Eventual idea: Event/Overlay SAAS.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?
- [ ] '/event/:id/manage' route needs to be added for managing the overlay.

### Week of April 8th TODO
- [ ] Get match creation done [admin].
- [ ] Get broadcast match order done [broadcast].
- [ ] Get broadcast event (and match) activating (and updating) done [broadcast].
- [ ] Get list of scenes needed for The Feast World Cup from Kaze [broadcast].
- [ ] Think about re-formatting configs for the many formats.
- [ ] Add winner table(?)
- [ ] 'Delete' method hides the event (todo: public list will hide 'deleted' events)