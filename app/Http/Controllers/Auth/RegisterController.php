<?php

namespace App\Http\Controllers\Auth;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\Services\Teams\AcceptInvitation;
use App\Services\Teams\CreateTeam;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var CreateTeam
     */
    private $createTeam;

    /**
     * @var AcceptInvitation
     */
    private $acceptInvitation;

    /**
     * RegisterController constructor
     *
     * @param CreateTeam $createTeam
     * @param AcceptInvitation $acceptInvitation
     * @return void
     */
    public function __construct(CreateTeam $createTeam, AcceptInvitation $acceptInvitation)
    {
        $this->middleware('guest');

        $this->acceptInvitation = $acceptInvitation;
        $this->createTeam = $createTeam;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => ['required_without:invitation', 'string', 'max:255'],
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return \DB::transaction(function() use ($data) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if ($token = request('invitation'))
            {
                // attach user to invited team
                $invitation = Invitation::where('token', $token)->first();

                $this->acceptInvitation->handle($user, $invitation);
            }
            else
            {
                // create a new team and attach as owner
                $this->createTeam->handle($user, $data, Team::ROLE_OWNER);
            }

            \Auth::login($user, true);

            if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail())
            {
                $user->sendEmailVerificationNotification();
            }

            return $user;
        });
    }
}
