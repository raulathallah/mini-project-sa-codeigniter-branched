<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class UserProfileCell extends Cell
{
    protected $user;
    public function mount($user)
    {
        $this->user = $user;
    }

    public function getUserProperty()
    {
        return $this->user;
    }
}
