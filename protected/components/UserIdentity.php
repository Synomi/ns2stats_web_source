<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    public function __construct($steamId, $name) {
        $this->_id = $steamId;
        $this->username = $name;
    }

    public function getId() {
        return $this->_id;
    }

}