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
    'bracket-single': {
        "randomize": {
            "type": "select",
            "label": "randomize",
            "description": "Randomize Groups?",
            "placeholder": false,
            "default": "No",
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
        "third_place": {
            "type": "select",
            "label": "third_place_match",
            "description": "Third Place Match?",
            "placeholder": false,
            "default": "Yes. Useful when you need in-depth seeding.",
            "options": [
                {
                    "title": "Yes",
                    "value": "1"
                },
                {
                    "title": "No",
                    "value": "0"
                }
            ]
        }
    },
    'bracket-double': {
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
        },
        "third_place": {
            "type": "select",
            "label": "third_place_match",
            "description": "Third Place Match?",
            "placeholder": false,
            "default": "Yes. Useful when you need in-depth seeding.",
            "options": [
                {
                    "title": "Yes",
                    "value": "1"
                },
                {
                    "title": "No",
                    "value": "0"
                }
            ]
        }
    }
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
        let inputWrapper  = this.createBootstrapWrapper();
        let inputLabel    = this.createBootstrapLabel();
        let inputContent  = this.createInputContent();
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

    this.createTextInput = function () {
        let textInput = document.createElement('input');
        textInput.type        = this.inputType;
        textInput.id          = this.inputId;
        textInput.name        = this.inputId;
        textInput.className   = 'form-control';
        textInput.placeholder = this.inputPlaceholder;

        return textInput;
    };

    this.createSelectInput = function () {
        let selectInput = document.createElement('select');
        selectInput.id        = this.inputId;
        selectInput.name      = this.inputId;
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

if (document.getElementById('event-type') || document.getElementById('nav-teams')) {
    //Listen for select form change.
    eventType.onchange = handleEventType;
}

//Hide and show error log
$(window).on('ajaxInvalidField', function (event, fieldElement, fieldName, errorMsg, isFirst) {
    $('.alert-danger').removeClass('d-none').addClass('visible');
});

/**
 * Event Edit
 */
if (document.getElementById('nav-teams')) {
    let setDefaults = function () {
        if (configuration) {
            for (const [key, value] of Object.entries(configuration)) {
                let formElementExists = document.getElementById(`event_config[${key}]`);
                formElementExists.value = value;
            }
        }
    };

    window.addEventListener('load', function () {
        handleEventType();
        setTimeout(setDefaults, 2000);
    });

    //Start Remember Which Tab
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });

    let activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        $('#nav-tab a[href="' + activeTab + '"]').tab('show');
    }
    //End Remember Which Tab

    let teamsTab = document.getElementById('more-teams');
    if (teamsTab) {
        teamsTab.onclick = inputChange;

        function inputChange() {
            $('#nav-tab a[href="#nav-teams"]').tab('show');
        }
    }
}

//Teams
/**
 * Event Edit
 */
if (document.getElementById('manage-event-teams')) {
    $(document).on('click', ".image-preview-clear", function () {
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("Browse");
    });

    $(document).on('change', "#image-file:file", function () {
        let file = this.files[0];
        $(".image-preview-input-title").text("Re-Select");
        $(".image-preview-clear").show();
        $(".image-preview-filename").val(file.name);
    });
}

let placementPreview = document.getElementById('placement-preview');
if (placementPreview) {
    function placementUp(row) {
        let prevRow = row.previousSibling;
        if (prevRow) {
            row.parentNode.insertBefore(row, prevRow);
        }
    }

    function placementDown(row) {
        let nextRow = row.nextSibling;
        if (nextRow) {
            row.parentNode.insertBefore(row, nextRow.nextSibling);
        }
    }
}