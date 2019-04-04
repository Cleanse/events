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
- Generators: '$this->create()' Event, then return Redirect::to('/event/:id') where teams can be added...
- Generators: '$this->schedule()'

###### Frontend? Controllers (October backend)
- !!! Eventual idea: Event/Overlay SAAS.
- [x] 'Generate' the event based on a Class by event-type.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?
- [ ] '/event/:id/manage' route needs to be added for managing the overlay.