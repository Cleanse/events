const types = {
    'round-robin': {
        'number_of_teams': {
            element: 'input',
            type: 'number',
            label: 'number_of_teams',
            placeholder: 'Number of teams.',
            description: '# of Teams'
        },
        'number_of_groups': {
            element: 'input',
            type: 'number',
            label: 'number_of_groups',
            placeholder: 'Number of groups required.',
            description: 'Groups / Divisions'
        },
        'cycles': {
            element: 'input',
            type: 'number',
            label: 'cycles',
            placeholder: 'Number of times teams face-off.',
            description: 'Times Participants Face-off (1-3)'
        },
        'randomize': {
            element: 'input',
            type: 'checkbox',
            label: 'cycles',
            placeholder: false,
            description: 'Randomize Groups?'
        }
    }
};
// 'single-elimination-bracket'
// 'double-elimination-bracket'

let eventType = document.getElementById('event-type');
let eventConfig = document.getElementById('event-config');

eventType.onchange = handleEventType;

function handleEventType() {
    //check if #event-config is empty, if not clear element. function clearEventConfig()

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