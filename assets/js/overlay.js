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

//Score
if (document.getElementById('overlay-score')) {

    function makeScoreUpdate(data) {
        if (!data) {
            return;
        }

        $('#one-wrapper').addClass(data.one.region);
        $('#two-wrapper').addClass(data.two.region);

        $('#one-logo').attr("src", data.one.logo.path ? data.one.logo.path : scoreClawsLogo);
        $('#two-logo').attr("src", data.two.logo.path ? data.two.logo.path : scoreFangsLogo);

        $('#one-name').text(data.one.name ? data.one.name : "Claws");
        $('#two-name').text(data.two.name ? data.two.name : "Fangs");

        $('#one-score').text(data.team_one_score ? data.team_one_score : 0);
        $('#two-score').text(data.team_two_score ? data.team_two_score : 0);
    }

    makeAjaxCall(matchApiUrl, makeScoreUpdate);
}

//Matchup
if (document.getElementById('overlay-matchup')) {
    function makeMatchUpdate(data) {
        if (!data) {
            return;
        }

        if (!data.one || !data.two) {
            return;
        }

        let one_region = data.one.region ? data.one.region : 'na';
        let two_region = data.two.region ? data.two.region : 'na';
        $('#one-wrapper').addClass(one_region);
        $('#two-wrapper').addClass(two_region);

        let one_logo = data.one ? data.one.logo.path : scoreClawsLogo;
        let two_logo = data.two ? data.two.logo.path : scoreFangsLogo;
        $('#one-logo').attr("src", one_logo);
        $('#two-logo').attr("src", two_logo);

        $('#one-name').text(data.one.name ? data.one.name : "Claws");
        $('#two-name').text(data.two.name ? data.two.name : "Fangs");

        $('#one-score').text(data.team_one_score ? data.team_one_score : 0);
        $('#two-score').text(data.team_two_score ? data.team_two_score : 0);
    }

    makeAjaxCall(matchApiUrl, makeMatchUpdate);
}

//Bracket/Groups
if (document.getElementById('overlay-event')) {
    //# (will need logic check for types
}
