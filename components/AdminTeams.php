<?php

namespace Cleanse\Event\Components;

use Flash;
use Input;
use Redirect;
use Session;
use Validator;
use ValidationException;
use System\Models\File;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Team;

class AdminTeams extends ComponentBase
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
        $this->addCss('assets/css/events.css');
        $this->addJs('assets/js/events.js');

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
        $newTeam->name        = post('name');
        $newTeam->region      = post('region');
        $newTeam->description = post('description');

        $newTeam->save();

        if (Input::hasFile('logo')) {
            $uploadedFile = Input::file('logo');

            $file = new File;
            $file->data = $uploadedFile;
            $file->is_public = true;
            $file->save();

            $newTeam->logo()->add($file);
        }

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
        $getTeam->region = post('region');
        $getTeam->description = post('description');

        $getTeam->save();

        return Redirect::to('/events/teams/manage');
    }

    public function onUpdateLogo()
    {
        $team = Team::find(post('id'));

        if (Input::hasFile('logo')) {
            $uploadedFile = Input::file('logo');

            $file = new File;
            $file->data = $uploadedFile;
            $file->is_public = true;
            $file->save();

            $team->logo()->add($file);
        }

        Flash::success($team->name);
        Session::flash('flashSuccess', true);
    }
}
