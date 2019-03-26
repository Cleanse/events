<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Flash;
use Redirect;
use Session;
use Validator;
use ValidationException;

use Cleanse\Event\Models\Team;

class Teams extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Manage Event Teams',
            'description' => 'Displays a list of event teams to manage by the admin.'
        ];
    }

    public function onRun()
    {
        $this->page['teams'] = $this->getTeams();
    }

    public function getTeams()
    {
        return Team::orderBy('name', 'asc')
        ->get();
    }

    public function onLoadCreation(){}

    public function onCreateTeam()
    {
        $data = post();

        $rules = [
            'name' => 'required',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $newTeam = new Team;
        $newTeam->name = post('name');
        $newTeam->description = post('description');

        $newTeam->save();

        Flash::success($newTeam->name);
        Session::flash('flashSuccess', true);

        return Redirect::to('/events/teams/manage');
    }

    public function onRequestTeam()
    {
        $this->page['team'] = Team::find(post('id'));
    }

    public function onUpdateTeam()
    {
        $getTeam = Team::find(post('id'));
        $getTeam->name = post('name');
        $getTeam->description = post('description');

        $getTeam->save();

        return Redirect::to('/events/teams/manage');
    }
}
