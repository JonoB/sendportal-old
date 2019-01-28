<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a new user.';

    /**
     * The current name error.
     *
     * @var string
     */
    protected $nameError = '';

    /**
     * The current email error.
     *
     * @var string
     */
    protected $emailError = '';

    /**
     * The current password error.
     *
     * @var string
     */
    protected $passwordError = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userDetails = $this->collectDetails();

        return $this->registerUser($userDetails);
    }

    /**
     * Register a user with the provided details.
     *
     * @param array $userDetails
     */
    private function registerUser(array $userDetails)
    {
        $user = User::create($userDetails);

        $this->info('User Registered!');
        $this->table(['Name', 'Email'], [[$user->name, $user->email]]);
    }

    /**
     * Collect the account details.
     *
     * @return array
     */
    private function collectDetails(): array
    {
        $userDetails = [
            'name' => $this->collectName(),
            'email' => $this->collectEmail(),
            'password' => $this->collectPassword(),
        ];

        return $userDetails;
    }

    /**
     * Collect the name for the account.
     *
     * @return mixed
     */
    private function collectName()
    {
        $name = $this->ask('Enter Name');

        while ( ! $this->validName($name))
        {
            $this->error($this->nameError);
            $name = $this->ask('Enter Name');
        }

        return $name;
    }

    /**
     * Collect the email for the account.
     *
     * @return mixed
     */
    private function collectEmail()
    {
        $email = $this->ask('Enter Email Address');

        while ( ! $this->validEmail($email))
        {
            $this->error($this->emailError);
            $email = $this->ask('Enter Email Address');
        }

        return $email;
    }

    /**
     * Collect the password for the account.
     *
     * @return mixed
     */
    private function collectPassword()
    {
        $password = $this->ask('Enter Password');

        while ( ! $this->validPassword($password))
        {
            $this->error($this->passwordError);
            $password = $this->ask('Enter Password');
        }

        return bcrypt($password);
    }

    /**
     * Determine whether or not the password is valid.
     *
     * @param $name
     *
     * @return bool
     */
    private function validName($name): bool
    {
        if ( ! $name)
        {
            $this->setNameError('You must enter a name.');

            return false;
        }

        if (strlen($name) > 255)
        {
            $this->setName('The name must be less than 255 characters long.');

            return false;
        }

        return true;
    }

    /**
     * Determine whether or not the password is valid.
     *
     * @param $password
     *
     * @return bool
     */
    private function validPassword($password): bool
    {
        if ( ! $password)
        {
            $this->setPasswordError('You must enter a password.');

            return false;
        }
        elseif (strlen($password) < 6)
        {
            $this->setPasswordError('Your password must be at least six characters long.');

            return false;
        }

        return true;
    }

    /**
     * Determine whether or not the email is valid.
     *
     * @param $email
     *
     * @return bool
     */
    private function validEmail($email)
    {
        if ( ! $email)
        {
            $this->setEmailError('You must enter a valid email address');

            return false;
        }
        elseif (User::where('email', $email)->count())
        {
            $this->setEmailError('Email address is already in use.');

            return false;
        }
        elseif ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->setEmailError('You did not enter a valid email address.');

            return false;
        }
        elseif (strlen($email) > 255)
        {
            $this->setName('The email must be less than 255 characters long.');

            return false;
        }

        return true;
    }

    /**
     * Set the current email error.
     *
     * @param string $errorMessage
     */
    private function setNameError(string $errorMessage)
    {
        $this->nameError = $errorMessage;
    }

    /**
     * Set the current email error.
     *
     * @param string $errorMessage
     */
    private function setEmailError(string $errorMessage)
    {
        $this->emailError = $errorMessage;
    }

    /**
     * Set the current email error.
     *
     * @param string $errorMessage
     */
    private function setPasswordError(string $errorMessage)
    {
        $this->passwordError = $errorMessage;
    }
}
