function EventTypeConfig(options) {
    this.typeProperty = '';

    this.handleEventType = function() {
        this.clearEventConfig();
    };

    this.clearEventConfig = function() {
        this.typeProperty = 'meh';
    };
}
