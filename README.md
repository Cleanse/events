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
- ~~Database will contain the access to different formats. "formats" directory will have sub-folders with a proper
naming scheme to import the entire format config, creation, update scheme.~~
- ValidateEvent, ManageEvent, UpdateEvent, <??>
- Overlay ('/event/:id/broadcast') will work similarly to '/event/:id/placement' does.
- Editing a match will pop out similarly to editing a team (using a modal).

###### Frontend? Controllers (October backend)
- !!! Eventual idea: Event/Overlay SAAS.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?
- [ ] '/event/:id/manage' route needs to be added for managing the overlay.

### Week of April 8th TODO
- [ ] Get broadcast match order done [broadcast].
- [ ] Get broadcast event (and match) activating (and updating) done [broadcast].
  - Similar to teams and events, create a matches not broadcasted method in Model.
- [x] Think about re-formatting configs for the many formats. (Decided that things are ok as is)
- [x] Add winner table. (Decided against, but may reconsider. Can simply get winner of 'last match' per event.)
- [x] 'Delete' method hides the event (todo: public list will hide 'deleted' events)

### Week of April 15th TODO
- [ ] Get match creation done (partially done) [admin].
- [ ] Get list of scenes needed for The Feast World Cup from Kaze [broadcast]
