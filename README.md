# Events Plugin (WIP)
A round-robin and bracket events plugin for OctoberCMS.

### Round Robin Group Stage
- [x] The teams to groups ratio will be an option on creation.
- [x] Teams can be either **manually or randomly** placed into groups.
- [x] Generate *Best-of-**X*** matches that match all teams in your respective group.

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

###### Frontend? Controllers (October backend)
- !!! Eventual idea: Event/Overlay SAAS.
- [x] 'Generate' the event based on a Class by event-type.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?
- [ ] '/event/:id/manage' route needs to be added for managing the overlay.

### Week of April 8th TODO
- [ ] Get match creation done [admin].
- [ ] Get broadcast match order done [broadcast].
- [ ] Get broadcast event (and match) activating (and updating) done [broadcast].
- [ ] Get list of scenes needed for The Feast World Cup from Kaze [broadcast].
- [ ] Think about re-formatting configs for the many formats.