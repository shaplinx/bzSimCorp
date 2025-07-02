<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ladder\Ladder;


class LadderController extends Controller
{
    function getAllRoles() {
        return $this->sendResponse(__("Fetched Successfully"),array_values(Ladder::$roles));
    }

    function getAllPermissions() {
        return $this->sendResponse(__("Fetched Successfully"),Ladder::$permissions);
    }
}
