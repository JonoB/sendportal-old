<?php namespace App\Interfaces;

interface BaseInterface
{

    /**
     * Return current active user id
     *
     * @return int|bool
     */
    public function getActiveUserId();

    /**
     * Return active user model
     *
     * @return \App\User
     */
    public function getActiveUser();

    /**
     * Returns model validation error messages
     *
     * @return array
     */
    public function getErrors();

    /**
     * Checks whether the model has any errors
     *
     * @return bool
     */
    public function hasErrors();
}