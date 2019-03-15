//todo: Put in config or ajax.
const types = {
    "round-robin": {
        "number_of_teams": {
            "element": "input",
            "type": "number",
            "label": "number_of_teams",
            "placeholder": "Number of teams.",
            "description": "# of Teams",
            "default": 2
        },
        "number_of_groups": {
            "element": "input",
            "type": "number",
            "label": "number_of_groups",
            "placeholder": "Number of groups required.",
            "description": "Groups / Divisions",
            "default": 1
        },
        "cycles": {
            "element": "input",
            "type": "number",
            "label": "cycles",
            "placeholder": "Number of times teams face-off.",
            "description": "Times Participants Face-off (1-3)",
            "default": 1
        },
        "randomize": {
            "element": "input",
            "type": "checkbox",
            "label": "randomize",
            "placeholder": false,
            "description": "Randomize Groups?",
            options: false
        }
    },
    'single-elimination-bracket': {
        'number_of_teams': {
            element: 'input',
            type: 'number',
            label: 'number_of_teams',
            placeholder: 'Number of teams.',
            description: '# of Teams',
            options: false
        },
        'randomize': {
            element: 'input',
            type: 'checkbox',
            label: 'randomize',
            placeholder: false,
            description: 'Randomize Groups?',
            options: false
        }
    },
    'double-elimination-bracket': {
        'number_of_teams': {
            element: 'input',
            type: 'number',
            label: 'number_of_teams',
            placeholder: 'Number of teams.',
            description: '# of Teams',
            options: false
        },
        'randomize': {
            element: 'input',
            type: 'checkbox',
            label: 'randomize',
            placeholder: false,
            description: 'Randomize Groups?',
            options: false
        },
        'grand_finals': {
            element: 'input',
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
                createConfigElement(eventConfig, key, option[key]);
            }
        }
    } else {
        clearEventConfig();
    }
}

function clearEventConfig() {
    eventConfig.innerHTML = '';
}

function createConfigElement(parent, option, optionSettings) {
    let formGroup = document.createElement('div');
    formGroup.className = 'form-group';

    let formLabel = document.createElement('label');
    formLabel.setAttribute("for", option);
    formLabel.innerHTML = optionSettings.description;
    formGroup.appendChild(formLabel);

    let formInput = createTextInput(optionSettings);
    formGroup.appendChild(formInput);

    parent.appendChild(formGroup);
}

function createTextInput(ops) {
    let textInput = document.createElement(ops.element);
    textInput.type = ops.type;
    textInput.id = ops.label;
    textInput.name = ops.label;
    textInput.className = 'form-control';

    return textInput;
}

//Listen for select form change.
eventType.onchange = handleEventType;