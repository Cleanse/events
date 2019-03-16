//todo: Put in config or ajax.
const types = {
    "round-robin": {
        "number_of_teams": {
            "type": "number",
            "label": "number_of_teams",
            "description": "# of Teams",
            "placeholder": "Number of teams.",
            "default": 2,
            "options": false
        },
        "number_of_groups": {
            "type": "number",
            "label": "number_of_groups",
            "description": "Groups / Divisions",
            "placeholder": "Number of groups required.",
            "default": 1,
            "options": false
        },
        "cycles": {
            "type": "number",
            "label": "cycles",
            "description": "Times Participants Face-off (1-3)",
            "placeholder": "Number of times teams face-off.",
            "default": 1,
            "options": false
        },
        "randomize": {
            "type": "select",
            "label": "randomize",
            "description": "Randomize Groups?",
            "placeholder": false,
            "default": 0,
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
        'number_of_teams': {
            type: 'number',
            label: 'number_of_teams',
            placeholder: 'Number of teams.',
            description: '# of Teams',
            options: false
        },
        'randomize': {
            type: 'checkbox',
            label: 'randomize',
            placeholder: false,
            description: 'Randomize Groups?',
            options: false
        }
    },
    'double-elimination-bracket': {
        'number_of_teams': {
            type: 'number',
            label: 'number_of_teams',
            placeholder: 'Number of teams.',
            description: '# of Teams',
            options: false
        },
        'randomize': {
            type: 'checkbox',
            label: 'randomize',
            placeholder: false,
            description: 'Randomize Groups?',
            options: false
        },
        'grand_finals': {
            type: 'select',
            label: 'grand_finals',
            placeholder: 'Number of teams.',
            description: '# of Teams',
            options: [0, 1, 2]
        }
    }
};

//Watch Select Field
let eventType = document.getElementById('event-type');
let eventConfig = document.getElementById('event-config');

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

function clearEventConfig() {
    eventConfig.innerHTML = '';
}

function Form(parent, options) {
    this.parent           = parent;
    this.inputType        = options.type;
    this.inputId          = options.label;
    this.inputDescription = options.description;
    this.inputPlaceholder = options.placeholder;
    this.inputDefault     = options.default ? options.default : false;
    this.inputOptions     = options.options ? options.options : false;

    this.createInput = function () {
        let inputWrapper = this.createBootstrapWrapper();
        let inputLabel = this.createBootstrapLabel();
        let inputContent = this.createInputContent();

        inputWrapper.appendChild(inputLabel);
        inputWrapper.appendChild(inputContent);
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
        textInput.value = this.inputDefault;

        return textInput;
    };

    this.createSelectInput = function() {
        let selectInput = document.createElement('select');
        selectInput.id = this.inputId;
        selectInput.name = this.inputId;
        selectInput.className = 'form-control';

        console.log(this.inputOptions);

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