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

//Score
if (document.getElementById('overlay-score')) {
    let scoreApiUrl = `/api/broadcast/${broadcast_channel}/score`;

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

    makeAjaxCall(scoreApiUrl, makeScoreUpdate);
}

//Matchup
if (document.getElementById('overlay-matchup')) {
    //do stuff
}

//Bracket/Groups
if (document.getElementById('overlay-event')) {
    //# (will need logic check for types
}
