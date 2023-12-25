<?php

namespace app\components;

class AccessRule extends \yii\filters\AccessRule
{

    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true;
        }
        foreach ($this->roles as $role) {
            if ($role == '?' && $user->getIsGuest()) {
                return true;
            } else if (!$user->getIsGuest() && $role == '@') {
                return true;
            } else if (!$user->getIsGuest() && $role == $user->identity->role) {
                return true;
            }
        }

        return false;
    }
}
