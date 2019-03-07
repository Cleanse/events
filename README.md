# Events Plugin (WIP)
A round-robin and bracket events plugin for OctoberCMS.

### Round Robin Group Stage
- [ ] The teams to groups ratio will be an option on creation.
- [ ] Teams can be either **manually or randomly** placed into groups.
- [x] Generate *Best-of-**X*** matches that match all teams in your respective group.
- [Yes/No] For the overlay, should advancing to the next match be manually controlled (due to scoreboard)?

### Tournament
- [ ] Generate *Best-of-**X*** single-elimination bracket.
- [ ] Generate *Best-of-**X*** double-elimination bracket.
- [ ] Option to grab the top **X** teams from a *Round Robin Group Stage* event.

###### Brainstorming
- !!! Take the *Config*, insert it into the 'event_type' factory, and do magic.
- Use Challonge API (?) and bridge off of it for special cases (ex. adding a player roster and linking it to the 
  event_id).
- !!! To learn more Javascript, write the app with js/html/scss for frontend tasks and use PHP as an api backend.

###### Frontend? Controllers (October backend)
- CRUD (Create, Read, Update, Delete) Forms.