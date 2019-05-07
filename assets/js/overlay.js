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
let matchApiUrl = `/api/broadcast/${broadcast_channel}/match`;
let groupApiUrl = `/api/broadcast/${broadcast_channel}/group`;
let bracketApiUrl = `/api/broadcast/${broadcast_channel}/bracket`;

//Score +
if (document.getElementById('overlay-score')) {

    function makeScoreUpdate(match) {
        if (!match) {
            return;
        }

        $('#one-wrapper').removeClass();
        $('#two-wrapper').removeClass();
        $('#one-wrapper').addClass(match.one.region ? match.one.region : '');
        $('#two-wrapper').addClass(match.two.region ? match.two.region : '');

        $('#one-logo').attr("src", match.one.logo ? match.one.logo.path : scoreClawsLogo);
        $('#two-logo').attr("src", match.two.logo ? match.two.logo.path : scoreFangsLogo);

        $('#one-name').text(match.one.name ? match.one.name : "Claws");
        $('#two-name').text(match.two.name ? match.two.name : "Fangs");

        $('#one-score').text(match.team_one_score ? match.team_one_score : 0);
        $('#two-score').text(match.team_two_score ? match.team_two_score : 0);
    }

    makeAjaxCall(matchApiUrl, makeScoreUpdate);
}

//Match +
if (document.getElementById('overlay-matchup')) {
    function makeMatchUpdate(match) {
        if (!match) {
            return;
        }

        if (!match.one || !match.two) {
            return;
        }

        let one_region = match.one.region ? match.one.region : 'default';
        let two_region = match.two.region ? match.two.region : 'default';
        $('#one-wrapper').removeClass();
        $('#two-wrapper').removeClass();
        $('#one-wrapper').addClass(one_region);
        $('#two-wrapper').addClass(two_region);

        let one_logo = match.one.logo ? match.one.logo.path : scoreClawsLogo;
        let two_logo = match.two.logo ? match.two.logo.path : scoreFangsLogo;
        $('#one-logo').attr("src", one_logo);
        $('#two-logo').attr("src", two_logo);

        $('#one-name').text(match.one.name ? match.one.name : 'Claws');
        $('#two-name').text(match.two.name ? match.two.name : 'Fangs');

        $('#one-description').text(match.one.description ? match.one.description : '');
        $('#two-description').text(match.two.description ? match.two.description : '');

        $('#one-score').text(match.team_one_score ? match.team_one_score : 0);
        $('#two-score').text(match.team_two_score ? match.team_two_score : 0);
    }

    makeAjaxCall(matchApiUrl, makeMatchUpdate);
}

//RR/Groups --
if (document.getElementById('overlay-event-rr')) {
    function makeGroupUpdate(group_array) {
        if (!group_array) {
            return;
        }

        $('#current-group > span').text(group_array.group_number ? group_array.group_number : '');

        const parent = document.getElementById('matches-wrapper');
        parent.innerHTML = '';

        for (let i = 0; i < group_array.event.matches.length; i++) {
            createMatchNode(parent, i, group_array.event.matches[i], group_array.broadcast.active_match);
        }

        //Need to use components ^ above as well
        for (let s = 0; s < group_array.standings.length; s++) {
            $('#standing-'+s).removeClass().addClass('standing active');
            $('#standing-'+s+' .name').text(group_array.standings[s].name);
            $('#standing-'+s+' .points').text(group_array.standings[s].pivot.points ? group_array.standings[s].pivot.points : 0);
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
            match_wrapper.classList.add("active");
        }

        if (match.winner_id > 0) {
            match_wrapper.classList.add("complete");
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

        opponent_two.className = 'opponent-2';
        opponent_two_score.className = 'score';
        opponent_two_name.className = 'name';
        opponent_two_logo.className = 'logo';
        opponent_two_img.src = match.two.logo ? match.two.logo.path : scoreFangsLogo;

        opponent_one_score.innerText = match.team_one_score ? match.team_one_score : 0;
        opponent_two_score.innerText = match.team_two_score ? match.team_two_score : 0;

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

        let node = `<div id="match-${n}" class="active">
                    <div class="opponent-1">
                        <div class="score">{{ m.team_one_score ?? 0 }}</div>
                        {% if event.matches.count > 6 %}
                        <div class="logo">
                            <img src="{{ m.one.getLogoThumb(44) ?? claws_logo }}">
                        </div>
                        {% else %}
                        <div class="name">{{ m.one.name }}</div>
                        {% endif %}
                    </div>

                    <div class="match-versus">vs</div>

                    <div class="opponent-2">
                        {% if event.matches.count > 6 %}
                        <div class="logo">
                            <img src="{{ m.two.getLogoThumb(44) ?? fangs_logo }}">
                        </div>
                        {% else %}
                        <div class="name">{{ m.two.name }}</div>
                        {% endif %}
                        <div class="score">{{ m.team_two_score ?? 0 }}</div>
                    </div>
                </div>`;

        parent.appendChild(match_wrapper);
    }

    makeAjaxCall(groupApiUrl, makeGroupUpdate);
}

//Brackets
if (document.getElementById('overlay-event-bracket')) {
    function makeBracketUpdate(event_array) {
        if (!event_array) {
            return;
        }

        for (let i = 0; i < event_array.event.matches.length; i++) {
            $('#match-'+ i +' > .opponent-1 .score').text(event_array.event.matches[i].team_one_score ? event_array.event.matches[i].team_one_score : 0);
            $('#match-'+ i +' > .opponent-2 .score').text(event_array.event.matches[i].team_two_score ? event_array.event.matches[i].team_two_score : 0);

            if (event_array.event.matches[i].one) {
                $('#match-'+ i +' > .opponent-1 .name')
                    .text(event_array.event.matches[i].one.name)
                    .removeClass('inactive');
            } else {
                $('#match-'+ i +' > .opponent-1 .score').text('-');
            }

            if (event_array.event.matches[i].two) {
                $('#match-'+ i +' > .opponent-2 .name')
                    .text(event_array.event.matches[i].two.name)
                    .removeClass('inactive');
            } else {
                $('#match-'+ i +' > .opponent-2 .score').text('-');
            }

            $('#match-'+ i).removeClass().addClass('match');
            if (event_array.event.matches[i].id === event_array.broadcast.active_match) {
                $('#match-'+ i).addClass('active');
            }
        }

        if (event_array.event.winner_id) {
            $('#best-of').text(event_array.event.winner.name+' wins!');

            for (let s = 0; s < 4; s++) {
                $('#seed-'+s).text(event_array.placement[s].name);
            }
        }
    }

    makeAjaxCall(bracketApiUrl, makeBracketUpdate);
}