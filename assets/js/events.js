//setup if page in [needs_this_set_of_methods] else [do_nothing];
const types = {
    "round-robin": {
        "number_of_groups": {
            "type": "number",
            "label": "number_of_groups",
            "description": "Groups / Divisions",
            "placeholder": "Number of groups required.",
            "default": "1 (One group/division).",
            "options": false
        },
        "cycles": {
            "type": "select",
            "label": "cycles",
            "description": "Times Participants Face-off (1-3)",
            "placeholder": false,
            "default": "Once.",
            "options": [
                {
                    "title": "Once",
                    "value": "1"
                },
                {
                    "title": "Twice",
                    "value": "2"
                },
                {
                    "title": "Three Times",
                    "value": "3"
                }
            ]
        },
        "randomize": {
            "type": "select",
            "label": "randomize",
            "description": "Randomize Groups?",
            "placeholder": false,
            "default": "No.",
            "options": [
                {
                    "title": "No",
                    "value": "0"
                },
                {
                    "title": "Yes",
                    "value": "1"
                }
            ]
        }
    },
    'single-elimination-bracket': {
        "randomize": {
            "type": "select",
            "label": "randomize",
            "description": "Randomize Groups?",
            "placeholder": false,
            "default": "No.",
            "options": [
                {
                    "title": "No",
                    "value": "0"
                },
                {
                    "title": "Yes",
                    "value": "1"
                }
            ]
        }
    },
    'double-elimination-bracket': {
        "randomize": {
            "type": "select",
            "label": "randomize",
            "description": "Randomize Groups?",
            "placeholder": false,
            "default": "No.",
            "options": [
                {
                    "title": "No",
                    "value": "0"
                },
                {
                    "title": "Yes",
                    "value": "1"
                }
            ]
        },
        "grand_finals": {
            "type": "select",
            "label": "grand_finals",
            "description": "Grand Finals Options",
            "placeholder": false,
            "default": "1-2 matches*.",
            "options": [
                {
                    "title": "1-2 matches — winners bracket finalist has to be defeated twice.",
                    "value": "1"
                },
                {
                    "title": "One Match",
                    "value": "2"
                },
                {
                    "title": "None",
                    "value": "3"
                }
            ]
        }
    },
    "swiss": {
        "points_per_victory": {
            "type": "number",
            "label": "points_per_victory",
            "description": "Points Per Match Win",
            "placeholder": "1.0",
            "default": false,
            "options": false
        },
        "points_per_match_tie": {
            "type": "number",
            "label": "points_per_match_tie",
            "description": "Points Per Match Tie",
            "placeholder": "0.5",
            "default": false,
            "options": false
        },
        "points_per_win": {
            "type": "number",
            "label": "points_per_win",
            "description": "Points Per Game Win",
            "placeholder": "0.0",
            "default": false,
            "options": false
        },
        "points_per_tie": {
            "type": "number",
            "label": "points_per_tie",
            "description": "Points Per Game Tie",
            "placeholder": "0.0",
            "default": false,
            "options": false
        },
        "points_per_bye": {
            "type": "number",
            "label": "points_per_bye",
            "description": "Points Per Round Bye",
            "placeholder": "1.0",
            "default": false,
            "options": false
        }
    },
};

//Watch Select Field
let eventType = document.getElementById('event-type');
let eventConfig = document.getElementById('event-config');

//On Select Change, populate form with necessary fields.
function handleEventType() {
    if (eventType.options[eventType.selectedIndex].value !== '') {
        //check if #event-config is empty, if not clear element.
        clearEventConfig();

        //create header
        let selectedTitle = eventType.options[eventType.selectedIndex].text;
        let formGroupTitle = document.createElement('h3');
        formGroupTitle.innerText = selectedTitle;

        eventConfig.appendChild(formGroupTitle);

        //Load config options
        let selectedOp = eventType.options[eventType.selectedIndex].value;
        let option = types[selectedOp];

        //Create Config Elements
        for (let key in option) {
            if (option.hasOwnProperty(key)) {
                let eventConfigInput = new Form(eventConfig, option[key]);
                eventConfigInput.createInput();
            }
        }
    } else {
        clearEventConfig();
    }
}

//Clear config form before re-populating form with necessary fields.
function clearEventConfig() {
    eventConfig.innerHTML = '';
}

//Form creation class
function Form(parent, options) {
    this.parent           = parent;
    this.inputType        = options.type;
    this.inputId          = `event_config[${options.label}]`;
    this.inputDescription = options.description;
    this.inputPlaceholder = options.placeholder;
    this.inputDefault     = options.default ? options.default : false;
    this.inputOptions     = options.options ? options.options : false;

    this.createInput = function () {
        let inputWrapper = this.createBootstrapWrapper();
        let inputLabel = this.createBootstrapLabel();
        let inputContent = this.createInputContent();
        let inputFeedback = this.createInputFeedback();

        inputWrapper.appendChild(inputLabel);
        inputWrapper.appendChild(inputContent);
        inputWrapper.appendChild(inputFeedback);
        this.parent.appendChild(inputWrapper);
    };

    this.createBootstrapWrapper = function () {
        let formGroup = document.createElement('div');
        formGroup.className = 'form-group';

        return formGroup;
    };

    this.createBootstrapLabel = function () {
        let formLabel = document.createElement('label');
        formLabel.setAttribute("for", this.inputId);
        formLabel.innerHTML = this.inputDescription;

        return formLabel;
    };

    this.createInputFeedback = function () {
        let formFeedback = document.createElement('div');
        formFeedback.innerHTML = `Default: ${this.inputDefault}`;
        formFeedback.className = 'form-text text-muted';

        return formFeedback;
    };

    this.createInputContent = function () {
        //switch statement for diff input types
        switch (this.inputType) {
            case 'select':
                return this.createSelectInput();
                break;
            case 'number':
            case 'text':
            default:
                return this.createTextInput();
        }
    };

    this.createTextInput = function() {
        let textInput = document.createElement('input');
        textInput.type = this.inputType;
        textInput.id = this.inputId;
        textInput.name = this.inputId;
        textInput.className = 'form-control';
        textInput.placeholder = this.inputPlaceholder;

        return textInput;
    };

    this.createSelectInput = function() {
        let selectInput = document.createElement('select');
        selectInput.id = this.inputId;
        selectInput.name = this.inputId;
        selectInput.className = 'form-control';

        for (let i = 0; i < this.inputOptions.length; i++) {
            let option = document.createElement("option");
            option.value = this.inputOptions[i].value;
            option.text = this.inputOptions[i].title;

            selectInput.appendChild(option);
        }

        return selectInput;
    };
}

//Listen for select form change.
eventType.onchange = handleEventType;

//Hide and show error log
$(window).on('ajaxInvalidField', function(event, fieldElement, fieldName, errorMsg, isFirst) {
    $('.alert-danger').removeClass('d-none').addClass('visible');
});

/**
 * Event Edit
 */
if (document.getElementById('nav-teams')) {
    window.addEventListener('load', function() {
        handleEventType();
    });

    let setDefaults = function() {

        if (configuration) {
            for (const [key, value] of Object.entries(configuration)) {
                let formElementExists = document.getElementById(`event_config[${key}]`);
                formElementExists.value = value;
            }
        }
    };

    window.onload = setTimeout(setDefaults, 3000);
}