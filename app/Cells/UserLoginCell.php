<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class UserLoginCell extends Cell
{
    protected $dateTime;
    public function mount()
    {
        helper('date');
        $now = date('Y-m-d H:i:s', now());

        if (cache()->get('user')) {
            if (!cache()->get('logged_in')) {
                cache()->save('logged_in', $now, MINUTE * 5);
                $this->dateTime = $now;
            } else {
                $this->dateTime = cache()->get('logged_in');
            }
        }
    }

    public function getDateTimeProperty()
    {
        return $this->dateTime;
    }
}
