<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\CreateTeamRequest;
use App\Models\Team;
use App\Repositories\TeamsRepository;
use App\Services\Teams\CreateTeam;

class TeamsController extends Controller
{
    /**
     * @var TeamsRepository
     */
    protected $teams;

    /**
     * @var CreateTeam
     */
    protected $createTeam;

    /**
     * TeamsController constructor.
     *
     * @param TeamsRepository $teams
     * @param CreateTeam $createTeam
     */
    public function __construct(TeamsRepository $teams, CreateTeam $createTeam)
    {
        $this->teams = $teams;
        $this->createTeam = $createTeam;
    }

    /**
     * Display a listing of teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('teams.index', [
            'teams' => user()->teams,
            'invitations' => user()->invitations()->with('team')->get(),
        ]);
    }

    /**
     * Store a newly created template in storage.
     *
     * @param CreateTeamRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(CreateTeamRequest $request)
    {
        $this->createTeam->handle(user(), $request->all());

        return redirect()->route('teams.index');
    }

    /**
     * Display the specified template.
     *
     * @param int $id
     *
     * @return void
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified template.
     *
     * @param Team $team
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Team $team)
    {
        // route model binding, so Team is passed in, and not an id
        abort_unless(user()->ownsTeam($team), 404);

        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified template in storage.
     *
     * @param CreateTeamRequest $request
     * @param Team $team
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(CreateTeamRequest $request, Team $team)
    {
        abort_unless(user()->ownsTeam($team), 404);

        $this->teams->update($team->id, ['name' => request('company_name')]);

        return redirect()->route('teams.index');
    }

    /**
     * Remove the specified template from storage.
     *
     * @param int $id
     *
     * @return void
     */
    public function destroy($id)
    {
        abort(404);
    }

    /**
     * Switch the active team
     *
     * @param $team
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Team $team)
    {
        abort_unless(user()->onTeam($team), 404);

        user()->switchToTeam($team);

        return redirect()->route('dashboard');
    }
}