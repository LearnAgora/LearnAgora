var app = angular.module('card', []);

app.controller('CardController', function($http) {
    this.id = 0;
    this.type = null;
    this.name = "";
    this.description = "";
    this.question = "";
    this.editTitle = true;
    this.action = null;
    this.init = function(type,id,name,description) {
        this.type = type;
        this.id = id;
        this.name = name;
        this.description = description;
        this.action = new Action(13,'somename','actiondescription');
    }
    this.setId = function(id) {
        this.id = id;
    }
    this.outcomes = [
        {text: "option 1",tags: [
            {name: "tag1", values: {motivational:0, progress:0, badge:0}},
            {name: "tag2", values: {motivational:0, progress:0, badge:0}},
        ]},
        {text: "option 2",tags: [
            {name: "tag3", values: {motivational:0, progress:0, badge:0}},
        ]},
        {text: "option 3",tags: [
        ]},
    ];
    this.addOutcome = function() {
        this.outcomes.push({text: "",tags: []});
    };
    this.getName = function() {
        return (this.name === '') ? 'Click to edit' : this.name;
    }
    this.save = function() {
        this.editTitle=false;
        var self = this;
        var request = $http({
            method: "post",
            url: "/app_dev.php/particle/save",
            data: {id: this.id, name: this.name, description: this.description, type: this.type},
            context: self
        });
        request.success(function(result){
            self.setId(result.particleId);
            $('#debug').html('particle saved (id='+result.particleId+')<br />');
        });
    }
});

app.controller('PanelController', function() {
    this.tab = 1;
    this.selectTab = function(setTab) {
        this.tab = setTab;
    };
    this.isSelected = function(checkTab) {
        return this.tab === checkTab;
    };
});

function Action(id,name,description) {
    this.type = 'Action';
    this.id = id;
    this.name = name;
    this.description = description;
    this.save = function() {
        var self = this;
        var request = $http({
            method: "post",
            url: "/app_dev.php/particle/save",
            data: {id: this.id, name: this.name, description: this.description, type: this.type},
            context: self
        });
        request.success(function(result){
            self.setId(result.particleId);
            $('#debug').html('Action saved (id='+result.particleId+')<br />');
        });
    }
}