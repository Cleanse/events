//Ajax
function makeAjaxCall(apiUrl, userFunc) {
    $.ajax({
        type: "get",
        url: apiUrl,
        success: function (data) {
            userFunc(data);

            setTimeout(function () {
                makeAjaxCall(apiUrl, userFunc);
            }, 10000);
        }
    });
}

//Global
let scoreClawsLogo = '/plugins/cleanse/event/assets/images/score-claws.png';
let scoreFangsLogo = '/plugins/cleanse/event/assets/images/score-fangs.png';
let defaultTeamLogo = '/plugins/cleanse/event/assets/images/default.jpg';
let matchApiUrl = `/api/broadcast/${broadcast_channel}/match`;
let bracketApiUrl = `/api/broadcast/${broadcast_channel}/bracket`;

//Score
if (document.getElementById('overlay-score')) {
    function makeScoreUpdate(match) {
        if (!match) {
            return;
        }

        $('#one-logo').attr('src', match.one.logo ? match.one.logo.path : scoreClawsLogo);
        $('#two-logo').attr('src', match.two.logo ? match.two.logo.path : scoreFangsLogo);

        $('#one-name').text(match.one.name ? match.one.name : 'Claws');
        $('#two-name').text(match.two.name ? match.two.name : 'Fangs');

        $('#one-score').text(match.team_one_score ? match.team_one_score : 0);
        $('#two-score').text(match.team_two_score ? match.team_two_score : 0);

        $('#one-region').text(match.one.region ? match.one.region : '??');
        $('#two-region').text(match.two.region ? match.two.region : '??');
    }

    makeAjaxCall(matchApiUrl, makeScoreUpdate);
}

//Names +
if (document.getElementById('overlay-team-name')) {
    function makeNameUpdate(match) {
        if (!match) {
            return;
        }

        if (names_team === 'claws') {
            $('#overlay-team-name.claws .team-name').text(match.one.name ? match.one.name : 'Claws');
            $('#overlay-team-name.claws .team-logo img').attr('src', match.one.logo ? match.one.logo.path : scoreClawsLogo);
        }

        if (names_team === 'fangs') {
            console.log('hi fangs');
            $('#overlay-team-name.fangs .team-name').text(match.two.name ? match.two.name : 'Fangs');
            $('#overlay-team-name.fangs .team-logo img').attr('src', match.two.logo ? match.two.logo.path : scoreFangsLogo);
        }
    }

    makeAjaxCall(matchApiUrl, makeNameUpdate);
}

//Match +
if (document.getElementById('overlay-matchup')) {
    function makeMatchUpdate(event) {
        if (!event) {
            return;
        }

        if (!event.match.one || !event.match.two) {
            return;
        }

        let one_wrapper = $('#one-wrapper');
        let two_wrapper = $('#two-wrapper');

        let one_region = event.match.one.region ? event.match.one.region : 'default';
        let two_region = event.match.two.region ? event.match.two.region : 'default';
        one_wrapper.removeClass();
        two_wrapper.removeClass();
        one_wrapper.addClass(one_region);
        two_wrapper.addClass(two_region);

        let one_logo = event.match.one.logo ? event.match.one.logo.path : scoreClawsLogo;
        let two_logo = event.match.two.logo ? event.match.two.logo.path : scoreFangsLogo;
        $('#one-logo').attr("src", one_logo);
        $('#two-logo').attr("src", two_logo);

        $('#one-name').text(event.match.one.name ? event.match.one.name : 'Claws');
        $('#two-name').text(event.match.two.name ? event.match.two.name : 'Fangs');

        $('#one-description').text(event.match.one.description ? event.match.one.description : '');
        $('#two-description').text(event.match.two.description ? event.match.two.description : '');

        $('#one-score').text(event.match.team_one_score ? event.match.team_one_score : 0);
        $('#two-score').text(event.match.team_two_score ? event.match.team_two_score : 0);

        if (event.match.event.config['best_of']) {
            $('#best-of').text(event.match.event.config['best_of']);
        }

        if (event.match.takes_place_during) {
            $('#event-round').text('Group '+event.match.takes_place_during);
        }

        if (event.match.order) {
            $('#event-round').text(event.round);
        }
    }

    makeAjaxCall(matchApiUrl, makeMatchUpdate);
}

//Event Round Robin +
if (document.getElementById('overlay-event-groups')) {
    let groupsApiUrl = `/api/broadcast/${broadcast_channel}/groups`;

    function makeGroupsUpdate(groups_array) {
        if (!groups_array) {
            return;
        }

        const groupsParent = document.getElementById('group-wrapper');

        for (let g = 0; g < groups_array.groups.length; g++) {
            let groupNode = groupsParent.children[g];
            groupNode.children[1].innerHTML = ''; //empty the 'team-container'

            for (let s = 0; s < groups_array.groups[g].length; s++) {
                createGroupsStandingNode(groupNode.children[1], s, groups_array.groups[g][s]);
            }
        }
    }

    function createGroupsStandingNode(parent, s, team) {
        let standing_wrapper       = document.createElement('div');
        let standing_team_logo     = document.createElement('div');
        let standing_team_name     = document.createElement('div');
        let standing_team_points   = document.createElement('div');
        let standing_team_logo_img = document.createElement('img');

        standing_wrapper.id = 'team-'+s;

        standing_wrapper.classList.add('team');
        if (team.region) {
            standing_wrapper.classList.add(team.region);
        }

        standing_team_points.className = 'points';
        standing_team_name.className = 'name';
        standing_team_logo.className = 'logo';
        standing_team_logo_img.src = team.logo ? team.logo.path : defaultTeamLogo;

        standing_team_name.innerText = team.name;
        standing_team_points.innerText = team.pivot.points ? team.pivot.points : 0;

        standing_team_logo.appendChild(standing_team_logo_img);

        standing_wrapper.appendChild(standing_team_points);
        standing_wrapper.appendChild(standing_team_logo);
        standing_wrapper.appendChild(standing_team_name);

        parent.appendChild(standing_wrapper);
    }

    makeAjaxCall(groupsApiUrl, makeGroupsUpdate);
}

//Groups
if (document.getElementById('overlay-event-rr')) {
    let groupApiUrl = `/api/broadcast/${broadcast_channel}/group/${group_id}`;

    function makeGroupUpdate(group_array) {
        if (!group_array) {
            return;
        }

        $('#current-group > span').text(group_array.group_number ? group_array.group_number : '');

        const matchesParent = document.getElementById('matches-wrapper');
        const standingsParent = document.getElementById('standings-wrapper');
        matchesParent.innerHTML = '';
        standingsParent.innerHTML = '';

        for (let i = 0; i < group_array.event.matches.length; i++) {
            createMatchNode(matchesParent, i, group_array.event.matches[i], group_array.broadcast.active_match);
        }

        for (let s = 0; s < group_array.standings.length; s++) {
            createStandingNode(standingsParent, s, group_array.standings[s]);

            if (group_array.standings[s].pivot.points >= 3 && s === 2) {
                threeWayTie(standingsParent);
            }
        }
    }

    function createMatchNode(parent, n, match, active_match) {
        let match_wrapper = document.createElement('div');
        let match_versus = document.createElement('div');

        let opponent_one = document.createElement('div');
        let opponent_one_score = document.createElement('div');
        let opponent_one_name = document.createElement('div');
        let opponent_one_logo = document.createElement('div');
        let opponent_one_img = document.createElement('img');

        let opponent_two = document.createElement('div');
        let opponent_two_score = document.createElement('div');
        let opponent_two_name = document.createElement('div');
        let opponent_two_logo = document.createElement('div');
        let opponent_two_img = document.createElement('img');

        match_wrapper.id = 'match-'+n;
        if (match.id === active_match) {
            match_wrapper.classList.add('active');
        }

        if (match.winner_id > 0) {
            match_wrapper.classList.add('complete');
        }

        match_versus.className = 'match-versus';
        match_versus.innerText = 'vs';

        if (match.winner_id === match.one.id) {
            opponent_one.className = 'opponent-1 winner';
        } else {
            opponent_one.className = 'opponent-1';
        }
        opponent_one_score.className = 'score';
        opponent_one_name.className = 'name';
        opponent_one_logo.className = 'logo';
        opponent_one_img.src = match.one.logo ? match.one.logo.path : scoreClawsLogo;

        if (match.winner_id === match.two.id) {
            opponent_two.className = 'opponent-2 winner';
        } else {
            opponent_two.className = 'opponent-2';
        }
        opponent_two_score.className = 'score';
        opponent_two_name.className = 'name';
        opponent_two_logo.className = 'logo';
        opponent_two_img.src = match.two.logo ? match.two.logo.path : scoreFangsLogo;

        opponent_one_score.innerText = match.team_one_score ? match.team_one_score : 0;
        opponent_two_score.innerText = match.team_two_score ? match.team_two_score : 0;

        opponent_one_name.innerText = match.one ? match.one.name : '-';
        opponent_two_name.innerText = match.two ? match.two.name : '-';

        opponent_one_logo.appendChild(opponent_one_img);
        opponent_two_logo.appendChild(opponent_two_img);

        opponent_one.appendChild(opponent_one_score);
        opponent_one.appendChild(opponent_one_logo);
        opponent_one.appendChild(opponent_one_name);

        opponent_two.appendChild(opponent_two_name);
        opponent_two.appendChild(opponent_two_logo);
        opponent_two.appendChild(opponent_two_score);

        match_wrapper.appendChild(opponent_one);
        match_wrapper.appendChild(match_versus);
        match_wrapper.appendChild(opponent_two);

        parent.appendChild(match_wrapper);
    }

    function createStandingNode(parent, s, team) {
        let standing_wrapper       = document.createElement('div');
        let standing_team_logo     = document.createElement('div');
        let standing_team_name     = document.createElement('div');
        let standing_team_points   = document.createElement('div');
        let standing_team_logo_img = document.createElement('img');

        standing_wrapper.id = 'standing-'+s;
        if (team.pivot.points >= 3 && s <= 2) {
            standing_wrapper.classList.add('qualified');
        }

        standing_wrapper.classList.add('standing');
        if (team.region) {
            standing_wrapper.classList.add(team.region);
        }

        standing_team_points.className = 'points';
        standing_team_name.className = 'name';
        standing_team_logo.className = 'logo';

        standing_team_logo_img.src = team.logo ? team.logo.path : defaultTeamLogo;

        standing_team_name.innerText = team.name;
        standing_team_points.innerText = team.pivot.points ? team.pivot.points : 0;

        standing_team_logo.appendChild(standing_team_logo_img);

        standing_wrapper.appendChild(standing_team_logo);
        standing_wrapper.appendChild(standing_team_name);
        standing_wrapper.appendChild(standing_team_points);

        parent.appendChild(standing_wrapper);
    }

    function threeWayTie(standingsParent) {
        standingsParent.children[1].classList.remove('qualified');
        standingsParent.children[2].classList.remove('qualified');

        standingsParent.children[1].classList.add('tie');
        standingsParent.children[2].classList.add('tie');
    }

    makeAjaxCall(groupApiUrl, makeGroupUpdate);
}

//Brackets
if (document.getElementById('overlay-event-bracket')) {
    function makeBracketUpdate(event_array) {
        if (!event_array) {
            return;
        }

        const seedingParent = document.getElementById('final-standings');
        seedingParent.innerHTML = '';

        for (let i = 0; i < event_array.event.matches.length; i++) {
            if (event_array.event.matches[i].one) {
                $('#match-'+ i +' > .opponent-1 .name')
                    .text(event_array.event.matches[i].one.name)
                    .removeClass('inactive');

                if (event_array.event.matches[i].winner_id !== null) {
                    if (event_array.event.matches[i].winner_id === event_array.event.matches[i].team_two) {
                        $('#match-'+ i +' > .opponent-1 .name')
                            .addClass('inactive');
                    }
                }

                $('#match-'+ i +' > .opponent-1 .score')
                    .text(event_array.event.matches[i].team_one_score ? event_array.event.matches[i].team_one_score : 0);
            } else {
                $('#match-'+ i +' > .opponent-1 .score').text('-');
            }

            if (event_array.event.matches[i].two) {
                $('#match-'+ i +' > .opponent-2 .name')
                    .text(event_array.event.matches[i].two.name)
                    .removeClass('inactive');

                if (event_array.event.matches[i].winner_id !== null) {
                    if (event_array.event.matches[i].winner_id === event_array.event.matches[i].team_one) {
                        $('#match-'+ i +' > .opponent-2 .name')
                            .addClass('inactive');
                    }
                }

                $('#match-'+ i +' > .opponent-2 .score')
                    .text(event_array.event.matches[i].team_two_score ? event_array.event.matches[i].team_two_score : 0);
            } else {
                $('#match-'+ i +' > .opponent-2 .score').text('-');
            }

            $('#match-'+ i).removeClass().addClass('match');
            if (event_array.event.matches[i].id === event_array.broadcast.active_match) {
                $('#match-'+ i).addClass('active');
            }
        }

        if (event_array.event.winner_id) {
            for (let s = 0; s < 3; s++) {
                createSeedRows(s, event_array.placement[s].name, seedingParent);
            }
        }
    }

    function createSeedRows(num, team, parent) {
        let seed_wrapper = document.createElement('div');

        seed_wrapper.id = 'seed-'+num;
        seed_wrapper.classList.add('seed');
        seed_wrapper.innerText = team;

        parent.appendChild(seed_wrapper);
    }

    makeAjaxCall(bracketApiUrl, makeBracketUpdate);
}

if (document.getElementById('overlay-prize-pool-wrapper')) {
    let prizeApiUrl = `/api/broadcast/${broadcast_channel}/prize`;

    function makePrizeUpdate(prize) {
        if (!prize) {
            return;
        }

        document.getElementById('prize-pool').setAttribute('data-prize', prize.value);
        $('#prize-pool').text(prize.value);
    }

    makeAjaxCall(prizeApiUrl, makePrizeUpdate);
}

if (document.getElementById('overlay-information')) {
    let infoApiUrl = `/api/broadcast/${broadcast_channel}/info`;

    function makeInfoUpdate(info) {
        if (!info) {
            return;
        }

        $('#overlay-information').text(info);
    }

    makeAjaxCall(infoApiUrl, makeInfoUpdate);
}