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
scoreClawsLogo = '/plugins/cleanse/event/assets/images/score-claws.png';
scoreFangsLogo = '/plugins/cleanse/event/assets/images/score-fangs.png';
let matchApiUrl = `/api/broadcast/${broadcast_channel}/match`;
let eventApiUrl = `/api/broadcast/${broadcast_channel}/event`;

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

//Brackets
if (document.getElementById('overlay-event-bracket')) {
    function makeBracketUpdate(event) {
        if (!event) {
            return;
        }

        for (i = 0; i <= event.matches.count; i++) {
            console.log(i);
        }

        $('#one-wrapper').addClass(event.one.region);
        $('#two-wrapper').addClass(event.two.region);

        $('#one-logo').attr("src", event.one.logo.path ? event.one.logo.path : scoreClawsLogo);
        $('#two-logo').attr("src", event.two.logo.path ? event.two.logo.path : scoreFangsLogo);

        $('#match-1-name').text(event.one.name ? event.one.name : "Claws");
        $('#two-name').text(event.two.name ? event.two.name : "Fangs");

        $('#one-score').text(event.team_one_score ? event.team_one_score : 0);
        $('#two-score').text(event.team_two_score ? event.team_two_score : 0);
    }

    makeAjaxCall(eventApiUrl, makeBracketUpdate);
}

//RR/Groups
if (document.getElementById('overlay-event-rr')) {
    function makeGroupUpdate(event) {
        if (!event) {
            return;
        }

        $('#one-wrapper').addClass(event.one.region);
        $('#two-wrapper').addClass(event.two.region);

        $('#one-logo').attr("src", event.one.logo.path ? event.one.logo.path : scoreClawsLogo);
        $('#two-logo').attr("src", event.two.logo.path ? event.two.logo.path : scoreFangsLogo);

        $('#one-name').text(event.one.name ? event.one.name : "Claws");
        $('#two-name').text(event.two.name ? event.two.name : "Fangs");

        $('#one-score').text(event.team_one_score ? event.team_one_score : 0);
        $('#two-score').text(event.team_two_score ? event.team_two_score : 0);
    }

    makeAjaxCall(eventApiUrl, makeGroupUpdate);
}
