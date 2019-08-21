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
}